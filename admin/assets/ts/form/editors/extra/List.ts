import Editor from "../../Editor";
import Checkbox from "../Checkbox";
import DateEditor from "../Date";
import DateTime from "../DateTime";
import Hidden from "../Hidden";
import Number from "../Number";
import ObjectEditor from "../Object";
import Radio from "../Radio";
import Select from "../Select";
import Text from "../Text";
import TextArea from "../TextArea";
import ListItem from "./ListItem";
/**
 * List editor
 *
 * An array editor. Creates a list of other editor items.
 *
 * Special options:
 * @param {String} [options.schema.itemType]          The editor type for each item in the list. Default: 'Text'
 * @param {String} [options.schema.confirmDelete]     Text to display in a delete confirmation dialog. If falsey, will not ask for confirmation.
 */

import Settings from "../../Settings";

class List extends Editor {
	template = _.template(
		`
      <div>
        <div data-items></div>
        <button type="button" data-action="add"><%= addLabel %></button>
      </div>
    `,
		null,
		Settings.templateSettings
	);
	Editor: any;
	items: any[];
	$list: JQuery<HTMLElement>;

	constructor(options) {
		options = options || {};
		super(
			_.extend(
				{
					events: {
						'click [data-action="add"]': function (event) {
							event.preventDefault();
							this.addItem(undefined, true);
						},
					},
				},
				options || {}
			)
		);

		var schema = this.schema;
		if (!schema) throw new Error("Missing required option 'schema'");

		this.schema = _.extend(
			{
				addLabel: "Add",
			},
			schema
		);

		this.template = options.template || schema.listTemplate;

		//Determine the editor to use
		this.Editor = (function () {
			var type = schema.itemType;

			//Default to Text
			if (!type) return Text;

			if (_.isString(type)) {
				switch (type) {
					case "Text":
						return Text;
					case "TextArea":
						return TextArea;
					case "Select":
						return Select;
					case "Checkbox":
						return Checkbox;
					case "Radio":
						return Radio;
					case "Hidden":
						return Hidden;
					case "Object":
						return ObjectEditor;
					case "Number":
						return Number;
					case "Date":
						return DateEditor;
					case "DateTime":
						return DateTime;
					default:
						return Text;
				}
			}
			//Or whichever was passed
			return type;
		})();

		this.items = [];
	}

	render() {
		var value = this.value || [],
			$ = Backbone.$;

		//Create main element
		var $el = $(
			$.trim(
				this.template({
					addLabel: this.schema.addLabel,
				})
			)
		);

		//Store a reference to the list (item container)
		this.$list = $el.is("[data-items]") ? $el : $el.find("[data-items]");

		if (value instanceof Backbone.Collection) {
			value = value.toJSON();
		}
		//Add existing items
		if (value.length) {
			_.each(value, (itemValue) => {
				this.addItem(itemValue);
			});
		}

		//If no existing items create an empty one, unless the editor specifies otherwise
		else {
			if (!this.Editor.isAsync) this.addItem();
		}

		this._checkMaxCardinalityReached($el);

		this.setElement($el);
		this.$el.attr("id", this.id);
		this.$el.attr("name", this.key);

		if (this.hasFocus) this.trigger("blur", this);

		return this;
	}

	/**
	 * If number of items exceeds the maxListLength value set on the schema,
	 * hide any 'add' button in the passed in $element
	 *
	 * @param {jQuery selector} [$el] Where to look for the add button
	 */
	_checkMaxCardinalityReached($el) {
		if (this.schema.maxListLength && this.items.length >= this.schema.maxListLength) {
			$el.find('button[data-action="add"]').hide();
		}
	}

	/**
	 * Add a new item to the list
	 * @param {Mixed} [value]           Value for the new item editor
	 * @param {Boolean} [userInitiated] If the item was added by the user clicking 'add'
	 */
	addItem(value = "", userInitiated = false) {
		//Create the item
		var item = new ListItem({
			list: this,
			form: this.form,
			schema: this.schema,
			value: value,
			Editor: this.Editor,
			key: this.key,
		}).render();

		var _addItem = () => {
			this.items.push(item);
			this.$list.append(item.el);

			this._checkMaxCardinalityReached(this.$el);

			item.editor.on(
				"all",
				(event, ...args) => {
					if (event === "change") return;

					// args = ["key:change", itemEditor, fieldEditor]
					var args = _.toArray(args);
					args[0] = "item:" + event;
					args.splice(1, 0, this);
					// args = ["item:key:change", this=listEditor, itemEditor, fieldEditor]

					this.trigger.apply(this, args);
				},
				this
			);

			item.editor.on(
				"change",
				function () {
					if (!item.addEventTriggered) {
						item.addEventTriggered = true;
						this.trigger("add", this, item.editor);
					}
					this.trigger("item:change", this, item.editor);
					this.trigger("change", this);
				},
				this
			);

			item.editor.on(
				"focus",
				function () {
					if (this.hasFocus) return;
					this.trigger("focus", this);
				},
				this
			);
			item.editor.on(
				"blur",
				() => {
					if (!this.hasFocus) return;
					setTimeout(() => {
						if (
							_.find(this.items, function (item) {
								return item.editor.hasFocus;
							})
						)
							return;
						this.trigger("blur", this);
					}, 0);
				},
				this
			);

			if (userInitiated || value) {
				item.addEventTriggered = true;
			}

			if (userInitiated) {
				this.trigger("add", self, item.editor);
				this.trigger("change", self);
			}
		};

		//Check if we need to wait for the item to complete before adding to the list
		if (this.Editor.isAsync) {
			item.editor.on("readyToAdd", _addItem, this);
		}

		//Most editors can be added automatically
		else {
			_addItem();
			item.editor.focus();
		}

		return item;
	}

	/**
	 * Remove an item from the list
	 * @param {List.Item} item
	 */
	removeItem(item) {
		//Confirm delete
		var confirmMsg = this.schema.confirmDelete;
		if (confirmMsg && !confirm(confirmMsg)) return;

		var index = _.indexOf(this.items, item);

		this.items[index].remove();
		this.items.splice(index, 1);

		if (item.addEventTriggered) {
			this.trigger("remove", this, item.editor);
			this.trigger("change", this);
		}

		if (!this.items.length && !this.Editor.isAsync) this.addItem();

		// show the "add" button in case the max-length has not been reached
		if (this.schema.maxListLength && this.items.length < this.schema.maxListLength) {
			this.$el.find('button[data-action="add"]').show();
		}
	}

	getValue() {
		var values = _.map(this.items, function (item) {
			return item.getValue();
		});

		//Filter empty items
		return _.without(values, undefined, "");
	}

	setValue(value) {
		this.value = value;
		this.render();
	}

	focus() {
		if (this.hasFocus) return;

		if (this.items[0]) this.items[0].editor.focus();
	}

	blur() {
		if (!this.hasFocus) return;

		var focusedItem = _.find(this.items, function (item) {
			return item.editor.hasFocus;
		});

		if (focusedItem) focusedItem.editor.blur();
	}

	/**
	 * Override default remove function in order to remove item views
	 */
	remove() {
		_.invoke(this.items, "remove");

		this.remove.call(this);
		return this;
	}

	/**
	 * Run validation
	 *
	 * @return {Object|Null}
	 */
	validate() {
		//Collect errors
		var errors = _.map(this.items, function (item) {
			return item.validate();
		});

		//Check if any item has errors
		var hasErrors = _.compact(errors).length ? true : false;
		if (!hasErrors) return null;

		//If so create a shared error
		var fieldError = {
			type: "list",
			message: "Some of the items in the list failed validation",
			errors: errors,
		};

		return fieldError;
	}
}

export default List;

import Editor from "../../Editor";
import Text from "../Text";
import Settings from "../../Settings";
/**
 * A single item in the list
 *
 * @param {editors.List} options.list The List editor instance this item belongs to
 * @param {Function} options.Editor   Editor constructor function
 * @param {String} options.key        Model key
 * @param {Mixed} options.value       Value
 * @param {Object} options.schema     Field schema
 */
class ListItem extends Editor {
	//STATICS
	addEventTriggered = false;
	template = _.template(
		`<div>
        <span data-editor></span>
        <button type="button" data-action="remove">&times;</button>
      </div>
    `,
		null,
		Settings.templateSettings
	);

	errorClassName: "error";

	list: any;
	Editor: any;
	editor: any;

	constructor(options) {
		super(
			_.extend(
				{
					events: {
						'click [data-action="remove"]': function (event) {
							event.preventDefault();
							this.list.removeItem(this);
						},
						"keydown input[type=text]": function (event) {
							if (event.keyCode !== 13) return;
							event.preventDefault();
							this.list.addItem();
							this.list.$list.find("> li:last input").focus();
						},
					},
				},
				options || {}
			)
		);
		this.list = options.list;
		this.schema = options.schema || this.list.schema;
		this.value = options.value;
		this.Editor = options.Editor || Text;
		this.key = options.key;
		this.template = options.template || this.schema.itemTemplate;
		this.errorClassName = options.errorClassName;
		this.form = options.form;
	}

	render() {
		//Create editor
		this.editor = new Editor({
			key: this.key,
			schema: this.schema,
			value: this.value,
			list: this.list,
			item: this,
			form: this.form,
		}).render();

		//Create main element
		var $el = $($.trim(this.template()));

		$el.find("[data-editor]").append(this.editor.el);

		//Replace the entire element so there isn't a wrapper tag
		this.setElement($el);

		return this;
	}

	getValue() {
		return this.editor.getValue();
	}

	setValue(value) {
		this.editor.setValue(value);
	}

	focus() {
		this.editor.focus();
	}

	blur() {
		this.editor.blur();
	}

	remove() {
		this.editor.remove();

		this.remove.call(this);
		return this;
	}

	validate() {
		var value = this.getValue(),
			formValues = this.list.form ? this.list.form.getValue() : {},
			validators = this.schema.validators,
			getValidator = this.getValidator;

		if (this.editor.nestedForm && this.editor.nestedForm.validate) {
			return this.editor.nestedForm.validate();
		}

		if (!validators) return null;

		//Run through validators until an error is found
		var error = null;
		_.every(validators, function (validator) {
			error = getValidator(validator)(value, formValues);

			return error ? false : true;
		});

		//Show/hide error
		if (error) {
			this.setError(error);
		} else {
			this.clearError();
		}

		//Return error to be aggregated by list
		return error ? error : null;
	}

	/**
	 * Show a validation error
	 */
	setError(err) {
		this.$el.addClass(this.errorClassName);
		this.$el.attr("title", err.message);
	}

	/**
	 * Hide validation errors
	 */
	clearError() {
		this.$el.removeClass(this.errorClassName);
		this.$el.attr("title", null);
	}
}

export default ListItem;

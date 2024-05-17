import Form from "../../Form";
import Editor from "../../Editor";
import Field from "../../Field";
import Settings from "../../Settings";

/**
 * Base modal object editor for use with the List editor; used by Object
 * and NestedModal list types
 */
class ListModal extends Editor {
	//STATICS
	template = _.template(`<div><%= summary %></div>`, null, Settings.templateSettings);

	//The modal adapter that creates and manages the modal dialog.
	//Defaults to BootstrapModal (http://github.com/powmedia/backbone.bootstrap-modal)
	//Can be replaced with another adapter that implements the same interface.
	ModalAdapter: any = null;

	//Make the wait list for the 'ready' event before adding the item to the list
	isAsync = true;
	modal: any;
	nestedSchema: any;
	modalForm: any;

	/**
	 * @param {Object} options
	 * @param {Form} options.form                       The main form
	 * @param {Function} [options.schema.itemToString]  Function to transform the value for display in the list.
	 * @param {String} [options.schema.itemType]        Editor type e.g. 'Text', 'Object'.
	 * @param {Object} [options.schema.subSchema]       Schema for nested form,. Required when itemType is 'Object'
	 * @param {Function} [options.schema.model]         Model constructor function. Required when itemType is 'NestedModel'
	 */
	constructor(options) {
		options = options || {};

		super(
			_.extend(
				{
					events: {
						click: "openEditor",
					},
				},
				options
			)
		);

		//Dependencies
		if (!options.ModalAdapter) throw new Error("A ModalAdapter is required");

		this.form = options.form;
		if (!options.form) throw new Error('Missing required option: "form"');

		//Template
		this.template = options.template;
	}

	/**
	 * Render the list item representation
	 */
	render() {
		var self = this;

		//New items in the list are only rendered when the editor has been OK'd
		if (_.isEmpty(this.value)) {
			this.openEditor();
		}

		//But items with values are added automatically
		else {
			this.renderSummary();

			setTimeout(function () {
				self.trigger("readyToAdd");
			}, 0);
		}

		if (this.hasFocus) this.trigger("blur", this);

		return this;
	}

	/**
	 * Renders the list item representation
	 */
	renderSummary() {
		this.$el.html(
			$.trim(
				this.template({
					summary: this.getStringValue(),
				})
			)
		);
	}

	/**
	 * Function which returns a generic string representation of an object
	 *
	 * @param {Object} value
	 *
	 * @return {String}
	 */
	itemToString(value) {
		var createTitle = function (key) {
			var context = { key: key };

			return new Field(context).createTitle();
		};

		value = value || {};

		//Pretty print the object keys and values
		var parts = [];
		_.each(this.nestedSchema, function (schema, key) {
			var desc = schema.title ? schema.title : createTitle(key),
				val = value[key];

			if (_.isUndefined(val) || _.isNull(val)) val = "";

			parts.push(desc + ": " + val);
		});

		return parts.join("<br />");
	}

	/**
	 * Returns the string representation of the object value
	 */
	getStringValue() {
		var schema = this.schema,
			value = this.getValue();

		if (_.isEmpty(value)) return "[Empty]";

		//If there's a specified toString use that
		if (schema.itemToString) return schema.itemToString(value);

		//Otherwise use the generic method or custom overridden method
		return this.itemToString(value);
	}

	openEditor() {
		var form = (this.modalForm = new Form({
			schema: this.nestedSchema,
			data: this.value,
		}));

		var modal = (this.modal = new this.ModalAdapter({
			content: form,
			animate: true,
		}));

		modal.open();

		this.trigger("open", this);
		this.trigger("focus", this);

		modal.on("cancel", this.onModalClosed, this);

		modal.on("ok", _.bind(this.onModalSubmitted, this));
	}

	/**
	 * Called when the user clicks 'OK'.
	 * Runs validation and tells the list when ready to add the item
	 */
	onModalSubmitted() {
		var modal = this.modal,
			form = this.modalForm,
			isNew = !this.value;
		if (!form) {
			return;
		}

		//Stop if there are validation errors
		var error = form.validate();
		if (error) return modal.preventClose();

		//Store form value
		this.value = form.getValue();

		//Render item
		this.renderSummary();

		if (isNew) this.trigger("readyToAdd");

		this.trigger("change", this);

		this.onModalClosed();
	}

	/**
	 * Cleans up references, triggers events. To be called whenever the modal closes
	 */
	onModalClosed() {
		this.modal = null;
		this.modalForm = null;

		this.trigger("close", this);
		this.trigger("blur", this);
	}

	getValue() {
		return this.value;
	}

	setValue(value) {
		this.value = value;
	}

	focus() {
		if (this.hasFocus) return;

		this.openEditor();
	}

	blur() {
		if (!this.hasFocus) return;

		if (this.modal) {
			this.modal.trigger("cancel");
		}
	}
}

export default ListModal;

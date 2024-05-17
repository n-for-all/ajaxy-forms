import Editor from "../Editor";

/**
 * Object editor
 *
 * Creates a child form. For editing Javascript objects
 *
 * @param {Object} options
 * @param {Form} options.form                 The form this editor belongs to; used to determine the constructor for the nested form
 * @param {Object} options.schema             The schema for the object
 * @param {Object} options.schema.subSchema   The schema for the nested form
 */
class ObjectEditor extends Editor {
	//Prevent error classes being set on the main control; they are internally on the individual fields
	hasNestedForm = true;
	value: {};
	form: any;
	schema: any;
	nestedForm: any;

	constructor(options) {
		super(options);
		//Set default value for the instance so it's not a shared object
		this.value = {};

		//Init

		//Check required options
		if (!this.form) throw new Error('Missing required option "form"');
		if (!this.schema.subSchema) throw new Error("Missing required 'schema.subSchema' option for Object editor");
	}

	render() {
		//Get the constructor for creating the nested form; i.e. the same constructor as used by the parent form
		var NestedForm = this.form.constructor;

		//Create the nested form
		this.nestedForm = new NestedForm({
			schema: this.schema.subSchema,
			data: this.value,
			idPrefix: this.id + "_",
			Field: NestedForm.NestedField,
		});

		this._observeFormEvents();

		this.$el.html(this.nestedForm.render().el);

		if (this.hasFocus) this.trigger("blur", this);

		return this;
	}

	getValue() {
		if (this.nestedForm) return this.nestedForm.getValue();

		return this.value;
	}

	setValue(value) {
		this.value = value;

		this.render();
	}

	focus() {
		if (this.hasFocus) return;

		this.nestedForm.focus();
	}

	blur() {
		if (!this.hasFocus) return;

		this.nestedForm.blur();
	}

	remove() {
		this.nestedForm.remove();

		Backbone.View.prototype.remove.call(this);
		return this;
	}

	validate() {
		var errors = _.extend({}, super.validate.call(this), this.nestedForm.validate());
		return _.isEmpty(errors) ? false : errors;
	}

	_observeFormEvents() {
		if (!this.nestedForm) return;

		this.nestedForm.on(
			"all",
			function () {
				// args = ["key:change", form, fieldEditor]
				var args = _.toArray(arguments);
				args[1] = this;
				// args = ["key:change", this=objectEditor, fieldEditor]

				this.trigger.apply(this, args);
			},
			this
		);
	}
}

export default ObjectEditor;

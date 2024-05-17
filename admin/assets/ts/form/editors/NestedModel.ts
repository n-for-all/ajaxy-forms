import ObjectEditor from "./Object";

/**
 * NestedModel editor
 *
 * Creates a child form. For editing nested Backbone models
 *
 * Special options:
 *   schema.model:   Embedded model constructor
 */
class NestedModel extends ObjectEditor {
	constructor(options) {
		super(options);

		if (!this.form) throw new Error('Missing required option "form"');
		if (!options.schema.model) throw new Error('Missing required "schema.model" option for NestedModel editor');
	}

	render() {
		//Get the constructor for creating the nested form; i.e. the same constructor as used by the parent form
		var NestedForm = this.form.constructor;

		var data = this.value || {},
			nestedModel = this.schema.model;

		//Wrap the data in a model if it isn't already a model instance
		var modelInstance = data.constructor === nestedModel ? data : new nestedModel(data);

		this.nestedForm = new NestedForm({
			model: modelInstance,
			idPrefix: this.id + "_",
			fieldTemplate: "nestedField",
		});

		this._observeFormEvents();

		//Render form
		this.$el.html(this.nestedForm.render().el);

		if (this.hasFocus) this.trigger("blur", this);

		return this;
	}

	/**
	 * Update the embedded model, checking for nested validation errors and pass them up
	 * Then update the main model if all OK
	 *
	 * @return {Error|null} Validation error or null
	 */
	commit() {
		var error = this.nestedForm.commit();
		if (error) {
			this.$el.addClass("error");
			return error;
		}

		return this.commit.call(this);
	}
}

export default NestedModel;

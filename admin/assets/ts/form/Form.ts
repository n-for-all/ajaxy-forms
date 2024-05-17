/// <reference path='../node_modules/@types/backbone/index.d.ts' />

import Fieldset from "./Fieldset";
import Validators from "./Validators";
import NestedField from "./NestedField";
import Field from "./Field";

class Form extends Backbone.View<any> {
	hasFocus: boolean;
	options: any;
	schema: any;
	template: any;
	Field: any;
	NestedField: NestedField;
	selectedFields: any;
	fields: {};
	fieldsets: any[];
	idPrefix: any;
	data: any;

	Fieldset: Fieldset = null;

	static template = _.template(
		`<form>
        <div data-fieldsets></div>
        <% if (submitButton) { %>
            <button type="submit"><%= submitButton %></button>
        <% } %>
        </form>`,
		null,
		//@ts-ignore
		this.templateSettings
	);

	static validators = Validators;

	/**
	 * Constructor
	 *
	 * @param {Object} [options.schema]
	 * @param {Backbone.Model} [options.model]
	 * @param {Object} [options.data]
	 * @param {String[]|Object[]} [options.fieldsets]
	 * @param {String[]} [options.fields]
	 * @param {String} [options.idPrefix]
	 * @param {Form.Field} [options.Field]
	 * @param {Form.Fieldset} [options.Fieldset]
	 * @param {Function} [options.template]
	 * @param {Boolean|String} [options.submitButton]
	 */
	constructor(options: any) {
		super(options);

		this.hasFocus = false;
		this.events = <any>{
			submit: (event) => {
				this.trigger("submit", event);
			},
		};

		//Merge default options
		options = this.options = _.extend(
			{
				submitButton: false,
			},
			options
		);

		//Find the schema to use
		var schema = (this.schema = (() => {
			//Prefer schema from options
			if (options.schema) return _.result(options, "schema");

			//Then schema on model
			var model = options.model;
			if (model && model.schema) return _.result(model, "schema");

			//Then built-in schema
			if (this.schema) return _.result(this, "schema");

			//Fallback to empty schema
			return {};
		})());

		//Store important data
		_.extend(this, _.pick(options, "model", "data", "idPrefix", "templateData"));

		//Override defaults
		var constructor: any = this.constructor;
		this.template = options.template || this.template || constructor.template;
		this.Fieldset = options.Fieldset || this.Fieldset || constructor.Fieldset;
		this.Field = options.Field || this.Field || constructor.Field;
		this.NestedField = options.NestedField || this.NestedField || constructor.NestedField;

		//Check which fields will be included (defaults to all)
		var selectedFields = (this.selectedFields = options.fields || _.keys(schema));

		//Create fields
		var fields = (this.fields = {});

		_.each(
			selectedFields,
			_.bind(function (key) {
				var fieldSchema = schema[key];
				fields[key] = this.createField(key, fieldSchema);
			}, this)
		);

		//Create fieldsets
		var fieldsetSchema = options.fieldsets || _.result(this, "fieldsets") || _.result(this.model, "fieldsets") || [selectedFields];
		this.fieldsets = [];

		_.each(
			fieldsetSchema,
			_.bind(function (itemSchema) {
				this.fieldsets.push(this.createFieldset(itemSchema));
			}, this)
		);
	}

	/**
	 * Creates a Fieldset instance
	 *
	 * @param {String[]|Object[]} schema       Fieldset schema
	 *
	 * @return {Form.Fieldset}
	 */
	createFieldset = (schema) => {
		var options = {
			schema: schema,
			fields: this.fields,
			legend: schema.legend || null,
		};

		return new Fieldset(options);
	};

	/**
	 * Creates a Field instance
	 *
	 * @param {String} key
	 * @param {Object} schema       Field schema
	 *
	 * @return {Form.Field}
	 */
	createField = (key, schema) => {
		var options: { [x: string]: any } = {
			form: this,
			key: key,
			schema: schema,
			idPrefix: this.idPrefix,
		};

		if (this.model) {
			options.model = this.model;
		} else if (this.data) {
			options.value = this.data[key];
		} else {
			options.value = undefined;
		}

		var field = new Field(options);

		this.listenTo(field.editor, "all", (e) => this.handleEditorEvent(e, field.editor));

		return field;
	};

	/**
	 * Callback for when an editor event is fired.
	 * Re-triggers events on the form as key:event and triggers additional form-level events
	 *
	 * @param {String} event
	 * @param {Editor} editor
	 */
	handleEditorEvent = (event, editor) => {
		//Re-trigger editor events on the form
		var formEvent = editor.key + ":" + event;

		this.trigger.call(this, formEvent, this, editor, Array.prototype.slice.call([event, editor], 2));

		//Trigger additional events
		switch (event) {
			case "change":
				this.trigger("change", this);
				break;

			case "focus":
				if (!this.hasFocus) this.trigger("focus", this);
				break;

			case "blur":
				if (this.hasFocus) {
					//TODO: Is the timeout etc needed?

					setTimeout(() => {
						var focusedField = _.find(this.fields, function (field: any) {
							return field.editor.hasFocus;
						});

						if (!focusedField) this.trigger("blur", this);
					}, 0);
				}
				break;
		}
	};

	templateData = () => {
		var options = this.options;

		return {
			submitButton: options.submitButton,
		};
	};

	render = () => {
		var fields = this.fields,
			$ = Backbone.$;

		//Render form
		var $form = $($.trim(this.template(_.result(this, "templateData"))));

		//Render standalone editors
		$form
			.find("[data-editors]")
			.add($form)
			.each((i, el) => {
				var $container = $(el),
					selection = $container.attr("data-editors");

				if (_.isUndefined(selection)) return;

				//Work out which fields to include
				var keys = selection == "*" ? this.selectedFields || _.keys(fields) : selection.split(",");

				//Add them
				_.each(keys, function (key) {
					var field = fields[key];

					$container.append(field.editor.render().el);
				});
			});

		//Render standalone fields
		$form
			.find("[data-fields]")
			.add($form)
			.each((i, el) => {
				var $container = $(el),
					selection = $container.attr("data-fields");

				if (_.isUndefined(selection)) return;

				//Work out which fields to include
				var keys = selection == "*" ? this.selectedFields || _.keys(fields) : selection.split(",");

				//Add them
				_.each(keys, function (key) {
					var field = fields[key];

					$container.append(field.render().el);
				});
			});

		//Render fieldsets
		$form
			.find("[data-fieldsets]")
			.add($form)
			.each((i, el) => {
				var $container = $(el),
					selection = $container.attr("data-fieldsets");

				if (_.isUndefined(selection)) return;

				_.each(this.fieldsets, function (fieldset) {
					$container.append(fieldset.render().el);
				});
			});

		//Set the main element
		this.setElement($form);

		//Set class
		$form.addClass(this.className);

		//Set attributes
		if (this.attributes) {
			$form.attr(this.attributes);
		}

		return this;
	};

	/**
	 * Validate the data
	 *
	 * @return {Object}       Validation errors
	 */
	validate = (options) => {
		var fields = this.fields,
			model = this.model,
			errors: { [x: string]: any } = {};

		options = options || {};

		//Collect errors from schema validation
		_.each(fields, function (field: any) {
			var error = field.validate();
			if (error) {
				errors[field.key] = error;
			}
		});

		//Get errors from default Backbone model validator
		if (!options.skipModelValidate && model && model.validate) {
			var modelErrors = model.validate(this.getValue());

			if (modelErrors) {
				var isDictionary = _.isObject(modelErrors) && !_.isArray(modelErrors);

				//If errors are not in object form then just store on the error object
				if (!isDictionary) {
					errors._others = errors._others || [];
					errors._others.push(modelErrors);
				}

				//Merge programmatic errors (requires model.validate() to return an object e.g. { fieldKey: 'error' })
				if (isDictionary) {
					_.each(modelErrors, function (val, key) {
						//Set error on field if there isn't one already
						if (fields[key] && !errors[key]) {
							fields[key].setError(val);
							errors[key] = val;
						} else {
							//Otherwise add to '_others' key
							errors._others = errors._others || [];
							var tmpErr = {};
							tmpErr[key] = val;
							errors._others.push(tmpErr);
						}
					});
				}
			}
		}

		return _.isEmpty(errors) ? null : errors;
	};

	/**
	 * Update the model with all latest values.
	 *
	 * @param {Object} [options]  Options to pass to Model#set (e.g. { silent: true })
	 *
	 * @return {Object}  Validation errors
	 */
	commit = (options) => {
		//Validate
		options = options || {};

		var validateOptions = {
			skipModelValidate: !options.validate,
		};

		var errors = this.validate(validateOptions);
		if (errors) return errors;

		//Commit
		var modelError;

		var setOptions = _.extend(
			{
				error: function (model, e) {
					modelError = e;
				},
			},
			options
		);

		this.model.set(this.getValue(), setOptions);

		if (modelError) return modelError;
	};

	/**
	 * Get all the field values as an object.
	 * Use this method when passing data instead of objects
	 *
	 * @param {String} [key]    Specific field value to get
	 */
	getValue = (key?) => {
		//Return only given key if specified
		if (key) return this.fields[key].getValue();

		//Otherwise return entire form
		var values = {};
		_.each(this.fields, function (field: any) {
			values[field.key] = field.getValue();
		});

		return values;
	};

	/**
	 * Update field values, referenced by key
	 *
	 * @param {Object|String} key     New values to set, or property to set
	 * @param val                     Value to set
	 */
	setValue = (prop, val) => {
		var data = {};
		if (typeof prop === "string") {
			data[prop] = val;
		} else {
			data = prop;
		}

		var key;
		for (key in this.schema) {
			if (data[key] !== undefined) {
				this.fields[key].setValue(data[key]);
			}
		}
	};

	/**
	 * Returns the editor for a given field key
	 *
	 * @param {String} key
	 *
	 * @return {Editor}
	 */
	getEditor = (key) => {
		var field = this.fields[key];
		if (!field) throw new Error("Field not found: " + key);

		return field.editor;
	};

	/**
	 * Gives the first editor in the form focus
	 */
	focus = () => {
		if (this.hasFocus) return;

		//Get the first field
		var fieldset = this.fieldsets[0],
			field = fieldset.getFieldAt(0);

		if (!field) return;

		//Set focus
		field.editor.focus();
	};

	/**
	 * Removes focus from the currently focused editor
	 */
	blur = () => {
		if (!this.hasFocus) return;

		var focusedField: any = _.find(this.fields, function (field: any) {
			return field.editor.hasFocus;
		});

		if (focusedField) focusedField.editor.blur();
	};

	/**
	 * Manages the hasFocus property
	 *
	 * @param {String} event
	 */
	trigger = (event, ...args) => {
		if (event === "focus") {
			this.hasFocus = true;
		} else if (event === "blur") {
			this.hasFocus = false;
		}

		return Backbone.View.prototype.trigger.apply(this, args);
	};

	/**
	 * Override default remove function in order to remove embedded views
	 *
	 * TODO: If editors are included directly with data-editors="x", they need to be removed
	 * May be best to use XView to manage adding/removing views
	 */
	remove = () => {
		_.each(this.fieldsets, function (fieldset) {
			fieldset.remove();
		});

		_.each(this.fields, function (field: any) {
			field.remove();
		});

		return Backbone.View.prototype.remove.apply(this, []);
	};
}

export default Form;

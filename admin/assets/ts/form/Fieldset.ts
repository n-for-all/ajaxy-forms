import Settings from "./Settings";

class Fieldset extends Backbone.View<any> {
	template = _.template(
		`<fieldset data-fields>
    <% if (legend) { %>
      <legend><%= legend %></legend>
    <% } %>
  </fieldset>`,
		null,
		Settings.templateSettings
	);
	schema: any;
	fields: Partial<any> | Pick<any, any>;
	/**
	 * Constructor
	 *
	 * Valid fieldset schemas:
	 *   ['field1', 'field2']
	 *   { legend: 'Some Fieldset', fields: ['field1', 'field2'] }
	 *
	 * @param {String[]|Object[]} options.schema      Fieldset schema
	 * @param {Object} options.fields           Form fields
	 */
	constructor(options) {
        super(options);
		options = options || {};

		//Create the full fieldset schema, merging defaults etc.
		var schema = (this.schema = this.createSchema(options.schema));

		//Store the fields for this fieldset
		this.fields = _.pick(options.fields, schema.fields);

		//Override defaults
		this.template = options.template || schema.template || this.template;
	}

	/**
	 * Creates the full fieldset schema, normalising, merging defaults etc.
	 *
	 * @param {String[]|Object[]} schema
	 *
	 * @return {Object}
	 */
	createSchema(schema) {
		//Normalise to object
		if (_.isArray(schema)) {
			schema = { fields: schema };
		}

		//Add null legend to prevent template error
		schema.legend = schema.legend || null;

		return schema;
	}

	/**
	 * Returns the field for a given index
	 *
	 * @param {Number} index
	 *
	 * @return {Field}
	 */
	getFieldAt(index) {
		var key = this.schema.fields[index];

		return this.fields[key];
	}

	/**
	 * Returns data to pass to template
	 *
	 * @return {Object}
	 */
	templateData() {
		return this.schema;
	}

	/**
	 * Renders the fieldset and fields
	 *
	 * @return {Fieldset} this
	 */
	render() {
		var fields = this.fields,
			$ = Backbone.$;

		//Render fieldset
		var $fieldset = $($.trim(this.template(_.result(this, "templateData"))));

		//Render fields
		$fieldset
			.find("[data-fields]")
			.add($fieldset)
			.each(function (i, el) {
				var $container = $(el),
					selection = $container.attr("data-fields");

				if (_.isUndefined(selection)) return;

				_.each(fields, function (field) {
					$container.append(field.render().el);
				});
			});

		this.setElement($fieldset);

		return this;
	}

	/**
	 * Remove embedded views then self
	 */
	remove() {
		_.each(this.fields, function (field) {
			field.remove();
		});

		Backbone.View.prototype.remove.call(this);
		return this;
	}
}

export default Fieldset;

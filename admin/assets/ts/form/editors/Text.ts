import Editor from "../Editor";

/**
 * Text
 *
 * Text input with focus, blur and change events
 */
class Text extends Editor {
	defaultValue: string | number = "";

	previousValue: string | number | string[] = "";

	constructor(options) {
		super(
			_.extend(
				{
					events: {
						keyup: "determineChange",
						keypress: (event) => {
							setTimeout(() => {
								this.determineChange();
							}, 0);
						},
						select: function (event) {
							this.trigger("select", this);
						},
						focus: function (event) {
							this.trigger("focus", this);
						},
						blur: function (event) {
							this.trigger("blur", this);
						},
					},
					tagName: "input",
				},
				options || {}
			)
		);

		var schema = this.schema;

		//Allow customising text type (email, phone etc.) for HTML5 browsers
		var type = "text";

		if (schema && schema.editorAttrs && schema.editorAttrs.type) type = schema.editorAttrs.type;
		if (schema && schema.dataType) type = schema.dataType;

		this.$el.attr("type", type);
	}

	/**
	 * Adds the editor to the DOM
	 */
	render() {
		this.setValue(this.value);

		return this;
	}

	determineChange() {
		var currentValue = this.$el.val();
		var changed = currentValue !== this.previousValue;

		if (changed) {
			this.previousValue = currentValue;

			this.trigger("change", this);
		}
	}

	/**
	 * Returns the current editor value
	 * @return {String}
	 */
	getValue() {
		return this.$el.val();
	}

	/**
	 * Sets the value of the form element
	 * @param {String}
	 */
	setValue(value) {
		this.value = value;
		this.$el.val(value);
		this.previousValue = this.$el.val();
	}

	focus() {
		if (this.hasFocus) return;

		this.$el.focus();
	}

	blur() {
		if (!this.hasFocus) return;

		this.$el.blur();
	}

	select() {
		this.$el.select();
	}
}

export default Text;

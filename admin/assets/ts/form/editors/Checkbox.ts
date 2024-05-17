import Editor from "../Editor";

/**
 * Checkbox editor
 *
 * Creates a single checkbox, i.e. boolean value
 */
class Checkbox extends Editor {
	defaultValue = false;

	constructor(options) {
		super(
			_.extend(
				{
					tagName: "input",
					events: <any>{
						click: function (event) {
							this.trigger("change", this);
						},
						focus: function (event) {
							this.trigger("focus", this);
						},
						blur: function (event) {
							this.trigger("blur", this);
						},
					},
				},
				options || {}
			)
		);

		this.$el.attr("type", "checkbox");
	}

	/**
	 * Adds the editor to the DOM
	 */
	render() {
		this.setValue(this.value);

		return this;
	}

	getValue() {
		return this.$el.prop("checked");
	}

	setValue(value) {
		if (value) {
			this.$el.prop("checked", true);
		} else {
			this.$el.prop("checked", false);
		}
		this.value = !!value;
	}

	focus() {
		if (this.hasFocus) return;

		this.$el.focus();
	}

	blur() {
		if (!this.hasFocus) return;

		this.$el.blur();
	}
}

export default Checkbox;

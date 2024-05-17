import Text from "./Text";

/**
 * NUMBER
 *
 * Normal text input that only allows a number. Letters etc. are not entered.
 */
class Number extends Text {
	defaultValue = 0;

	constructor(options) {
		super(options);

		this.events = <any>_.extend({}, this.events, {
			keypress: "onKeyPress",
			change: "onKeyPress",
			input: "determineChange",
		});

		var schema = this.schema;

		this.$el.attr("type", "number");

		if (!schema || !schema.editorAttrs || !schema.editorAttrs.step) {
			// provide a default for `step` attr,
			// but don't overwrite if already specified
			this.$el.attr("step", "any");
		}
	}

	/**
	 * Check value is numeric
	 */
	onKeyPress(event) {
		const delayedDetermineChange = () => {
			setTimeout(() => {
				this.determineChange();
			}, 0);
		};

		//Allow backspace
		if (event.charCode === 0) {
			delayedDetermineChange();
			return;
		}

		//Get the whole new value so that we can prevent things like double decimals points etc.
		var newVal = this.$el.val();
		if (event.charCode != undefined) {
			newVal = newVal + String.fromCharCode(event.charCode);
		}

		var numeric = /^-?[0-9]*\.?[0-9]*$/.test(newVal.toString());

		if (numeric) {
			delayedDetermineChange();
		} else {
			event.preventDefault();
		}
	}

	getValue() {
		var value = this.$el.val();

		return value === "" ? null : parseFloat(value.toString());
	}

	setValue(value) {
		value = (function () {
			if (_.isNumber(value)) return value;

			if (_.isString(value) && value !== "") return parseFloat(value);

			return null;
		})();

		if (_.isNaN(value)) value = null;
		this.value = value;
		super.setValue.call(this, value);
	}
}

export default Number;

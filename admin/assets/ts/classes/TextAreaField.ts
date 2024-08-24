class TextAreaField extends Backbone.View<any> {
	field: any;
	basename: string;
	value: string;
	constructor(basename, field, value: string) {
		super();

		this.field = field;
		this.basename = basename;
		this.value = value;
	}

	createField() {
		this.$el.addClass(["af-field", `af-field-${this.field.type}`]);

		if (this.field.label) {
			let label = jQuery("<label></label>").html(this.field.label);
			this.$el.append(label);
		}

		let inputDiv = jQuery("<div></div>").addClass(["af-field-input", "af-field-input-" + this.field.type, "af-field-input-" + this.field.name]);
		let input = jQuery("<textarea></textarea>").attr("name", `${this.basename}[${this.field.name}]`).addClass("widefat").val(this.value);
		inputDiv.append(input);

		if (this.field.help) {
			let small = jQuery("<small></small>").html(this.field.help);
			inputDiv.append(small);
		}

		this.$el.append(inputDiv);
	}

	render() {
		this.createField();
		return this;
	}
}

export default TextAreaField;

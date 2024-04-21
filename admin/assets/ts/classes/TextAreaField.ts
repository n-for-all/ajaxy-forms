class TextAreaField extends Backbone.View<any> {
	field: any;
	basename: string;
	constructor(basename, field) {
		super();

		this.field = field;
        this.basename = basename;
	}

	createField() {
		this.$el.addClass(["af-field", `af-field-${this.field.type}`]);

		if (this.field.label) {
			let label = jQuery("<label></label>").html(this.field.label);
			this.$el.append(label);
		}

		let inputDiv = jQuery("<div></div>").addClass("af-field-input");
		let input = jQuery("<textarea></textarea>").attr("name", `${this.basename}[${this.field.name}]`).addClass("widefat");
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

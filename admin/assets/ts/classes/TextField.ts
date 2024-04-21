/// <reference path='../node_modules/@types/backbone/index.d.ts' />

class TextField extends Backbone.View<any> {
	field: any;
	basename: any;
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

		let input = jQuery("<input></input>")
			.attr("type", this.field.type || "text")
			.attr("name", `${this.basename}[${this.field.name}]`)
			.addClass(["widefat", `af-input-${this.field.name}`]);
		if (this.field.default) {
			if (this.field.type === "checkbox" || this.field.type === "radio") {
				input.attr("checked", "checked");
				input.val(1);
			} else {
				input.val(this.field.default);
			}
		}
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

export default TextField;

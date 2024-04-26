/// <reference path='../node_modules/@types/backbone/index.d.ts' />

class TextField extends Backbone.View<any> {
	field: any;
	basename: any;
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

		let inputDiv = jQuery("<div></div>").addClass("af-field-input");

		let input = jQuery("<input></input>")
			.attr("type", this.field.type || "text")
			.attr("name", `${this.basename}[${this.field.name}]`)
			.val(this.value)
			.addClass(["widefat", `af-input-${this.field.name}`]);

		if (this.field.type === "checkbox" || this.field.type === "radio") {
			(this.field.default || this.value == "1") && input.attr("checked", "checked");
			input.val(1);
		} else {
			if (!this.value || this.value === "") {
				input.val(this.field.default || "");
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

class SelectField extends Backbone.View<any> {
	field: any;
	basename: string;
	value: string;
	constructor(basename: string, field, value: string) {
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
		let select = jQuery("<select></select>").attr("name", `${this.basename}[${this.field.name}]`).addClass("widefat");

		Object.keys(this.field.options)
			.map((key) => {
				let option = this.field.options[key];
				let optionEl = jQuery("<option></option>").attr("value", key).html(option);
				if (key == this.value) {
					optionEl.attr("selected", "selected");
				} else if (this.value == '' && key == this.field.default) {
					optionEl.attr("selected", "selected");
				}
				select.append(optionEl);
			})
			.join("");
		inputDiv.append(select);

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

export default SelectField;

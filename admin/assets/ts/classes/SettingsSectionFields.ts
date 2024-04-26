/// <reference path='../node_modules/@types/backbone/index.d.ts' />

import RepeaterField from "./RepeaterField";
import SelectField from "./SelectField";
import TextAreaField from "./TextAreaField";
import TextField from "./TextField";

class SettingsSectionFields extends Backbone.View<any> {
	fields: any;
	basename: any;
	data: any;
	constructor(basename: string, fields, data: any) {
		super();
		this.fields = fields;
		this.basename = basename;
		this.data = data;
	}
	render() {
		if (!this.fields || this.fields.length === 0) return this;
		let fieldMap = Object.keys(this.fields).map((key) => {
			let _field = this.fields[key];
			switch (_field.type) {
				case "repeater":
					return new RepeaterField(this.basename, _field, this.data ? this.data[_field.name] || [] : []);
				case "select":
					return new SelectField(this.basename, _field, this.data ? this.data[_field.name] || "" : "");
				case "textarea":
					return new TextAreaField(this.basename, _field, this.data ? this.data[_field.name] || "" : "");
				default:
					return new TextField(this.basename, _field, this.data ? this.data[_field.name] || "" : "");
			}
		});

		fieldMap.forEach((field) => {
			this.$el.append(field.render().el);
		});
		return this;
	}
}

export default SettingsSectionFields;

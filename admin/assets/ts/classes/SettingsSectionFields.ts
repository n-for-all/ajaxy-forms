/// <reference path='../node_modules/@types/backbone/index.d.ts' />

import RepeaterField from "./RepeaterField";
import SelectField from "./SelectField";
import TextAreaField from "./TextAreaField";
import TextField from "./TextField";

class SettingsSectionFields extends Backbone.View<any> {
	fields: any;
	basename: any;
	constructor(basename: string, fields) {
		super();
		this.fields = fields;
		this.basename = basename;
	}
	render() {
		if (!this.fields || this.fields.length === 0) return this;
		let fieldMap = Object.keys(this.fields).map((key) => {
			let _field = this.fields[key];
			switch (_field.type) {
				case "repeater":
					return new RepeaterField(this.basename, _field, []);
				case "select":
					return new SelectField(this.basename, _field);
				case "textarea":
					return new TextAreaField(this.basename, _field);
				default:
					return new TextField(this.basename, _field);
			}
		});

		fieldMap.forEach((field) => {
			this.$el.append(field.render().el);
		});
		return this;
	}
}

export default SettingsSectionFields;

/// <reference path='../node_modules/@types/backbone/index.d.ts' />

import FieldView from "./FieldView";
import RepeaterField from "./RepeaterField";
import SelectField from "./SelectField";
import TextAreaField from "./TextAreaField";
import TextField from "./TextField";

class SettingsSectionFields extends Backbone.View<any> {
	fields: any;
	basename: any;
	data: any;
    fieldView: FieldView;
	constructor(fieldView, basename: string, fields, data: any) {
		super();
		this.fieldView = fieldView;
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
                    let textField = new TextField(this.basename, _field, this.data ? this.data[_field.name] || "" : "");
                    if(_field.name == 'label'){
                        textField.$el.on("blur",  "input", (e) => {
                            let value = jQuery(e.target).val();
                            if (!value || value === "") {
                                this.fieldView.setTitle("No Label");
                                return;
                            }
                            this.fieldView.setTitle(value);
                        });

                        this.fieldView.setTitle(this.data[_field.name] || "No Label");
                    }
					return textField; 
			}
		});

		fieldMap.forEach((field) => {
			this.$el.append(field.render().el);
		});

		return this;
	}
}

export default SettingsSectionFields;

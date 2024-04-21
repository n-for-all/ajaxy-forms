/// <reference path='../node_modules/@types/backbone/index.d.ts' />

import DraggableModel from "./DraggableModel";
import Settings from "./Settings";
import SettingsHeader from "./SettingsHeader";

declare let ajaxyFormsBuilder: {
	fields: { [x: string]: any };
};

class FieldView extends Backbone.View<DraggableModel> {
	data: any;
	type: string;
	index: number;
	constructor(index, type, data) {
		super({ tagName: "li" });

		//_.template($("#templ").html());
		this.type = type;
		this.data = data;
		this.index = index;
	}

	createSettings(field) {
		return new Settings(this.index, field, () => {
			this.remove();
		});
	}
	render() {
		this.el.classList.add(`form-item-${this.index}`);
		let field = ajaxyFormsBuilder.fields[this.type];
		let settings = this.createSettings(field);
		let header = new SettingsHeader(field, () => {
			settings.toggle();
		});
		this.$el.append(header.render().el); 
		this.$el.append(settings.render().el);
		this.$el.on("blur", '.expand-settings-basic input.af-input-label', function () {
			if (!this.value || this.value === "") {
				header.title.text("No Label");
				return;
			}
			header.title.text(this.value);
		});
		return this;
	}
}

export default FieldView;

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
	sortIndex: number;
	constructor(index, type, data, sortIndex) {
		super({ tagName: "li" });

		this.type = type;
		this.data = data;
		this.index = index;
        this.sortIndex = sortIndex;
	}

	createSettings(field) {
		return new Settings(this.index, field, this.data, () => {
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
		this.$el.append(`<input class="type-index" name="fields[${this.index}][type]" type="hidden" value="${this.type}">`);
		this.$el.append(`<input class="sort-index" name="fields[${this.index}][_sort]" type="hidden" value="${this.sortIndex}">`);
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

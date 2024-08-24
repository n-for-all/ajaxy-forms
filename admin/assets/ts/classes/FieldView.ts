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
    header: SettingsHeader;
    settings: Settings;
    field: any;
	constructor(index, type, data, sortIndex) {
		super({ tagName: "li" });

		this.type = type;
		this.data = data;
		this.index = index;
        this.sortIndex = sortIndex;

        this.field = ajaxyFormsBuilder.fields[this.type];
        if(!this.field) {
            console.error(`Field type "${this.type}" not found.`, this.data);
            return;
        }
		this.settings = this.createSettings(this.field);
		this.header = new SettingsHeader(this.field, () => {
			this.settings.toggle();
		});
	}

	createSettings(field) {
		return new Settings(this, this.index, field, this.data, () => {
			this.remove();
		});
	}

    setTitle(title) {
        this.header.title.text(title);
    }
	render() {
		this.el.classList.add(`form-item-${this.index}`, `form-type-${this.type}`);
		
        if(!this.field){
            console.error(`Field type "${this.type}" not found.`, this.data);
            return this;
        }
		this.$el.append(this.header.render().el); 
		this.$el.append(this.settings.render().el);
		this.$el.append(`<input class="type-index" name="fields[${this.index}][type]" type="hidden" value="${this.type}">`);
		this.$el.append(`<input class="sort-index" name="fields[${this.index}][_sort]" type="hidden" value="${this.sortIndex}">`);
		
		return this;
	}
}

export default FieldView;

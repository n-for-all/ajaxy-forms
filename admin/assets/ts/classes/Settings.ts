/// <reference path='../node_modules/@types/backbone/index.d.ts' />

import ConstraintsView from "./ConstraintsView";
import SettingsSectionFields from "./SettingsSectionFields";

declare let ajaxyFormsBuilder: {
	constraints: { [x: string]: any };
};

class Settings extends Backbone.View<any> {
	field: any;
	onRemove: any;
	index: number;
    data: any;
	constructor(index, field, data, onRemove) {
		super();
		this.field = field;
		this.data = data;
		this.onRemove = onRemove;
		this.index = index;
	}

	createBasicSettings() {
		let basicSettings = jQuery("<div></div>").addClass("basic-settings");
		let basicInnerSection = jQuery("<div></div>").addClass("section-inner");

		basicInnerSection.append(new SettingsSectionFields(`fields[${this.index}]`, this.field.properties.basic.fields, this.data).render().el);
		basicSettings.append(basicInnerSection);
		return basicSettings;
	}

	createSettings(data, type) {
		let settings = jQuery("<div></div>").addClass(["expand-settings", "expand-settings-" + type]);
		if (data.expanded) {
			settings.addClass("active");
		}
		let heading = jQuery("<h3></h3>").html(`<span>${data.label}</span>`);

		let toggle = jQuery("<a></a>").attr("href", "#").addClass("item-toggle")
			.html(`<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 12H12M12 12H18M12 12V18M12 12V6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>`);

		toggle.on("click", (e) => {
			e.preventDefault();
			settings.toggleClass("active");
		});

		heading.append(toggle);
		settings.append(heading);

		let toggleInnerSection = jQuery("<div></div>").addClass("section-toggle");
		let innerSection = jQuery("<div></div>").addClass("section-inner");

		innerSection.append(new SettingsSectionFields(`fields[${this.index}]`, data.fields, this.data).render().el);
		toggleInnerSection.append(innerSection);
		settings.append(toggleInnerSection);
		return settings;
	}
	createConstraintsSettings(data) {
		let settings = jQuery("<div></div>").addClass(["expand-settings"]);
		if (data.expanded) {
			settings.addClass("active");
		}
		let heading = jQuery("<h3></h3>").html(`<span>${data.label}</span>`);

		let toggle = jQuery("<a></a>").attr("href", "#").addClass("item-toggle")
			.html(`<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 12H12M12 12H18M12 12V18M12 12V6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>`);

		toggle.on("click", (e) => {
			e.preventDefault();
			settings.toggleClass("active");
		});

		heading.append(toggle);
		settings.append(heading);

		let toggleInnerSection = jQuery("<div></div>").addClass("section-toggle");
		let innerSection = jQuery("<div></div>").addClass("section-inner");

		innerSection.append(new ConstraintsView(`fields[${this.index}]`, this.data.constraints || []).render().el);
		toggleInnerSection.append(innerSection);
		settings.append(toggleInnerSection);
		return settings;
	}
	render() {
		this.el.classList.add("wrap-settings", "wp-clearfix");

		let innerSettings = jQuery("<div></div>").addClass("settings-inner");

		innerSettings.append(this.createSettings(this.field.properties.basic, "basic"));
		innerSettings.append(this.createSettings(this.field.properties.advanced, "advanced"));
		innerSettings.append(
			this.createConstraintsSettings({
				label: "Validation",
				constraints: ajaxyFormsBuilder.constraints,
			})
		);

		innerSettings.append(jQuery("<hr/>"));

		let settingsActions = jQuery("<div></div>").addClass(["af-item-actions", "description-wide", "submitbox"]);

		let settingsAction = jQuery("<a></a>")
			.html("Remove")
			.addClass(["item-delete", "submitdelete", "deletion"])
			.attr("href", "#")
			.on("click", (e) => {
				e.preventDefault();
				this.onRemove();
			});

		settingsActions.append(settingsAction);
		innerSettings.append(settingsActions);
		this.$el.append(innerSettings);

		return this;
	}

	toggle() {
		this.el.classList.toggle("active");
	}
}

export default Settings;

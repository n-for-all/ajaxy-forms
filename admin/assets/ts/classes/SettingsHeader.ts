/// <reference path='../node_modules/@types/backbone/index.d.ts' />

class SettingsHeader extends Backbone.View<any> {
	title: JQuery<HTMLElement>;
	field: any;
	onToggle: any;
	constructor(field, onToggle) {
		super();
		this.field = field;
		this.onToggle = onToggle;
	}

	render() {
		this.$el.addClass("af-item-bar");

		let innerHeader = jQuery("<div></div>").addClass(["af-item-handle", "ui-sortable-handle"]);
		let headerTitle = jQuery("<label></label>").addClass("item-title");

		this.title = jQuery("<span></span>").addClass("af-item-title").html("No Label");

		headerTitle.append(this.title);
		innerHeader.append(headerTitle);

		let itemControls = jQuery("<div></div>").addClass("item-controls");

		if (this.field.docs) {
			itemControls.html(
				`<a target="_blank" href="${this.field.docs}" class="item-docs"><span class="dashicons dashicons-editor-help"></span></a>`
			);
		}
		itemControls.append(`<span class="item-type">${this.field.label}</span>`);

		jQuery('<a class="item-edit" href="#"></a>')
			.on("click", (e) => {
				e.preventDefault();
				this.onToggle();
			})
			.appendTo(itemControls);

		innerHeader.append(itemControls);

		this.$el.append(innerHeader);
		return this;
	}
}

export default SettingsHeader;

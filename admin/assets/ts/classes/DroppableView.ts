/// <reference path='../node_modules/@types/backbone/index.d.ts' />

import DraggableModel from "./DraggableModel";
import DroppableCollection from "./DroppableCollection";
import FieldView from "./FieldView";

class DroppableView extends Backbone.View<DraggableModel> {
	collection: DroppableCollection;
	fields: Array<any> = [];
	sortable: any;
	onDrop: (event, ui) => {};
	constructor(options: any = {}) {
		super(options);
		this.collection = options.collection;
		this.onDrop = options.onDrop;
		this.render();
	}
	droppable(options: any = {}) {
		this.$el.data("view", this);
		this.$el.data("collection", this.collection);

		this.collection.view = this; // so collection changes can update the views
		this.fields = [];

		//@ts-ignore
		this.sortable = this.$el.sortable({
				placeholder: "ui-state-highlight",
				handle: ".ui-sortable-handle",
				receive: this.onDrop.bind(this),
				update: (event, ui) => {
					this.$el.children().each((index, el) => {
						jQuery(el).find(".sort-index").val(index);
					});
				},
			})
			.disableSelection();
	}

	render() {
		return this;
	}

	add(type, data, index) {
		let fieldView = new FieldView(this.fields.length, type, data, index);
		this.fields.push(fieldView);

		let record = fieldView.render().el;
		this.insertAt(index, record);
		try {
			setTimeout(() => {
				window.scrollTo({
					top: jQuery(record).offset().top - 200,
					behavior: "smooth",
				});
			}, 300);
		} catch (e) {}
	}

	insertAt(index, item) {
		if (this.$el.children().length == 0) {
			return this.$el.append(item);
		} else {
			return this.$el.children().each(() => {
				if (index === 0) {
					this.$el.prepend(item);
				} else {
					this.$el
						.children()
						.eq(index - 1)
						.after(item);
				}
			});
		}
	}
}

export default DroppableView;

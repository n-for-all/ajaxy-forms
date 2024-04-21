/// <reference path='../node_modules/@types/backbone/index.d.ts' />

import DraggableModel from "./DraggableModel";
import DraggableView from "./DraggableView";
import DroppableCollection from "./DroppableCollection";
import FieldView from "./FieldView";

class DroppableView extends Backbone.View<DraggableModel> {
	collection: DroppableCollection;
	fields: Array<any> = [];
	sortable: any;
	droppable(options: any = {}) {
		this.$el.data("view", this);
		this.$el.data("collection", this.collection);

		this.collection.view = this; // so collection changes can update the views
		this.fields = [];

		//@ts-ignore
		this.sortable = this.$el.sortable({
			placeholder: "ui-state-highlight",
			handle: ".ui-sortable-handle",
		});
	}

	canDrop(draggableView: DraggableView) {
		return true;
	}

	render() {
		return this;
	}

	add(type, data) {
		let fieldView = new FieldView(this.fields.length, type, data);
		this.fields.push(fieldView);

		let record = fieldView.render().el;
		this.$el.append(record);
		try {
			setTimeout(() => {
				window.scrollTo({
					top: jQuery(record).offset().top - 200,
					behavior: "smooth",
				});
			}, 300);
		} catch (e) {}
	}

	// remove(fieldView: FieldView) {
	//     this.fields.splice(this.fields.indexOf(fieldView), 1);
	//     fieldView.$el.detach();

	//     return this;
	// }
}

export default DroppableView;

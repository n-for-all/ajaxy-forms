/// <reference path='../node_modules/@types/backbone/index.d.ts' />

import DraggableModel from "./DraggableModel";
import DroppableView from "./DroppableView";

class DraggableView extends Backbone.View<DraggableModel> {
	initialize() {
		this.model.view = this; // so updates to the underlying collection can affect the model
		this.$el.attr("draggable", "true");
	}

	draggable(options: any = {}) {
		options.canDrop = this._canDrop.bind(this);
		options.didDrop = this._didDrop.bind(this);

		//@ts-ignore
		this.$el.dragdrop(options);

		this.$el.data("view", this);
		this.$el.data("model", this.model);
	}

	private _canDrop(el: any) {
		const droppableView = jQuery(el).data("view");
		if (!droppableView || !(droppableView instanceof DroppableView)) {
			return false;
		}

		return droppableView.canDrop(this) && this.canDrop(droppableView);
	}

	private _didDrop(src: any, dst: any) {
		const draggableView = src.data("view");
		const draggableModel = src.data("model");
		const droppableView = dst.data("view");
		const srcCollection = src.parent().data("collection");
		const dstCollection = dst.data("collection");

		// update the model to belong to the collection
		// if the src is currently in a draggable view, remove it from the collection
		if (srcCollection) {
			srcCollection.remove(draggableModel);
			droppableView.trigger("remove");
		}
		if (dstCollection) {
			dstCollection.add(draggableModel);
			droppableView.trigger("add");
		}

		draggableView.trigger("drag");
		draggableModel.trigger("drag");

		this.didDrop(draggableView, droppableView);
	}

	canDrop(droppableView: DroppableView) {
		return true;
	}

	didDrop(draggableView: DraggableView, droppableView: DroppableView) {
		// override me
		let srcElm = draggableView.$el;
		let type = srcElm.data("type");
		// let destElm = droppableView.$el;
		// console.log("DID DROP", type, srcElm, destElm);

		droppableView.add(type, {});
		// destElm.append(droppableView.render().el);
		// let field = new Field(properties);
	}
}

export default DraggableView;

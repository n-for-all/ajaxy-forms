/// <reference path='../node_modules/@types/backbone/index.d.ts' />

import DraggableModel from "./DraggableModel";

class DroppableCollection extends Backbone.Collection<DraggableModel> {
	view: Backbone.View;
	id: number;
	initialize() {
		this.on("add", this._add);
		this.on("remove", this._remove);
	}

	private _add(model: any) {
		if (this.view && model.view) {
			this.view.$el.append(model.view.$el);
		}
	}

	private _remove(model: any) {
		if (model.view) {
			model.view.$el.detach();
		}
	}
}

export default DroppableCollection;

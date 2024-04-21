/// <reference path='../node_modules/@types/backbone/index.d.ts' />

class DraggableModel extends Backbone.Model {
	view: Backbone.View;
	type: string;
}

export default DraggableModel;

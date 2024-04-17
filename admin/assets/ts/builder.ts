/// <reference path='./node_modules/@types/backbone/index.d.ts' />

declare let ajaxyFormsBuilder: {
	fields: { [x: string]: any };
};

class Template {
	static renderField(index, field) {
		let properties = field.properties;

		let li = document.createElement("li");
		li.classList.add(`form-item-${index}`);

		let header = document.createElement("div");
		header.classList.add("menu-item-bar");

		header.innerHTML = `<div class="menu-item-handle ui-sortable-handle">
            <label class="item-title" for="menu-item-checkbox-141">
                <span class="menu-item-title">${"No Label"}</span>
            </label>
            <span class="item-controls">
                <span class="item-type">${field.label}</span>
                <span class="item-order">
                    <a href="#" class="item-move-up" aria-label="Move up">↑</a> |
                    <a href="#" class="item-move-down" aria-label="Move down">↓</a> 
                </span>
                <a class="item-edit" id="edit-141" href="#"><span class="screen-reader-text">Edit</span></a> 
            </span>
        </div>`;

		li.appendChild(header);

		let settings = document.createElement("div");
		settings.classList.add("menu-item-settings", "wp-clearfix");
		settings.style.display = "none";

		settings.innerHTML = `<div class="basic-settings">${Template.renderSection(properties.basic)}</div>
        <div class="advanced-settings">${Template.renderSection(properties.advanced)}</div>
        <hr/>
        <div class="menu-item-actions description-wide submitbox">
            <a class="item-delete submitdelete deletion" href="#">Remove</a>
        </div>`;

		li.appendChild(settings);

		header.querySelector(".item-edit").addEventListener("click", (e) => {
            e.preventDefault();
			settings.style.display = settings.style.display === "none" ? "block" : "none";
		});

		return li;
	}
	static renderSection(section) {
        if(!section.fields || section.fields.length === 0) return "";
		let fieldMap = Object.keys(section.fields).map((key) => {
			let _field = section.fields[key];
			return `<p class="description description-wide">
                <label for="edit-menu-item-title-141">
                    ${_field.label}
                    <br>
                    <input type="${_field.type}" id="edit-menu-item-title-141" class="widefat edit-menu-item-title" name="${_field.name}" value="">
                    <small>${_field.help}</small>
                </label>
            </p>`;
		});
		return `
            <h3>${section.label}</h3>
            ${fieldMap.join("")}
            `;
	}
}

class DraggableModel extends Backbone.Model {
	view: Backbone.View;
	type: string;
}

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
		let destElm = droppableView.$el;
		console.log("DID DROP", type, srcElm, destElm);

		droppableView.add(type, {});
		destElm.append(droppableView.render().el);
		// let field = new Field(properties);
	}
}

class DroppableView extends Backbone.View<DraggableModel> {
	collection: DroppableCollection;
	fields: Array<any> = [];
	droppable(options: any = {}) {
		this.$el.data("view", this);
		this.$el.data("collection", this.collection);

		this.collection.view = this; // so collection changes can update the views
		this.fields = [];
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

		this.$el.append(fieldView.render().el);
	}
}

class FieldView extends Backbone.View<DraggableModel> {
	template: (data: FieldProperties) => HTMLElement;
	data: any;
	type: string;
	index: number;
	constructor(index, type, data) {
		super();

		this.template = (data) => {
			let type = data.type;
			let field = ajaxyFormsBuilder.fields[type];
			return Template.renderField(this.index, field);
		};
		//_.template($("#templ").html());
		this.type = type;
		this.data = data;
		this.index = index;
	}
	render() {
		this.$el.html(this.template(new FieldProperties(this.type, this.data)));
		return this;
	}
}

class FieldProperties extends Backbone.Collection<FieldProperty> {
	view: Backbone.View;
	type: string;
	data: any;
	constructor(type: string, data: any) {
		super(data);

		this.type = type;
		this.data = data;
	}
}
class FieldProperty extends Backbone.Model {
	initialize() {}
}
jQuery(() => {
	const droppable: HTMLDivElement = document.querySelector(".af-form-wrap .droppable");
	const dropCollection = new DroppableCollection();

	const dropView = new DroppableView({
		collection: dropCollection,
		el: jQuery(droppable),
	});
	dropView.droppable();

	// create a few draggable views
	const dragModels: DraggableModel[] = [];
	const dragViews: DraggableView[] = [];

	const draggables = document.querySelectorAll(".af-fields > li.draggable");
	[].forEach.call(draggables, (draggable: HTMLElement, i: number) => {
		const dragModel = new DraggableModel({
			type: draggable.getAttribute("data-type"),
		});
		dragModels.push(dragModel);
		dropCollection.add(dragModel);

		const dragView = new DraggableView({
			model: dragModel,
			el: jQuery(draggable),
		});
		dragView.draggable({
			makeClone: true,
			canDropClass: "can-drop",
		});

		dragView.on("drag", () => {
			console.log("example: DRAG");
		});

		dragViews.push(dragView);
	});

	(window as any).dropCollection = dropCollection;
});

/// <reference path='./node_modules/@types/backbone/index.d.ts' />

import DraggableModel from "./classes/DraggableModel";
import DroppableCollection from "./classes/DroppableCollection";
import DroppableView from "./classes/DroppableView";

declare let form_metadata: any;

jQuery(() => {
	const droppable: HTMLDivElement = document.querySelector(".af-form-wrap .droppable");
	if (!droppable) {
		return;
	}
	const dropCollection = new DroppableCollection();

	const dropView = new DroppableView({
		collection: dropCollection,
		el: jQuery(droppable),
		onDrop: function (event, ui) {
			event.preventDefault();
			this.add(ui.item.data("type"), {}, ui.helper.index());
			return true;
		},
	});
	dropView.droppable();

	const dragModels: DraggableModel[] = [];

	//@ts-ignore
	jQuery(".af-fields li.draggable").draggable({
		containment: ".af-form-wrap",
		helper: "clone",
		revert: "invalid",
		connectToSortable: ".ui-sortable",
		start: function (event, ui) {
			ui.helper.height(ui.helper.prevObject.height());
			ui.helper.width(ui.helper.prevObject.width());
		},
		stop: (event, ui) => {
			ui.helper.remove();
		},
	});

	const draggables = document.querySelectorAll(".af-fields li.draggable");
	[].forEach.call(draggables, (draggable: HTMLElement, i: number) => {
		const dragModel = new DraggableModel({
			type: draggable.getAttribute("data-type"),
		});
		dragModels.push(dragModel);
		dropCollection.add(dragModel);
	});

	const toggleMore = document.querySelector(".af-fields li.more");
	toggleMore?.addEventListener("click", (e) => {
		e.preventDefault();
		const more = document.querySelector(".af-fields .af-all-fields");
		more.classList.toggle("active");

		if (more.classList.contains("active")) {
			setTimeout(() => (toggleMore.querySelector("span").innerHTML = "Load Less"), 500);
		} else {
			setTimeout(() => (toggleMore.querySelector("span").innerHTML = "Load More"), 500);
		}
	});

    if (form_metadata) {
		if (form_metadata.fields) {
			form_metadata.fields.forEach((field, index) => {
				dropView.add(field.type, field, index);
			});
		}
	}

	(window as any).dropCollection = dropCollection;
});

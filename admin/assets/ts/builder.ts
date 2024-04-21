/// <reference path='./node_modules/@types/backbone/index.d.ts' />

import DraggableModel from "./classes/DraggableModel";
import DraggableView from "./classes/DraggableView";
import DroppableCollection from "./classes/DroppableCollection";
import DroppableView from "./classes/DroppableView";

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

	const draggables = document.querySelectorAll(".af-fields li.draggable");
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
			dropClass: "af-drop",
		});

		// dragView.on("drag", () => {
		// 	console.log("example: DRAG");
		// });

		dragViews.push(dragView);
	});

	const toggleMore = document.querySelector(".af-fields li.more");
	toggleMore.addEventListener("click", (e) => {
		e.preventDefault();
		const more = document.querySelector(".af-fields .af-all-fields");
		more.classList.toggle("active");

		if (more.classList.contains("active")) {
			setTimeout(() => (toggleMore.querySelector("span").innerHTML = "Load Less"), 500);
		} else {
			setTimeout(() => (toggleMore.querySelector("span").innerHTML = "Load More"), 500);
		}
	});

	(window as any).dropCollection = dropCollection;
});

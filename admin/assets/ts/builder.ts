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
		const more = document.querySelectorAll('.af-fields li[data-group="advanced"]');
		[].forEach.call(more, (moreItem) => {
			moreItem.style.display = moreItem.style.display === "none" ? "" : "none";
		});
	});

	if (typeof form_metadata != undefined && form_metadata) {
		if (form_metadata.fields) {
			form_metadata.fields.forEach((field, index) => {
				dropView.add(field.type, field, index);
			});
		}
	}

	(window as any).dropCollection = dropCollection;

	let searchList = document.querySelectorAll(".af-fields li");
	let searchInput: HTMLInputElement = document.querySelector("input.af-search");
	searchInput.addEventListener("input", function () {
		let text = searchInput.value.toString().toLowerCase();
		[].forEach.call(searchList, (li) => {
			let keywords = li.getAttribute("data-keywords");
			if (!text || text.trim() == "") {
				li.style.display = "";
				return;
			}
			if (!keywords) {
				li.style.display = "none";
				return;
			}

			let keywordArray = keywords.toLowerCase().split(",");
			let found = keywordArray.some((keyword) => {
				if (keyword.toLowerCase().indexOf(text.toLowerCase()) > -1) {
					return true;
				}
				return false;
			});

			li.style.display = found ? "" : "none";
		});
	});
});

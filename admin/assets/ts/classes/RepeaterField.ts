let _uniqueId = 0;

function uniqueId() {
	return _uniqueId++;
}

class RepeaterField extends Backbone.View<any> {
	field: any;
	data: Array<any> = [];
	basename: any;
	constructor(basename:string, field, data) {
		super();

		this.data = data;
		this.field = field;
		this.basename = basename;
	}

	createInnerField(_field, index) {
		let el = jQuery("<div></div>").addClass(["af-field", `af-field-${_field.type}`]);
		let inputDiv = jQuery("<div></div>").addClass("af-field-input");

		let input = jQuery("<input></input>")
			.addClass("widefat")
			.attr("type", _field.type || "text")
			.attr("name", _field.name ? `${this.basename}[${this.field.name}][${uniqueId()}][${_field.name}]` : `${this.basename}[${this.field.name}][${uniqueId()}]`)
			.val(this.data && this.data[index] ? this.data[index][_field.name] || "" : "");

		if (_field.label) {
			input.attr("placeholder", _field.label);
		}
		inputDiv.append(input);
		if (_field.help) {
			let small = jQuery("<small></small>").html(_field.help);
			inputDiv.append(small);
		}

		el.append(inputDiv);
		return el;
	}

	createRemoveButton(index) {
		let lnk = jQuery("<a></a>").addClass("af-repeater-remove").html('<span class="dashicons dashicons-remove"></span>').attr("href", "#");
		lnk.on("click", (e) => {
			e.preventDefault();
			this.removeRepeater(index);
		});

		return lnk;
	}
	createAddButton() {
		let lnk = jQuery("<a></a>").addClass("af-repeater-add").html('<span class="dashicons dashicons-insert"></span>').attr("href", "#");
		lnk.on("click", (e) => {
			e.preventDefault();
			this.data.push({});
			this.addRepeater(this.data.length - 1);
		});

		return lnk;
	}

	removeRepeater(index) {
		this.data.splice(index, 1);
		this.$el.children(".af-repeater-inner").eq(index).remove();
	}

	addRepeater(index) {
		let repeaterDiv = jQuery("<div></div>").addClass("af-repeater-inner");
		this.field.fields.map((_field) => {
			let inner = this.createInnerField(_field, index);
			repeaterDiv.append(inner);
		});
		repeaterDiv.append(this.createRemoveButton(index));
		this.$el.append(repeaterDiv);
	}

	render(): this {
		if (this.field.label) {
			let heading = jQuery("<h4></h4>").html(this.field.label);
			this.$el.append(heading);
		}
		if (this.field.help) {
			let small = jQuery("<small></small>").html(this.field.help);
			this.$el.append(small);
		}

		if (this.data) {
			this.data.map((item, index) => {
				this.addRepeater(index);
			});
		}

		this.$el.addClass(["af-repeater"]);
		this.$el.append(this.createAddButton());
		return this;
	}
}

export default RepeaterField;

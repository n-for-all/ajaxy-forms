import Select from "./Select";

/**
 * Checkboxes editor
 *
 * Renders a <ul> with given options represented as <li> objects containing checkboxes
 *
 * Requires an 'options' value on the schema.
 *  Can be an array of options, a function that calls back with the array of options, a string of HTML
 *  or a Backbone collection. If a collection, the models must implement a toString() method
 */
class Checkboxes extends Select {
	tagName = "ul";

	groupNumber = 0;

	events = <any>{
		"click input[type=checkbox]": function () {
			this.trigger("change", this);
		},
		"focus input[type=checkbox]": function () {
			if (this.hasFocus) return;
			this.trigger("focus", this);
		},
		"blur input[type=checkbox]": function () {
			if (!this.hasFocus) return;

			_.defer(function (self) {
				if (self.$("input[type=checkbox]:focus")[0]) return;
				self.trigger("blur", self);
			}, this);
		},
	};

	getValue() {
		var values = [];

		this.$("input[type=checkbox]:checked").each(function () {
			values.push((this as HTMLInputElement).value);
		});
		return values;
	}

	setValue(values) {
		if (!_.isArray(values)) values = [values];
		this.value = values;
		this.$("input[type=checkbox]").val(values);
	}

	focus() {
		if (this.hasFocus) return;

		this.$("input[type=checkbox]").first().focus();
	}

	blur() {
		if (!this.hasFocus) return;

		this.$("input[type=checkbox]:focus").blur();
	}

	/**
	 * Create the checkbox list HTML
	 * @param {Array}   Options as a simple array e.g. ['option1', 'option2']
	 *                      or as an array of objects e.g. [{val: 543, label: 'Title for object 543'}]
	 * @return {String} HTML
	 */
	_arrayToHtml(array) {
		var $ = Backbone.$;
		var html = $();

		_.each(
			array,
			_.bind(function (option, index) {
				var itemHtml = $("<li>");
				if (_.isObject(option)) {
					if (option.group) {
						var originalId = this.id;
						this.id += "-" + this.groupNumber++;
						itemHtml = $('<fieldset class="group">').append($("<legend>").text(option.group));
						itemHtml = itemHtml.append(this._arrayToHtml(option.options));
						this.id = originalId;
					} else {
						var val = option.val || option.val === 0 ? option.val : "";
						itemHtml.append($('<input type="checkbox" name="' + this.getName() + '" id="' + this.id + "-" + index + '" />').val(val));
						if (option.labelHTML) {
							itemHtml.append($('<label for="' + this.id + "-" + index + '" />').html(option.labelHTML));
						} else {
							itemHtml.append($('<label for="' + this.id + "-" + index + '" />').text(option.label));
						}
					}
				} else {
					itemHtml.append($('<input type="checkbox" name="' + this.getName() + '" id="' + this.id + "-" + index + '" />').val(option));
					itemHtml.append($('<label for="' + this.id + "-" + index + '" />').text(option));
				}
				html = html.add(itemHtml);
			}, this)
		);

		return html;
	}
}

export default Checkboxes;
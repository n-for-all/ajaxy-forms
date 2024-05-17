/**
 * DateTime editor
 *
 * @param {Editor} [options.DateEditor]           Date editor view to use (not definition)
 * @param {Number} [options.schema.minsInterval]  Interval between minutes. Default: 15
 */

import Editor from "../Editor";
import DateEditor from "./Date";
import Settings from "../Settings";

class DateTime extends Editor {
	template = _.template(
		`
    <div class="bbf-datetime">
      <div class="bbf-date-container" data-date></div>
      <select data-type="hour"><%= hours %></select>
      :
      <select data-type="min"><%= mins %></select>
    </div>`,
		null,
		Settings.templateSettings
	);

	//The date editor to use (constructor function, not instance)
	DateEditor: DateEditor = null;
	options: any;
	dateEditor: any;
	$hidden: any;
	$hour: JQuery<HTMLElement>;
	$min: JQuery<HTMLElement>;

	constructor(options) {
		options = options || {};

		super(
			_.extend(
				{
					events: {
						"change select": () => {
							this.updateHidden();
							this.trigger("change", this);
						},
						"focus select": () => {
							if (this.hasFocus) return;
							this.trigger("focus", this);
						},
						"blur select": () => {
							if (!this.hasFocus) return;
							setTimeout(() => {
								if (this.$("select:focus")[0]) return;
								this.trigger("blur", self);
							}, 0);
						},
					},
				},
				options
			)
		);

		//Option defaults
		this.options = _.extend(
			{
				DateEditor: new DateEditor(options),
			},
			options
		);

		//Schema defaults
		this.schema = _.extend(
			{
				minsInterval: 15,
			},
			options.schema || {}
		);

		//Create embedded date editor
		this.dateEditor = new DateEditor(options);

		this.value = this.dateEditor.value;

		//Template
		this.template = options.template;
	}

	render() {
		function pad(n) {
			return n < 10 ? "0" + n : n;
		}

		var schema = this.schema,
			$ = Backbone.$;

		//Create options
		var hoursOptions = _.map(_.range(0, 24), function (hour) {
			return '<option value="' + hour + '">' + pad(hour) + "</option>";
		});

		var minsOptions = _.map(_.range(0, 60, schema.minsInterval), function (min) {
			return '<option value="' + min + '">' + pad(min) + "</option>";
		});

		//Render time selects
		var $el = $(
			$.trim(
				this.template({
					hours: hoursOptions.join(),
					mins: minsOptions.join(),
				})
			)
		);

		//Include the date editor
		$el.find("[data-date]").append(this.dateEditor.render().el);

		//Store references to selects
		this.$hour = $el.find('select[data-type="hour"]');
		this.$min = $el.find('select[data-type="min"]');

		//Get the hidden date field to store values in case POSTed to server
		this.$hidden = $el.find('input[type="hidden"]');

		//Set time
		this.setValue(this.value);

		this.setElement($el);
		this.$el.attr("id", this.id);
		this.$el.attr("name", this.getName());

		if (this.hasFocus) this.trigger("blur", this);

		return this;
	}

	/**
	 * @return {Date}   Selected datetime
	 */
	getValue() {
		var date = this.dateEditor.getValue();

		var hour = this.$hour.val(),
			min = this.$min.val();

		if (!date || !hour || !min) return null;

		date.setHours(hour);
		date.setMinutes(min);

		return date;
	}

	/**
	 * @param {Date}
	 */
	setValue(date) {
		if (!_.isDate(date)) date = new Date(date);
		this.value = date;
		this.dateEditor.setValue(date);

		this.$hour.val(date.getHours());
		this.$min.val(date.getMinutes());

		this.updateHidden();
	}

	focus() {
		if (this.hasFocus) return;

		this.$("select").first().focus();
	}

	blur() {
		if (!this.hasFocus) return;

		this.$("select:focus").blur();
	}

	/**
	 * Update the hidden input which is maintained for when submitting a form
	 * via a normal browser POST
	 */
	updateHidden() {
		var val = this.getValue();
		if (_.isDate(val)) val = val.toISOString();

		this.$hidden.val(val);
	}

	/**
	 * Remove the Date editor before removing self
	 */
	remove() {
		this.dateEditor.remove();

		this.remove.call(this);
		return this;
	}
}

export default DateTime;

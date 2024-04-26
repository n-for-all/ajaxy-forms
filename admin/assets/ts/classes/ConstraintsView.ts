import SettingsSectionFields from "./SettingsSectionFields";

declare let ajaxyFormsBuilder: {
	constraints: { [x: string]: any };
};

class ConstraintsView extends Backbone.View<any> {
	index = 0;
	basename = "";
	data: any;
	container: JQuery<HTMLElement>;
	constructor(basename, data) {
		super();

		this.data = data || {};
		this.basename = basename;
	}

	createSelect(constraintValue, index) {
		let container =
			jQuery(`<div class='expand-settings'><h3><span class="af-text">Constraint</span><a href="#" class="item-toggle"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 12H12M12 12H18M12 12V18M12 12V6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg></a></h3></div>`);
		let toggleContainer = jQuery('<div class="section-toggle"></div>');
		let fieldsContainer = jQuery('<div class="section-inner"></div>');
		let field = jQuery("<div class='af-field af-field-select'></div>");
		let $select = jQuery("<select></select>");
		$select.attr("name", `${this.basename}[constraints][${index}][type]`);
		$select.append(jQuery("<option></option>").val("").text("---"));
		Object.keys(ajaxyFormsBuilder.constraints).forEach((key) => {
			$select.append(jQuery("<option></option>").val(key).text(ajaxyFormsBuilder.constraints[key].label));
		});

        
		container.find(".item-toggle").on("click", (e) => {
			e.preventDefault();
			container.toggleClass("active");
		});

		let help = jQuery("<small></small>").addClass("help-block");

		let fields = jQuery("<div></div>");

		field.append($select);
		fieldsContainer.append(field);
		fieldsContainer.append(fields);

		fieldsContainer.append(help);

		let remove = jQuery("<a href='#' class='af-remove'>Remove</a>");
		remove.on("click", (e) => {
			e.preventDefault();
			container.remove();
		});

		fieldsContainer.append(remove);

		toggleContainer.append(fieldsContainer);
		container.append(toggleContainer);

		$select.on("change", (e) => {
			let value: string = $select.val().toString();
			let constraint = ajaxyFormsBuilder.constraints[value];
			fields.html("");
			fields.append(new SettingsSectionFields(`${this.basename}[constraints][${index}]`, constraint.fields, constraintValue).render().el);
			if (value && ajaxyFormsBuilder.constraints[value] && ajaxyFormsBuilder.constraints[value].help) {
				help.html(ajaxyFormsBuilder.constraints[value].help);
				if (ajaxyFormsBuilder.constraints[value].docs) {
					help.append(
						' | <a target="_blank" href="' + ajaxyFormsBuilder.constraints[value].docs + '" target="_blank">View Documentation</a>'
					);
				}

				container.find(".af-text").text(ajaxyFormsBuilder.constraints[value].label);
			} else {
				container.find(".af-text").text("Constraint");
				help.html("");
			}
		});

        $select.val(constraintValue.type || "");

        if(constraintValue.type){
            $select.trigger("change");
        }
		return container;
	}

	renderSettings() {
		this.container.empty();
		if (this.data) {
			this.data.forEach((constraint, index) => {
				this.container.append(this.createSelect(constraint, index));
			});
		}
	}

	render() {
		this.$el.addClass("constraints-settings");
		this.container = jQuery("<div></div>").addClass("constraints-container");
		let addMore = jQuery("<a href='#' class='af-add-more'>Add Constraint</a>");
		addMore.on("click", (e) => {
			e.preventDefault();
            this.data.push({ type: "" });
            this.renderSettings();
		});

        this.renderSettings();
		this.$el.append(this.container);
		this.$el.append(addMore);
		return this;
	}
}

export default ConstraintsView;

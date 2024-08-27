import { Form } from "./form";

class AjaxyRepeater {
	settings: any;
	id: string;
	template: string | undefined;
	index = 0;
	element: any;
	items: any;
	btnAdd: HTMLButtonElement | undefined;
	btnRemove: Array<HTMLButtonElement> = [];
    form: Form;
	constructor(element, form) {
		try {
			console.log(element.getAttribute("data-settings"));
			this.settings = JSON.parse(element.getAttribute("data-settings"));
		} catch (e) {
			console.error("Invalid settings", element);
			return;
		}

		if (!this.settings) {
			return;
		}
        this.form = form;
		this.element = element;
		this.id = element.getAttribute("id");
		this.template = this.unescapeHTML(document.querySelector(`#template-${this.id}`)?.innerHTML);
		this.items = element.querySelector(".repeater-items");

		if (this.settings.allowAdd) {
			this.btnAdd = element.querySelector('[data-action="add"]');
			this.btnAdd?.addEventListener("click", () => {
				this.add();
				this.index++;
			});
		}

		if (this.settings.min > 0) {
			for (let i = 0; i < this.settings.min; i++) {
				this.add();
				this.index++;
			}
		}

        this.form.element.addEventListener("reset", () => {
            this.items.innerHTML = "";
            this.index = 0;
            this.btnRemove.map((btn) => {
                btn.disabled = true;
                btn.style.display = "none";
            });
        });
	}

	isMax() {
		return this.items?.childElementCount >= this.settings.max && this.settings.max > 0;
	}
	unescapeHTML(string) {
		var elt = document.createElement("span");
		elt.innerHTML = string;
		var txt = elt.innerText;
        elt.remove();
        console.log(txt);
        return txt;
	}
	add() {
		if (this.isMax()) {
			return;
		}

		this.btnRemove.map((btn) => {
			btn.disabled = false;
			btn.style.display = "";
		});

		let newItem = document.createElement("div");
		newItem.classList.add("repeater-item");
		newItem.innerHTML = this.template?.replace(/--index/g, `--${this.index}`) as string;
		this.items.appendChild(newItem);
		if (this.settings.allowDelete) {
			let btnRemove: HTMLButtonElement | null = newItem.querySelector('[data-action="remove"]');
			if (btnRemove) {
				if (this.settings.min > 0 && this.items.childElementCount <= this.settings.min) {
					btnRemove.disabled = true;
					btnRemove.style.display = "none";
				}
				btnRemove.addEventListener("click", (e) => {
					e.preventDefault();
					if (this.btnAdd) this.btnAdd.disabled = false;

					if ((this.settings.min > 0 && this.items.childElementCount > this.settings.min) || this.settings.min <= 0) {
						newItem.remove();
						if (this.settings.min <= 0) return;

						if (this.items.childElementCount <= this.settings.min) {
							this.btnRemove.map((btn) => {
								btn.disabled = true;
								btn.style.display = "none";
							});
						}
					}
				});

				this.btnRemove.push(btnRemove);
			}
		}
		if (this.isMax() && this.btnAdd) {
			this.btnAdd.disabled = true;
		}

        this.form.trigger("item-added", [newItem]);
	}
}

export default AjaxyRepeater;

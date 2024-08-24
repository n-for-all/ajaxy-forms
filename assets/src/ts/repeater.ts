class AjaxyRepeater {
	settings: any;
	id: string;
	template: string | undefined;
	index = 0;
	element: any;
	items: any;
    btnAdd: HTMLButtonElement | undefined;
	constructor(element) {
		try {
            console.log(element.getAttribute("data-settings"));
			this.settings = JSON.parse(element.getAttribute("data-settings"));
		} catch (e) {
			console.error("Invalid settings", element);
			return;
		}

        if(!this.settings){
            return;
        }

		this.element = element;
		this.id = element.getAttribute("id");
		this.template = document.querySelector(`#template-${this.id}`)?.innerHTML;
		this.items = element.querySelector(".repeater-items");

		if (this.settings.allowAdd) {
			this.btnAdd = element.querySelector('[data-action="add"]');
			this.btnAdd?.addEventListener("click", () => {
				this.add();
				this.index++;
			});
		}

        if(this.settings.min > 0){
            for(let i = 0; i < this.settings.min; i++){
                this.add();
                this.index++;
            }
        }
	}

    isMax(){
        return this.items?.childElementCount >= this.settings.max && this.settings.max > 0;
    }

	add() {
		if (this.isMax()) {
			return;
		}
		let newItem = document.createElement("div");
		newItem.classList.add("repeater-item");
		newItem.innerHTML = this.template?.replace(/--index/g, `--${this.index}`) as string;
		this.items.appendChild(newItem);
		if (this.settings.allowDelete) {
			let btnRemove = newItem.querySelector('[data-action="remove"]');
			btnRemove?.addEventListener("click", (e) => {
				e.preventDefault();
                if(this.btnAdd) this.btnAdd.disabled = false;
				newItem.remove();
			});
		}
		if (this.isMax()&&this.btnAdd) {
			this.btnAdd.disabled = true;
		}
	}
}

export default AjaxyRepeater;

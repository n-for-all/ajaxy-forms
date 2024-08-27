import { Form } from "./form";

declare let ajaxyFormsSettings;

class AjaxyTermPostsManager {
	termsEl: HTMLSelectElement | null;
	postsEl: HTMLSelectElement | null;
	loadingMessage: any;
	noPostsMessage: any;
	defaultMessage: any;
	dataSettings: any;
	form: Form;

	constructor(form, container) {
		let dataSettings = JSON.parse(container.getAttribute("data-term-posts"));
		let messages = JSON.parse(container.getAttribute("data-messages"));

		this.form = form;
		this.termsEl = container.querySelector(`#${container.id}_terms`);

		this.postsEl = container.querySelector(`#${container.id}_posts`);
		if (!this.termsEl || !this.postsEl) {
			return;
		}

		this.dataSettings = dataSettings;
		this.loadingMessage = messages["loading"];
		this.noPostsMessage = messages["not_found"];
		this.defaultMessage = messages["default_option"];
		this.loadEvents();

		this.form.element.addEventListener("reset", () => {
			this.postsEl!.innerHTML = '<option value="">' + this.defaultMessage + "</option>";
		});
	}

	loadEvents() {
		this.termsEl!.addEventListener("change", () => {
			this.loadPosts(this.termsEl!.value);
		});

		if (this.termsEl!.value != "") {
			this.termsEl!.dispatchEvent(new Event("change"));
		}
	}

	loadPosts(termId) {
		if (!termId) {
			this.postsEl!.innerHTML = '<option value="">' + this.defaultMessage + "</option>";
			return;
		}

		this.postsEl!.innerHTML = '<option value="">' + this.loadingMessage + "</option>";
		this.postsEl!.style.transition = "opacity 0.3s ease";
		this.postsEl!.style.opacity = "0.5";
		fetch(ajaxyFormsSettings.dataUrl, {
			method: "POST",
			headers: {
				"Content-Type": "application/json",
			},
			body: JSON.stringify({
				type: "posts_by_term",
				term_id: termId,
				...this.dataSettings,
			}),
		})
			.then((response) => response.json())
			.then((response) => {
				this.postsEl!.style.opacity = "1";

				if (response.status != "success") {
					console.error(response.message);
					return;
				}

				this.postsEl!.innerHTML = "";
				if (response.data.length == 0) {
					this.postsEl!.innerHTML = '<option value="">' + this.noPostsMessage + "</option>";
					return;
				}
				response.data.forEach((post) => {
					this.postsEl!.innerHTML += `<option value="${post.id}">${post.title}</option>`;
				});
			});
	}
}

export default AjaxyTermPostsManager;

declare let ajaxyFormsSettings;

class AjaxyTermPostsManager {
	termsEl: HTMLSelectElement;
	postsEl: HTMLSelectElement;
	loadingMessage: any;
	noPostsMessage: any;
	defaultMessage: any;
    dataSettings: any;

	constructor(termSelector, postsSelector, messages, dataSettings) {
		this.termsEl = document.querySelector(termSelector);

		this.postsEl = document.querySelector(postsSelector);
		if (!this.termsEl || !this.postsEl) {
			return;
		}

		this.dataSettings = dataSettings;
		this.loadingMessage = messages['loading'];
		this.noPostsMessage = messages['not_found'];
		this.defaultMessage = messages['default_option'];
		this.loadEvents();
	}

	loadEvents() {
		this.termsEl.addEventListener("change", () => {
			this.loadPosts(this.termsEl.value);
		});

		if (this.termsEl.value != "") {
			this.termsEl.dispatchEvent(new Event("change"));
		}
	}

	loadPosts(termId) {
		if (!termId) {
            this.postsEl.innerHTML = '<option value="">' + this.defaultMessage + "</option>";
			return;
		}

		this.postsEl.innerHTML = '<option value="">' + this.loadingMessage + "</option>";
		this.postsEl.style.transition = "opacity 0.3s ease";
		this.postsEl.style.opacity = "0.5";
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
				this.postsEl.style.opacity = "1";

				if (response.status != "success") {
					console.error(response.message);
					return;
				}

				this.postsEl.innerHTML = "";
				if (response.data.length == 0) {
					this.postsEl.innerHTML = '<option value="">' + this.noPostsMessage + "</option>";
					return;
				}
				response.data.forEach((post) => {
					this.postsEl.innerHTML += `<option value="${post.id}">${post.title}</option>`;
				});
			});
	}
}

export default AjaxyTermPostsManager;

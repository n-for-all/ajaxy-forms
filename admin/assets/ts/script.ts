import "whatwg-fetch";

declare let ajaxurl: any, wp: any;

class AjaxyWooGithub {
	tippy: any;
    WooMediaFrame;
	constructor() {
		this.ready(() => {
            this.handleWpMedia();
			this.on("change", "ajaxy-github-repository-select", (el) => {
				let target = el.target || el.srcElement;
				let value = target.options[target.selectedIndex].value;

				let query = this.encodeQueryString({ action: "repo_releases_action", repo: value, _: new Date().getTime() });
				let row = this.closest(target, "tr");
				let nextElement = row.querySelector("select.ajaxy-github-release-select");
				nextElement.disabled = true;
				fetch(`${ajaxurl}${query}`)
					.then((res) => res.json())
					.then((json) => {
						nextElement.disabled = false;
						if (json.status == "success") {
							if (json.releases) {
								nextElement.innerHTML = "";
								if (json.releases.length) {
									json.releases.map((item) => {
										let option = document.createElement("option");
										option.innerHTML = item.label;
										option.value = item.value;
										nextElement.appendChild(option);
									});
								}
								nextElement.disabled = false;
							}
						} else {
							alert(json.message);
						}
					})
					.catch((err) => {
						nextElement.disabled = false;
						alert(err.message);
						console.error(err);
					});

				console.log(el);
			});
		});
	}

	closest(el, tag) {
		tag = tag.toUpperCase();
		do {
			if (el.nodeName === tag) {
				return el;
			}
		} while ((el = el.parentNode));

		return null;
	}

	ready(fn) {
		if (document.readyState != "loading") {
			fn();
		} else {
			document.addEventListener("DOMContentLoaded", fn);
		}
	}

	on(eventType, className, cb) {
		document.addEventListener(
			eventType,
			function (event) {
				var el = event.target,
					found;

				while (el && !(found = el.id === className || el.classList.contains(className.replace(".", "")))) {
					el = el.parentElement;
				}

				if (found) {
					cb.call(el, event);
				}
			},
			false
		);
	}

	encodeQueryString(params) {
		const keys = Object.keys(params);
		return keys.length ? "?" + keys.map((key) => encodeURIComponent(key) + "=" + encodeURIComponent(params[key])).join("&") : "";
	}

	handleWpMedia() {

	}
}

window["AjaxyWooGithub"] = new AjaxyWooGithub();

import "whatwg-fetch";
import { Form } from "./form";
import AjaxyTermPostsManager from "./term_posts";
import AjaxyRepeater from "./repeater";

class AjaxyForms {
	forms: { [x: string]: Form } = {};
	repeaters: { [x: string]: AjaxyRepeater } = {};
	term_posts: { [x: string]: AjaxyTermPostsManager } = {};
	constructor() {
		this.ready(() => {
			let forms = document.querySelectorAll("form.ajaxy-form.is-ajax");
			if (forms.length > 0) {
				[].forEach.call(forms, (form) => {
					this.forms[form.name] = new Form(form);
                    this.forms[form.name].addListener("item-added", ([elm]) => {
						let nTermPosts = elm.querySelectorAll("[data-term-posts]");
						if (nTermPosts.length) {
							nTermPosts.forEach((nTermPost) => {
								this.term_posts[nTermPost.id] = new AjaxyTermPostsManager(this.forms[form.name], nTermPost);
							});
						}
					});

					let term_posts = form.querySelectorAll(`[data-term-posts]`);
					if (term_posts.length > 0) {
						[].forEach.call(term_posts, (term_post) => {
							this.term_posts[term_post.id] = new AjaxyTermPostsManager(this.forms[form.name], term_post);
						});
					}

                    let repeaters = form.querySelectorAll(`.repeater`);
					if (repeaters.length > 0) {
						[].forEach.call(repeaters, (repeater) => {
							this.repeaters[repeater.id] = new AjaxyRepeater(repeater, this.forms[form.name]);
						});
					}
				});
			}
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
}

window["AjaxyTermPostsManager"] = AjaxyTermPostsManager;
window["AjaxyForms"] = new AjaxyForms();

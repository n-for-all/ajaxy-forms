import "whatwg-fetch";
import { Form } from "./form";

class AjaxyForms {
	forms: { [x: string]: Form } = {};
	constructor() {
		this.ready(() => {
			let forms = document.querySelectorAll("form.ajaxy-form.is-ajax");
			if (forms.length > 0) {
				[].forEach.call(forms, (form) => {
					this.forms[form.name] = new Form(form);
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

window["AjaxyForms"] = new AjaxyForms();

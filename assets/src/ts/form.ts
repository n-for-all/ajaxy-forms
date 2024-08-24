declare let ajaxyFormsSettings;

class Form {
	element: HTMLFormElement;
	submitButton: HTMLButtonElement | null;
	listeners: { [T: string]: any } = {};
	_validate: ((element: HTMLFormElement) => boolean) | undefined;
	_authenticated = false;
	errorElms: NodeListOf<Element>;
	messageElm: any;

	constructor(element: HTMLFormElement, submitButton?: HTMLButtonElement | null, validate?: (element: HTMLFormElement) => boolean, _normalSubmit?: boolean) {
		if (!element || element.tagName != "FORM") {
			console.warn("The element you passed to the form is invalid", element);
			return;
		}
		this.element = element;
		this.errorElms = this.element.querySelectorAll(".field-error");
		this.messageElm = this.element.querySelector(".form-message");
		this._validate = validate;
		this.submitButton = submitButton ? submitButton : this.element.querySelector('[type="submit"]');
		if (!this.submitButton) {
			console.warn("The form have no submit button", element);
			return;
		}
		this.element.addEventListener("submit", (e) => {
			e.preventDefault();
            this.element.dispatchEvent && this.element.dispatchEvent(new CustomEvent("beforesubmit", {
                bubbles: true,
                cancelable: true,
                detail: {
                    form: this.element,
                    submitButton: this.submitButton,
                },
            }));
			this.element.classList.remove("invalid");
			if (this.submitButton && this.submitButton.classList.contains("loading")) return;
			else this.submitButton?.classList.add("loading");
			if (!this._validate || this._validate(this.element)) {
				if (this.listeners["submit"]) {
					return this.trigger("submit", [
						this.element,
						() => {
							this.submitButton?.classList.remove("loading");
						},
					]);
				}
				const data = new FormData(this.element);
				const headers = this.getHeaders();

				// data.append("action", "af_submit");
				data.append("form_name", this.element.name);

				let method = (this.element.method ? this.element.method : "POST").toUpperCase();

				let fetchData = {
					headers: headers,
					method: method,
				};
				let action = this.element.action;

				if (method == "GET" || method == "HEAD") {
					//@ts-ignore
					action = action + "?" + new URLSearchParams(data).toString();
				} else {
					fetchData["body"] = data;
				}

				fetch(action, fetchData)
					.then((response: any) => {
						response
							.json()
							.then((json) => {
								this.clearErrors();
								this.trigger("response", json);
								this.submitButton?.classList.remove("loading");
								if (json) {
									if (json.status == "error") {
										if (json.fields) {
                                            this.errorElms = this.element.querySelectorAll(".field-error");
											let fields = json.fields;
											let names = Object.keys(fields);
											for (let i = 0; i < names.length; i++) {
												this.addErrors(names[i], fields[names[i]]);
											}
										}
									} else {
										if (json.status == "success") {
											this.element.reset();
											this.trigger("success", json);
										}
									}
									if (json.message) {
										this.setMessage(json.message, json.status);
									}
									if (json._token) {
										let token: HTMLInputElement | null = this.element.querySelector('[name*="[_token]"]');
										if (token) {
											token.value = json._token;
										}
									}
								}
							})
							.catch((error) => {
								this.clearErrors();
								this.trigger("error", error);
								this.submitButton?.classList.remove("loading");
								this.setMessage(error.message, "error");
							});
					})
					.catch((e) => {
						this.trigger("error", e);
						this.submitButton?.classList.remove("loading");
						console.error(e);
						this.setMessage(e.message, "error");
					});
			} else {
				this.submitButton?.classList.remove("loading");
				this.element.classList.add("invalid");
			}
		});

		this.element.setAttribute("rendered", "1");
	}

	public addListener = (name: string, callback: (response: any) => void) => {
		this.listeners[name] = callback;
	};

	public trigger(name: string, params?: [HTMLFormElement, () => void]): void {
		if (this.listeners[name]) {
			this.listeners[name].call(this, params);
		}
	}
	public serialize() {
		const result: Array<string> = [];
		Array.prototype.slice.call(this.element.elements).forEach(function (control: HTMLSelectElement | HTMLInputElement) {
			if (control.name && !control.disabled && ["file", "reset", "submit", "button"].indexOf(control.type) === -1)
				if (control.type === "select-multiple") 
					Array.prototype.slice.call((control as HTMLSelectElement).options).forEach(function (option: HTMLOptionElement) {
						if (option.selected) result.push(encodeURIComponent(control.name) + "=" + encodeURIComponent(option.value));
					});
				else if (["checkbox", "radio"].indexOf(control.type) === -1 || (control as HTMLInputElement).checked) result.push(encodeURIComponent(control.name) + "=" + encodeURIComponent(control.value));
				else if (control.value != "") result.push(encodeURIComponent(control.name) + "=" + encodeURIComponent(control.value));
		});
		return result.join("&").replace(/%20/g, "+");
	}

	public setAuthenticated() {
		this._authenticated = true;
	}
	public getHeaders(): Headers {
		const headers = new Headers();
		headers.set("accept", "application/json, application/xml, text/plain, text/html, *.*");
		headers.set("X-WP-Nonce", ajaxyFormsSettings.nonce);
		headers.set("cache", "no-cache");
		headers.set("redirect", "follow");
		return headers;
	}

	public clearErrors() {
		if (this.errorElms) {
			this.errorElms.forEach((elm) => (elm.innerHTML = ""));
		}
		if (this.messageElm) {
			this.messageElm.innerHTML = "";
		}
	}

	public isObject = (value) => {
		return (
			value != null && // Exclude null
			typeof value === "object" &&
			!Array.isArray(value)
		); // Exclude arrays
	};

	public addErrors(field: string, errors: Array<string> | { [x: string]: Array<string> }) {
		if (this.errorElms) {
			if (this.isObject(errors)) {
				let subnames = Object.keys(errors);
				for (let i = 0; i < subnames.length; i++) {
					this.addErrors(subnames[i], errors[subnames[i]]);
				}
				return;
			} else if (typeof errors == "string") {
				errors = [errors];
			}
			if (Array.isArray(errors)) {
				this.errorElms.forEach((elm) => {
					if (elm.classList.contains("field-" + field)) {
						let ul = document.createElement("ul");
						//@ts-ignore
						errors.forEach((error) => {
							let li = document.createElement("li");
							li.innerHTML = error;
							ul.appendChild(li);
						});
						elm.innerHTML = "";
						elm.appendChild(ul);
					}
				});
			}
		}
	}

	public setMessage(message: string, type: string = "success") {
		if (!this.messageElm || !message || message == "") {
			return;
		}
		this.messageElm.classList.remove("success", "error");

		this.messageElm.innerHTML = message;
		this.messageElm.classList.add(type);
	}
}

export { Form };

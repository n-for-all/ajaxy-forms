type Provider = {
	name: string;
	selector: string;
	api: string;
};

const PROVIDERS: Array<Provider> = [
	{ name: "recaptcha", selector: ".g-recaptcha", api: "grecaptcha" },
	{ name: "turnstile", selector: ".cf-turnstile", api: "turnstile" },
];

const toArray = (list: any): Array<any> => Array.prototype.slice.call(list);

/**
 * A single captcha widget, rendered explicitly so that we keep the widget id
 * and can reset it, the token is single use and a failed submission leaves a
 * spent one behind.
 */
class Captcha {
	element: HTMLElement;
	provider: Provider;
	widgetId: string | number | null = null;

	constructor(element: HTMLElement, provider: Provider) {
		this.element = element;
		this.provider = provider;
	}

	private api() {
		return window[this.provider.api];
	}

	private params() {
		const params = {};
		const data = this.element.dataset;
		for (const key in data) {
			if (key === "ajaxyCaptcha" || !data[key]) continue;
			params[key] = data[key];
		}
		return params;
	}

	public render(): boolean {
		if (this.widgetId !== null) return true;

		const api = this.api();
		if (!api || typeof api.render !== "function") return false;

		try {
			const widgetId = api.render(this.element, this.params());
			this.widgetId = widgetId === undefined || widgetId === null ? this.element.id : widgetId;
		} catch (e) {
			// Already rendered by the provider itself, keep it and reset by container.
			this.widgetId = this.element.id;
			console.warn("Ajaxy Forms: unable to render the captcha", e);
		}
		return true;
	}

	public reset() {
		const api = this.api();
		if (!api || typeof api.reset !== "function") return;

		try {
			if (this.widgetId !== null) api.reset(this.widgetId);
			else api.reset();
		} catch (e) {
			console.warn("Ajaxy Forms: unable to reset the captcha", e);
		}
	}
}

/**
 * Renders every captcha on the page and resets the ones belonging to a form.
 *
 * The provider scripts are printed inside the form markup and load async, so
 * they are ready either before or well after this script runs, hence the poll.
 */
class CaptchaManager {
	private captchas: Array<Captcha> = [];
	private timer: any = null;
	private attempts = 0;

	public discover(root: ParentNode = document) {
		PROVIDERS.forEach((provider) => {
			toArray(root.querySelectorAll(provider.selector)).forEach((element: HTMLElement) => {
				if (element.dataset.ajaxyCaptcha) return;
				element.dataset.ajaxyCaptcha = provider.name;
				this.captchas.push(new Captcha(element, provider));
			});
		});
		this.render();
	}

	public render() {
		if (this.pending().length === 0 || this.timer) return;

		this.attempts = 0;
		this.timer = setInterval(() => {
			this.attempts++;
			if (this.pending().length === 0 || this.attempts > 300) {
				clearInterval(this.timer);
				this.timer = null;
			}
		}, 100);
	}

	public reset(root?: HTMLElement) {
		this.captchas.forEach((captcha) => {
			if (!root || root.contains(captcha.element)) captcha.reset();
		});
	}

	private pending(): Array<Captcha> {
		return this.captchas.filter((captcha) => !captcha.render());
	}
}

const captchas = new CaptchaManager();

export { captchas, Captcha, CaptchaManager };

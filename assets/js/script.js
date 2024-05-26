(function () {
    'use strict';

    var Form = /** @class */ (function () {
        function Form(element, submitButton, validate, _normalSubmit) {
            var _this = this;
            this.listeners = {};
            this._authenticated = false;
            this.addListener = function (name, callback) {
                _this.listeners[name] = callback;
            };
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
            this.element.addEventListener("submit", function (e) {
                var _a, _b;
                e.preventDefault();
                _this.element.classList.remove("invalid");
                if (_this.submitButton && _this.submitButton.classList.contains("loading"))
                    return;
                else
                    (_a = _this.submitButton) === null || _a === void 0 ? void 0 : _a.classList.add("loading");
                if (!_this._validate || _this._validate(_this.element)) {
                    if (_this.listeners["submit"]) {
                        return _this.trigger("submit", [
                            _this.element,
                            function () {
                                var _a;
                                (_a = _this.submitButton) === null || _a === void 0 ? void 0 : _a.classList.remove("loading");
                            },
                        ]);
                    }
                    var data = new FormData(_this.element);
                    var headers = _this.getHeaders();
                    // data.append("action", "af_submit");
                    data.append("form_name", _this.element.name);
                    var method = (_this.element.method ? _this.element.method : "POST").toUpperCase();
                    var fetchData = {
                        headers: headers,
                        method: method
                    };
                    var action = _this.element.action;
                    if (method == "GET" || method == "HEAD") {
                        //@ts-ignore
                        action = action + "?" + new URLSearchParams(data).toString();
                    }
                    else {
                        fetchData["body"] = data;
                    }
                    fetch(action, fetchData)
                        .then(function (response) {
                        response
                            .json()
                            .then(function (json) {
                            var _a;
                            _this.clearErrors();
                            _this.trigger("response", json);
                            (_a = _this.submitButton) === null || _a === void 0 ? void 0 : _a.classList.remove("loading");
                            if (json) {
                                if (json.status == "error") {
                                    var fields = json.fields;
                                    var names = Object.keys(fields);
                                    for (var i = 0; i < names.length; i++) {
                                        _this.addErrors(names[i], fields[names[i]]);
                                    }
                                }
                                else {
                                    if (json.status == "success") {
                                        _this.element.reset();
                                        _this.trigger("success", json);
                                    }
                                }
                                if (json.message) {
                                    _this.setMessage(json.message, json.status);
                                }
                                if (json._token) {
                                    var token = _this.element.querySelector('[name*="[_token]"]');
                                    if (token) {
                                        token.value = json._token;
                                    }
                                }
                            }
                        })["catch"](function (error) {
                            var _a;
                            _this.clearErrors();
                            _this.trigger("error", error);
                            (_a = _this.submitButton) === null || _a === void 0 ? void 0 : _a.classList.remove("loading");
                            _this.setMessage(error.message, "error");
                        });
                    })["catch"](function (e) {
                        var _a;
                        _this.trigger("error", e);
                        (_a = _this.submitButton) === null || _a === void 0 ? void 0 : _a.classList.remove("loading");
                        console.error(e);
                        _this.setMessage(e.message, "error");
                    });
                }
                else {
                    (_b = _this.submitButton) === null || _b === void 0 ? void 0 : _b.classList.remove("loading");
                    _this.element.classList.add("invalid");
                }
            });
            this.element.setAttribute("rendered", "1");
        }
        Form.prototype.trigger = function (name, params) {
            if (this.listeners[name]) {
                this.listeners[name].call(this, params);
            }
        };
        Form.prototype.serialize = function () {
            var result = [];
            Array.prototype.slice.call(this.element.elements).forEach(function (control) {
                if (control.name && !control.disabled && ["file", "reset", "submit", "button"].indexOf(control.type) === -1)
                    if (control.type === "select-multiple")
                        Array.prototype.slice.call(control.options).forEach(function (option) {
                            if (option.selected)
                                result.push(encodeURIComponent(control.name) + "=" + encodeURIComponent(option.value));
                        });
                    else if (["checkbox", "radio"].indexOf(control.type) === -1 || control.checked)
                        result.push(encodeURIComponent(control.name) + "=" + encodeURIComponent(control.value));
                    else if (control.value != "")
                        result.push(encodeURIComponent(control.name) + "=" + encodeURIComponent(control.value));
            });
            return result.join("&").replace(/%20/g, "+");
        };
        Form.prototype.setAuthenticated = function () {
            this._authenticated = true;
        };
        Form.prototype.getHeaders = function () {
            var headers = new Headers();
            headers.set("accept", "application/json, application/xml, text/plain, text/html, *.*");
            headers.set("X-WP-Nonce", ajaxyFormsSettings.nonce);
            headers.set("cache", "no-cache");
            headers.set("redirect", "follow");
            return headers;
        };
        Form.prototype.clearErrors = function () {
            if (this.errorElms) {
                this.errorElms.forEach(function (elm) { return (elm.innerHTML = ""); });
            }
            if (this.messageElm) {
                this.messageElm.innerHTML = "";
            }
        };
        Form.prototype.addErrors = function (field, errors) {
            if (this.errorElms) {
                if (!(errors instanceof Array)) {
                    var subnames = Object.keys(errors);
                    for (var i = 0; i < subnames.length; i++) {
                        this.addErrors(subnames[i], errors[subnames[i]]);
                    }
                    return;
                }
                this.errorElms.forEach(function (elm) {
                    if (elm.classList.contains("field-" + field)) {
                        var ul_1 = document.createElement("ul");
                        errors.map(function (error) {
                            var li = document.createElement("li");
                            li.innerHTML = error;
                            ul_1.appendChild(li);
                        });
                        elm.innerHTML = "";
                        elm.appendChild(ul_1);
                    }
                });
            }
        };
        Form.prototype.setMessage = function (message, type) {
            if (type === void 0) { type = "success"; }
            if (!this.messageElm || !message || message == "") {
                return;
            }
            this.messageElm.classList.remove("success", "error");
            this.messageElm.innerHTML = message;
            this.messageElm.classList.add(type);
        };
        return Form;
    }());

    var AjaxyForms = /** @class */ (function () {
        function AjaxyForms() {
            var _this = this;
            this.forms = {};
            this.ready(function () {
                var forms = document.querySelectorAll("form.ajaxy-form.is-ajax");
                if (forms.length > 0) {
                    [].forEach.call(forms, function (form) {
                        _this.forms[form.name] = new Form(form);
                    });
                }
            });
        }
        AjaxyForms.prototype.closest = function (el, tag) {
            tag = tag.toUpperCase();
            do {
                if (el.nodeName === tag) {
                    return el;
                }
            } while ((el = el.parentNode));
            return null;
        };
        AjaxyForms.prototype.ready = function (fn) {
            if (document.readyState != "loading") {
                fn();
            }
            else {
                document.addEventListener("DOMContentLoaded", fn);
            }
        };
        AjaxyForms.prototype.on = function (eventType, className, cb) {
            document.addEventListener(eventType, function (event) {
                var el = event.target, found;
                while (el && !(found = el.id === className || el.classList.contains(className.replace(".", "")))) {
                    el = el.parentElement;
                }
                if (found) {
                    cb.call(el, event);
                }
            }, false);
        };
        AjaxyForms.prototype.encodeQueryString = function (params) {
            var keys = Object.keys(params);
            return keys.length ? "?" + keys.map(function (key) { return encodeURIComponent(key) + "=" + encodeURIComponent(params[key]); }).join("&") : "";
        };
        return AjaxyForms;
    }());
    window["AjaxyForms"] = new AjaxyForms();

})();

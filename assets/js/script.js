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
            this.isObject = function (value) {
                return (value != null && // Exclude null
                    typeof value === "object" &&
                    !Array.isArray(value)); // Exclude arrays
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
                _this.element.dispatchEvent && _this.element.dispatchEvent(new CustomEvent("beforesubmit", {
                    bubbles: true,
                    cancelable: true,
                    detail: {
                        form: _this.element,
                        submitButton: _this.submitButton,
                    },
                }));
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
                        method: method,
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
                                    if (json.fields) {
                                        _this.errorElms = _this.element.querySelectorAll(".field-error");
                                        var fields = json.fields;
                                        var names = Object.keys(fields);
                                        for (var i = 0; i < names.length; i++) {
                                            _this.addErrors(names[i], fields[names[i]]);
                                        }
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
                        })
                            .catch(function (error) {
                            var _a;
                            _this.clearErrors();
                            _this.trigger("error", error);
                            (_a = _this.submitButton) === null || _a === void 0 ? void 0 : _a.classList.remove("loading");
                            _this.setMessage(error.message, "error");
                        });
                    })
                        .catch(function (e) {
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
                if (this.isObject(errors)) {
                    var subnames = Object.keys(errors);
                    for (var i = 0; i < subnames.length; i++) {
                        this.addErrors(subnames[i], errors[subnames[i]]);
                    }
                    return;
                }
                else if (typeof errors == "string") {
                    errors = [errors];
                }
                if (Array.isArray(errors)) {
                    this.errorElms.forEach(function (elm) {
                        if (elm.classList.contains("field-" + field)) {
                            var ul_1 = document.createElement("ul");
                            //@ts-ignore
                            errors.forEach(function (error) {
                                var li = document.createElement("li");
                                li.innerHTML = error;
                                ul_1.appendChild(li);
                            });
                            elm.innerHTML = "";
                            elm.appendChild(ul_1);
                        }
                    });
                }
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

    /*! *****************************************************************************
    Copyright (c) Microsoft Corporation.

    Permission to use, copy, modify, and/or distribute this software for any
    purpose with or without fee is hereby granted.

    THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES WITH
    REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF MERCHANTABILITY
    AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY SPECIAL, DIRECT,
    INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES WHATSOEVER RESULTING FROM
    LOSS OF USE, DATA OR PROFITS, WHETHER IN AN ACTION OF CONTRACT, NEGLIGENCE OR
    OTHER TORTIOUS ACTION, ARISING OUT OF OR IN CONNECTION WITH THE USE OR
    PERFORMANCE OF THIS SOFTWARE.
    ***************************************************************************** */
    /* global Reflect, Promise */


    var __assign = function() {
        __assign = Object.assign || function __assign(t) {
            for (var s, i = 1, n = arguments.length; i < n; i++) {
                s = arguments[i];
                for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p)) t[p] = s[p];
            }
            return t;
        };
        return __assign.apply(this, arguments);
    };

    var AjaxyTermPostsManager = /** @class */ (function () {
        function AjaxyTermPostsManager(form, container) {
            var _this = this;
            var dataSettings = JSON.parse(container.getAttribute("data-term-posts"));
            var messages = JSON.parse(container.getAttribute("data-messages"));
            this.form = form;
            this.termsEl = container.querySelector("#".concat(container.id, "_terms"));
            this.postsEl = container.querySelector("#".concat(container.id, "_posts"));
            if (!this.termsEl || !this.postsEl) {
                return;
            }
            this.dataSettings = dataSettings;
            this.loadingMessage = messages["loading"];
            this.noPostsMessage = messages["not_found"];
            this.defaultMessage = messages["default_option"];
            this.loadEvents();
            this.form.element.addEventListener("reset", function () {
                _this.postsEl.innerHTML = '<option value="">' + _this.defaultMessage + "</option>";
            });
        }
        AjaxyTermPostsManager.prototype.loadEvents = function () {
            var _this = this;
            this.termsEl.addEventListener("change", function () {
                _this.loadPosts(_this.termsEl.value);
            });
            if (this.termsEl.value != "") {
                this.termsEl.dispatchEvent(new Event("change"));
            }
        };
        AjaxyTermPostsManager.prototype.loadPosts = function (termId) {
            var _this = this;
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
                body: JSON.stringify(__assign({ type: "posts_by_term", term_id: termId }, this.dataSettings)),
            })
                .then(function (response) { return response.json(); })
                .then(function (response) {
                _this.postsEl.style.opacity = "1";
                if (response.status != "success") {
                    console.error(response.message);
                    return;
                }
                _this.postsEl.innerHTML = "";
                if (response.data.length == 0) {
                    _this.postsEl.innerHTML = '<option value="">' + _this.noPostsMessage + "</option>";
                    return;
                }
                response.data.forEach(function (post) {
                    _this.postsEl.innerHTML += "<option value=\"".concat(post.id, "\">").concat(post.title, "</option>");
                });
            });
        };
        return AjaxyTermPostsManager;
    }());

    var AjaxyRepeater = /** @class */ (function () {
        function AjaxyRepeater(element, form) {
            var _this = this;
            var _a, _b;
            this.index = 0;
            this.btnRemove = [];
            try {
                console.log(element.getAttribute("data-settings"));
                this.settings = JSON.parse(element.getAttribute("data-settings"));
            }
            catch (e) {
                console.error("Invalid settings", element);
                return;
            }
            if (!this.settings) {
                return;
            }
            this.form = form;
            this.element = element;
            this.id = element.getAttribute("id");
            this.template = this.unescapeHTML((_a = document.querySelector("#template-".concat(this.id))) === null || _a === void 0 ? void 0 : _a.innerHTML);
            this.items = element.querySelector(".repeater-items");
            if (this.settings.allowAdd) {
                this.btnAdd = element.querySelector('[data-action="add"]');
                (_b = this.btnAdd) === null || _b === void 0 ? void 0 : _b.addEventListener("click", function () {
                    _this.add();
                    _this.index++;
                });
            }
            if (this.settings.min > 0) {
                for (var i = 0; i < this.settings.min; i++) {
                    this.add();
                    this.index++;
                }
            }
            this.form.element.addEventListener("reset", function () {
                _this.items.innerHTML = "";
                _this.index = 0;
                _this.btnRemove.map(function (btn) {
                    btn.disabled = true;
                    btn.style.display = "none";
                });
            });
        }
        AjaxyRepeater.prototype.isMax = function () {
            var _a;
            return ((_a = this.items) === null || _a === void 0 ? void 0 : _a.childElementCount) >= this.settings.max && this.settings.max > 0;
        };
        AjaxyRepeater.prototype.unescapeHTML = function (string) {
            var elt = document.createElement("span");
            elt.innerHTML = string;
            var txt = elt.innerText;
            elt.remove();
            console.log(txt);
            return txt;
        };
        AjaxyRepeater.prototype.add = function () {
            var _this = this;
            var _a;
            if (this.isMax()) {
                return;
            }
            this.btnRemove.map(function (btn) {
                btn.disabled = false;
                btn.style.display = "";
            });
            var newItem = document.createElement("div");
            newItem.classList.add("repeater-item");
            newItem.innerHTML = (_a = this.template) === null || _a === void 0 ? void 0 : _a.replace(/--index/g, "--".concat(this.index));
            this.items.appendChild(newItem);
            if (this.settings.allowDelete) {
                var btnRemove = newItem.querySelector('[data-action="remove"]');
                if (btnRemove) {
                    if (this.settings.min > 0 && this.items.childElementCount <= this.settings.min) {
                        btnRemove.disabled = true;
                        btnRemove.style.display = "none";
                    }
                    btnRemove.addEventListener("click", function (e) {
                        e.preventDefault();
                        if (_this.btnAdd)
                            _this.btnAdd.disabled = false;
                        if ((_this.settings.min > 0 && _this.items.childElementCount > _this.settings.min) || _this.settings.min <= 0) {
                            newItem.remove();
                            if (_this.settings.min <= 0)
                                return;
                            if (_this.items.childElementCount <= _this.settings.min) {
                                _this.btnRemove.map(function (btn) {
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
        };
        return AjaxyRepeater;
    }());

    var AjaxyForms = /** @class */ (function () {
        function AjaxyForms() {
            var _this = this;
            this.forms = {};
            this.repeaters = {};
            this.term_posts = {};
            this.ready(function () {
                var forms = document.querySelectorAll("form.ajaxy-form.is-ajax");
                if (forms.length > 0) {
                    [].forEach.call(forms, function (form) {
                        _this.forms[form.name] = new Form(form);
                        _this.forms[form.name].addListener("item-added", function (_a) {
                            var elm = _a[0];
                            var nTermPosts = elm.querySelectorAll("[data-term-posts]");
                            if (nTermPosts.length) {
                                nTermPosts.forEach(function (nTermPost) {
                                    _this.term_posts[nTermPost.id] = new AjaxyTermPostsManager(_this.forms[form.name], nTermPost);
                                });
                            }
                        });
                        var term_posts = form.querySelectorAll("[data-term-posts]");
                        if (term_posts.length > 0) {
                            [].forEach.call(term_posts, function (term_post) {
                                _this.term_posts[term_post.id] = new AjaxyTermPostsManager(_this.forms[form.name], term_post);
                            });
                        }
                        var repeaters = form.querySelectorAll(".repeater");
                        if (repeaters.length > 0) {
                            [].forEach.call(repeaters, function (repeater) {
                                _this.repeaters[repeater.id] = new AjaxyRepeater(repeater, _this.forms[form.name]);
                            });
                        }
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
    window["AjaxyTermPostsManager"] = AjaxyTermPostsManager;
    window["AjaxyForms"] = new AjaxyForms();

})();

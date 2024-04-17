"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
require("whatwg-fetch");
var AjaxyWooGithub = (function () {
    function AjaxyWooGithub() {
        var _this = this;
        this.ready(function () {
            _this.handleWpMedia();
            _this.on("change", "ajaxy-github-repository-select", function (el) {
                var target = el.target || el.srcElement;
                var value = target.options[target.selectedIndex].value;
                var query = _this.encodeQueryString({ action: "repo_releases_action", repo: value, _: new Date().getTime() });
                var row = _this.closest(target, "tr");
                var nextElement = row.querySelector("select.ajaxy-github-release-select");
                nextElement.disabled = true;
                fetch("".concat(ajaxurl).concat(query))
                    .then(function (res) { return res.json(); })
                    .then(function (json) {
                    nextElement.disabled = false;
                    if (json.status == "success") {
                        if (json.releases) {
                            nextElement.innerHTML = "";
                            if (json.releases.length) {
                                json.releases.map(function (item) {
                                    var option = document.createElement("option");
                                    option.innerHTML = item.label;
                                    option.value = item.value;
                                    nextElement.appendChild(option);
                                });
                            }
                            nextElement.disabled = false;
                        }
                    }
                    else {
                        alert(json.message);
                    }
                })
                    .catch(function (err) {
                    nextElement.disabled = false;
                    alert(err.message);
                    console.error(err);
                });
                console.log(el);
            });
        });
    }
    AjaxyWooGithub.prototype.closest = function (el, tag) {
        tag = tag.toUpperCase();
        do {
            if (el.nodeName === tag) {
                return el;
            }
        } while ((el = el.parentNode));
        return null;
    };
    AjaxyWooGithub.prototype.ready = function (fn) {
        if (document.readyState != "loading") {
            fn();
        }
        else {
            document.addEventListener("DOMContentLoaded", fn);
        }
    };
    AjaxyWooGithub.prototype.on = function (eventType, className, cb) {
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
    AjaxyWooGithub.prototype.encodeQueryString = function (params) {
        var keys = Object.keys(params);
        return keys.length ? "?" + keys.map(function (key) { return encodeURIComponent(key) + "=" + encodeURIComponent(params[key]); }).join("&") : "";
    };
    AjaxyWooGithub.prototype.handleWpMedia = function () {
    };
    return AjaxyWooGithub;
}());
window["AjaxyWooGithub"] = new AjaxyWooGithub();

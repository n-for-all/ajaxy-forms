(function () {
    'use strict';

    /******************************************************************************
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

    var extendStatics = function(d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (Object.prototype.hasOwnProperty.call(b, p)) d[p] = b[p]; };
        return extendStatics(d, b);
    };

    function __extends(d, b) {
        if (typeof b !== "function" && b !== null)
            throw new TypeError("Class extends value " + String(b) + " is not a constructor or null");
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    }

    var DraggableModel = (function (_super) {
        __extends(DraggableModel, _super);
        function DraggableModel() {
            return _super !== null && _super.apply(this, arguments) || this;
        }
        return DraggableModel;
    }(Backbone.Model));

    var DroppableCollection = (function (_super) {
        __extends(DroppableCollection, _super);
        function DroppableCollection() {
            return _super !== null && _super.apply(this, arguments) || this;
        }
        DroppableCollection.prototype.initialize = function () {
            this.on("add", this._add);
            this.on("remove", this._remove);
        };
        DroppableCollection.prototype._add = function (model) {
            if (this.view && model.view) {
                this.view.$el.append(model.view.$el);
            }
        };
        DroppableCollection.prototype._remove = function (model) {
            if (model.view) {
                model.view.$el.detach();
            }
        };
        return DroppableCollection;
    }(Backbone.Collection));

    var RepeaterField = (function (_super) {
        __extends(RepeaterField, _super);
        function RepeaterField(basename, field, data) {
            var _this = _super.call(this) || this;
            _this.data = [];
            _this.data = data || [];
            _this.field = field;
            _this.basename = basename;
            _this.index = 1;
            return _this;
        }
        RepeaterField.prototype.createInnerField = function (_field, item, index) {
            var el = jQuery("<div></div>").addClass(["af-field", "af-field-".concat(_field.type)]);
            var inputDiv = jQuery("<div></div>").addClass("af-field-input");
            var input = jQuery("<input></input>")
                .addClass("widefat")
                .attr("type", _field.type || "text")
                .attr("name", _field.name ? "".concat(this.basename, "[").concat(this.field.name, "][").concat(index, "][").concat(_field.name, "]") : "".concat(this.basename, "[").concat(this.field.name, "][").concat(index, "]"))
                .val(item ? item[_field.name] || "" : "");
            if (_field.label) {
                input.attr("placeholder", _field.label);
            }
            input.on("change", function (e) {
                item[_field.name] = jQuery(e.target).val();
            });
            inputDiv.append(input);
            if (_field.help) {
                var small = jQuery("<small></small>").html(_field.help);
                inputDiv.append(small);
            }
            el.append(inputDiv);
            return el;
        };
        RepeaterField.prototype.createRemoveButton = function (index) {
            var _this = this;
            var lnk = jQuery("<a></a>").addClass("af-repeater-remove").html('<span class="dashicons dashicons-remove"></span>').attr("href", "#");
            lnk.on("click", function (e) {
                e.preventDefault();
                _this.removeRepeater(index);
            });
            return lnk;
        };
        RepeaterField.prototype.createAddButton = function () {
            var _this = this;
            var lnk = jQuery("<a></a>").addClass("af-repeater-add").html('<span class="dashicons dashicons-insert"></span>').attr("href", "#");
            lnk.on("click", function (e) {
                e.preventDefault();
                _this.add();
            });
            return lnk;
        };
        RepeaterField.prototype.removeRepeater = function (index) {
            this.data.splice(index, 1);
            this.render();
        };
        RepeaterField.prototype.addRepeater = function (item, index) {
            var _this = this;
            var repeaterDiv = jQuery("<div></div>").addClass("af-repeater-inner");
            this.field.fields.map(function (_field) {
                var inner = _this.createInnerField(_field, item, index);
                repeaterDiv.append(inner);
            });
            repeaterDiv.append(this.createRemoveButton(index));
            this.$el.append(repeaterDiv);
        };
        RepeaterField.prototype.add = function () {
            this.data.push({});
            this.render();
        };
        RepeaterField.prototype.render = function () {
            var _this = this;
            this.$el.empty();
            if (this.field.label) {
                var heading = jQuery("<h4></h4>").html(this.field.label);
                this.$el.append(heading);
            }
            if (this.field.help) {
                var small = jQuery("<small></small>").html(this.field.help);
                this.$el.append(small);
            }
            if (this.data) {
                this.data.map(function (item, index) {
                    _this.addRepeater(item, index);
                });
            }
            this.$el.addClass(["af-repeater"]);
            this.$el.append(this.createAddButton());
            return this;
        };
        return RepeaterField;
    }(Backbone.View));

    var SelectField = (function (_super) {
        __extends(SelectField, _super);
        function SelectField(basename, field, value) {
            var _this = _super.call(this) || this;
            _this.field = field;
            _this.basename = basename;
            _this.value = value;
            return _this;
        }
        SelectField.prototype.createField = function () {
            var _this = this;
            this.$el.addClass(["af-field", "af-field-".concat(this.field.type)]);
            if (this.field.label) {
                var label = jQuery("<label></label>").html(this.field.label);
                this.$el.append(label);
            }
            var inputDiv = jQuery("<div></div>").addClass("af-field-input");
            var select = jQuery("<select></select>").attr("name", "".concat(this.basename, "[").concat(this.field.name, "]")).addClass("widefat").val(this.value);
            Object.keys(this.field.options)
                .map(function (key) {
                var option = _this.field.options[key];
                var optionEl = jQuery("<option></option>").attr("value", key).html(option);
                select.append(optionEl);
            })
                .join("");
            inputDiv.append(select);
            if (this.field.help) {
                var small = jQuery("<small></small>").html(this.field.help);
                inputDiv.append(small);
            }
            this.$el.append(inputDiv);
        };
        SelectField.prototype.render = function () {
            this.createField();
            return this;
        };
        return SelectField;
    }(Backbone.View));

    var TextAreaField = (function (_super) {
        __extends(TextAreaField, _super);
        function TextAreaField(basename, field, value) {
            var _this = _super.call(this) || this;
            _this.field = field;
            _this.basename = basename;
            _this.value = value;
            return _this;
        }
        TextAreaField.prototype.createField = function () {
            this.$el.addClass(["af-field", "af-field-".concat(this.field.type)]);
            if (this.field.label) {
                var label = jQuery("<label></label>").html(this.field.label);
                this.$el.append(label);
            }
            var inputDiv = jQuery("<div></div>").addClass("af-field-input");
            var input = jQuery("<textarea></textarea>").attr("name", "".concat(this.basename, "[").concat(this.field.name, "]")).addClass("widefat").val(this.value);
            inputDiv.append(input);
            if (this.field.help) {
                var small = jQuery("<small></small>").html(this.field.help);
                inputDiv.append(small);
            }
            this.$el.append(inputDiv);
        };
        TextAreaField.prototype.render = function () {
            this.createField();
            return this;
        };
        return TextAreaField;
    }(Backbone.View));

    var TextField = (function (_super) {
        __extends(TextField, _super);
        function TextField(basename, field, value) {
            var _this = _super.call(this) || this;
            _this.field = field;
            _this.basename = basename;
            _this.value = value;
            return _this;
        }
        TextField.prototype.createField = function () {
            this.$el.addClass(["af-field", "af-field-".concat(this.field.type)]);
            if (this.field.label) {
                var label = jQuery("<label></label>").html(this.field.label);
                this.$el.append(label);
            }
            var inputDiv = jQuery("<div></div>").addClass("af-field-input");
            var input = jQuery("<input></input>")
                .attr("type", this.field.type || "text")
                .attr("name", "".concat(this.basename, "[").concat(this.field.name, "]"))
                .val(this.value)
                .addClass(["widefat", "af-input-".concat(this.field.name)]);
            if (this.field.type === "checkbox" || this.field.type === "radio") {
                (this.field.default || this.value == "1") && input.attr("checked", "checked");
                input.val(1);
            }
            else {
                if (!this.value || this.value === "") {
                    input.val(this.field.default || "");
                }
            }
            inputDiv.append(input);
            if (this.field.help) {
                var small = jQuery("<small></small>").html(this.field.help);
                inputDiv.append(small);
            }
            this.$el.append(inputDiv);
        };
        TextField.prototype.render = function () {
            this.createField();
            return this;
        };
        return TextField;
    }(Backbone.View));

    var SettingsSectionFields = (function (_super) {
        __extends(SettingsSectionFields, _super);
        function SettingsSectionFields(basename, fields, data) {
            var _this = _super.call(this) || this;
            _this.fields = fields;
            _this.basename = basename;
            _this.data = data;
            return _this;
        }
        SettingsSectionFields.prototype.render = function () {
            var _this = this;
            if (!this.fields || this.fields.length === 0)
                return this;
            var fieldMap = Object.keys(this.fields).map(function (key) {
                var _field = _this.fields[key];
                switch (_field.type) {
                    case "repeater":
                        return new RepeaterField(_this.basename, _field, _this.data ? _this.data[_field.name] || [] : []);
                    case "select":
                        return new SelectField(_this.basename, _field, _this.data ? _this.data[_field.name] || "" : "");
                    case "textarea":
                        return new TextAreaField(_this.basename, _field, _this.data ? _this.data[_field.name] || "" : "");
                    default:
                        return new TextField(_this.basename, _field, _this.data ? _this.data[_field.name] || "" : "");
                }
            });
            fieldMap.forEach(function (field) {
                _this.$el.append(field.render().el);
            });
            return this;
        };
        return SettingsSectionFields;
    }(Backbone.View));

    var ConstraintsView = (function (_super) {
        __extends(ConstraintsView, _super);
        function ConstraintsView(basename, data) {
            var _this = _super.call(this) || this;
            _this.index = 0;
            _this.basename = "";
            _this.data = data || {};
            _this.basename = basename;
            return _this;
        }
        ConstraintsView.prototype.createSelect = function (constraintValue, index) {
            var _this = this;
            var container = jQuery("<div class='expand-settings'><h3><span class=\"af-text\">Constraint</span><a href=\"#\" class=\"item-toggle\"><svg viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\n                <path d=\"M6 12H12M12 12H18M12 12V18M12 12V6\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"></path>\n            </svg></a></h3></div>");
            var toggleContainer = jQuery('<div class="section-toggle"></div>');
            var fieldsContainer = jQuery('<div class="section-inner"></div>');
            var field = jQuery("<div class='af-field af-field-select'></div>");
            var $select = jQuery("<select></select>");
            $select.attr("name", "".concat(this.basename, "[constraints][").concat(index, "][type]"));
            $select.append(jQuery("<option></option>").val("").text("---"));
            Object.keys(ajaxyFormsBuilder.constraints).forEach(function (key) {
                $select.append(jQuery("<option></option>").val(key).text(ajaxyFormsBuilder.constraints[key].label));
            });
            container.find(".item-toggle").on("click", function (e) {
                e.preventDefault();
                container.toggleClass("active");
            });
            var help = jQuery("<small></small>").addClass("help-block");
            var fields = jQuery("<div></div>");
            field.append($select);
            fieldsContainer.append(field);
            fieldsContainer.append(fields);
            fieldsContainer.append(help);
            var remove = jQuery("<a href='#' class='af-remove'>Remove</a>");
            remove.on("click", function (e) {
                e.preventDefault();
                container.remove();
            });
            fieldsContainer.append(remove);
            toggleContainer.append(fieldsContainer);
            container.append(toggleContainer);
            $select.on("change", function (e) {
                var value = $select.val().toString();
                var constraint = ajaxyFormsBuilder.constraints[value];
                fields.html("");
                fields.append(new SettingsSectionFields("".concat(_this.basename, "[constraints][").concat(index, "]"), constraint.fields, constraintValue).render().el);
                if (value && ajaxyFormsBuilder.constraints[value] && ajaxyFormsBuilder.constraints[value].help) {
                    help.html(ajaxyFormsBuilder.constraints[value].help);
                    if (ajaxyFormsBuilder.constraints[value].docs) {
                        help.append(' | <a target="_blank" href="' + ajaxyFormsBuilder.constraints[value].docs + '" target="_blank">View Documentation</a>');
                    }
                    container.find(".af-text").text(ajaxyFormsBuilder.constraints[value].label);
                }
                else {
                    container.find(".af-text").text("Constraint");
                    help.html("");
                }
            });
            $select.val(constraintValue.type || "");
            if (constraintValue.type) {
                $select.trigger("change");
            }
            return container;
        };
        ConstraintsView.prototype.renderSettings = function () {
            var _this = this;
            this.container.empty();
            if (this.data) {
                this.data.forEach(function (constraint, index) {
                    _this.container.append(_this.createSelect(constraint, index));
                });
            }
        };
        ConstraintsView.prototype.render = function () {
            var _this = this;
            this.$el.addClass("constraints-settings");
            this.container = jQuery("<div></div>").addClass("constraints-container");
            var addMore = jQuery("<a href='#' class='af-add-more'>Add Constraint</a>");
            addMore.on("click", function (e) {
                e.preventDefault();
                _this.data.push({ type: "" });
                _this.renderSettings();
            });
            this.renderSettings();
            this.$el.append(this.container);
            this.$el.append(addMore);
            return this;
        };
        return ConstraintsView;
    }(Backbone.View));

    var Settings = (function (_super) {
        __extends(Settings, _super);
        function Settings(index, field, data, onRemove) {
            var _this = _super.call(this) || this;
            _this.field = field;
            _this.data = data;
            _this.onRemove = onRemove;
            _this.index = index;
            return _this;
        }
        Settings.prototype.createBasicSettings = function () {
            var basicSettings = jQuery("<div></div>").addClass("basic-settings");
            var basicInnerSection = jQuery("<div></div>").addClass("section-inner");
            basicInnerSection.append(new SettingsSectionFields("fields[".concat(this.index, "]"), this.field.properties.basic.fields, this.data).render().el);
            basicSettings.append(basicInnerSection);
            return basicSettings;
        };
        Settings.prototype.createSettings = function (data, type) {
            var settings = jQuery("<div></div>").addClass(["expand-settings", "expand-settings-" + type]);
            if (data.expanded) {
                settings.addClass("active");
            }
            var heading = jQuery("<h3></h3>").html("<span>".concat(data.label, "</span>"));
            var toggle = jQuery("<a></a>").attr("href", "#").addClass("item-toggle")
                .html("<svg viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\n                <path d=\"M6 12H12M12 12H18M12 12V18M12 12V6\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"></path>\n            </svg>");
            toggle.on("click", function (e) {
                e.preventDefault();
                settings.toggleClass("active");
            });
            heading.append(toggle);
            settings.append(heading);
            var toggleInnerSection = jQuery("<div></div>").addClass("section-toggle");
            var innerSection = jQuery("<div></div>").addClass("section-inner");
            innerSection.append(new SettingsSectionFields("fields[".concat(this.index, "]"), data.fields, this.data).render().el);
            toggleInnerSection.append(innerSection);
            settings.append(toggleInnerSection);
            return settings;
        };
        Settings.prototype.createConstraintsSettings = function (data) {
            var settings = jQuery("<div></div>").addClass(["expand-settings"]);
            if (data.expanded) {
                settings.addClass("active");
            }
            var heading = jQuery("<h3></h3>").html("<span>".concat(data.label, "</span>"));
            var toggle = jQuery("<a></a>").attr("href", "#").addClass("item-toggle")
                .html("<svg viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\n                <path d=\"M6 12H12M12 12H18M12 12V18M12 12V6\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"></path>\n            </svg>");
            toggle.on("click", function (e) {
                e.preventDefault();
                settings.toggleClass("active");
            });
            heading.append(toggle);
            settings.append(heading);
            var toggleInnerSection = jQuery("<div></div>").addClass("section-toggle");
            var innerSection = jQuery("<div></div>").addClass("section-inner");
            innerSection.append(new ConstraintsView("fields[".concat(this.index, "]"), this.data.constraints || []).render().el);
            toggleInnerSection.append(innerSection);
            settings.append(toggleInnerSection);
            return settings;
        };
        Settings.prototype.render = function () {
            var _this = this;
            this.el.classList.add("wrap-settings", "wp-clearfix");
            var innerSettings = jQuery("<div></div>").addClass("settings-inner");
            innerSettings.append(this.createSettings(this.field.properties.basic, "basic"));
            innerSettings.append(this.createSettings(this.field.properties.advanced, "advanced"));
            innerSettings.append(this.createConstraintsSettings({
                label: "Validation",
                constraints: ajaxyFormsBuilder.constraints,
            }));
            innerSettings.append(jQuery("<hr/>"));
            var settingsActions = jQuery("<div></div>").addClass(["af-item-actions", "description-wide", "submitbox"]);
            var settingsAction = jQuery("<a></a>")
                .html("Remove")
                .addClass(["item-delete", "submitdelete", "deletion"])
                .attr("href", "#")
                .on("click", function (e) {
                e.preventDefault();
                _this.onRemove();
            });
            settingsActions.append(settingsAction);
            innerSettings.append(settingsActions);
            this.$el.append(innerSettings);
            return this;
        };
        Settings.prototype.toggle = function () {
            this.el.classList.toggle("active");
        };
        return Settings;
    }(Backbone.View));

    var SettingsHeader = (function (_super) {
        __extends(SettingsHeader, _super);
        function SettingsHeader(field, onToggle) {
            var _this = _super.call(this) || this;
            _this.field = field;
            _this.onToggle = onToggle;
            return _this;
        }
        SettingsHeader.prototype.render = function () {
            var _this = this;
            this.$el.addClass("af-item-bar");
            var innerHeader = jQuery("<div></div>").addClass(["af-item-handle", "ui-sortable-handle"]);
            var headerTitle = jQuery("<label></label>").addClass("item-title");
            this.title = jQuery("<span></span>").addClass("af-item-title").html("No Label");
            headerTitle.append(this.title);
            innerHeader.append(headerTitle);
            var itemControls = jQuery("<div></div>").addClass("item-controls");
            if (this.field.docs) {
                itemControls.html("<a target=\"_blank\" href=\"".concat(this.field.docs, "\" class=\"item-docs\"><span class=\"dashicons dashicons-editor-help\"></span></a>"));
            }
            itemControls.append("<span class=\"item-type\">".concat(this.field.label, "</span>"));
            jQuery('<a class="item-edit" href="#"></a>')
                .on("click", function (e) {
                e.preventDefault();
                _this.onToggle();
            })
                .appendTo(itemControls);
            innerHeader.append(itemControls);
            this.$el.append(innerHeader);
            return this;
        };
        return SettingsHeader;
    }(Backbone.View));

    var FieldView = (function (_super) {
        __extends(FieldView, _super);
        function FieldView(index, type, data, sortIndex) {
            var _this = _super.call(this, { tagName: "li" }) || this;
            _this.type = type;
            _this.data = data;
            _this.index = index;
            _this.sortIndex = sortIndex;
            return _this;
        }
        FieldView.prototype.createSettings = function (field) {
            var _this = this;
            return new Settings(this.index, field, this.data, function () {
                _this.remove();
            });
        };
        FieldView.prototype.render = function () {
            this.el.classList.add("form-item-".concat(this.index));
            var field = ajaxyFormsBuilder.fields[this.type];
            var settings = this.createSettings(field);
            var header = new SettingsHeader(field, function () {
                settings.toggle();
            });
            this.$el.append(header.render().el);
            this.$el.append(settings.render().el);
            this.$el.append("<input class=\"type-index\" name=\"fields[".concat(this.index, "][type]\" type=\"hidden\" value=\"").concat(this.type, "\">"));
            this.$el.append("<input class=\"sort-index\" name=\"fields[".concat(this.index, "][_sort]\" type=\"hidden\" value=\"").concat(this.sortIndex, "\">"));
            this.$el.on("blur", '.expand-settings-basic input.af-input-label', function () {
                if (!this.value || this.value === "") {
                    header.title.text("No Label");
                    return;
                }
                header.title.text(this.value);
            });
            return this;
        };
        return FieldView;
    }(Backbone.View));

    var DroppableView = (function (_super) {
        __extends(DroppableView, _super);
        function DroppableView(options) {
            if (options === void 0) { options = {}; }
            var _this = _super.call(this, options) || this;
            _this.fields = [];
            _this.collection = options.collection;
            _this.onDrop = options.onDrop;
            _this.render();
            return _this;
        }
        DroppableView.prototype.droppable = function (options) {
            var _this = this;
            this.$el.data("view", this);
            this.$el.data("collection", this.collection);
            this.collection.view = this;
            this.fields = [];
            this.sortable = this.$el.sortable({
                placeholder: "ui-state-highlight",
                handle: ".ui-sortable-handle",
                receive: this.onDrop.bind(this),
                update: function (event, ui) {
                    _this.$el.children().each(function (index, el) {
                        jQuery(el).find(".sort-index").val(index);
                    });
                },
            })
                .disableSelection();
        };
        DroppableView.prototype.render = function () {
            return this;
        };
        DroppableView.prototype.add = function (type, data, index) {
            var fieldView = new FieldView(this.fields.length, type, data, index);
            this.fields.push(fieldView);
            var record = fieldView.render().el;
            this.insertAt(index, record);
            try {
                setTimeout(function () {
                    window.scrollTo({
                        top: jQuery(record).offset().top - 200,
                        behavior: "smooth",
                    });
                }, 300);
            }
            catch (e) { }
        };
        DroppableView.prototype.insertAt = function (index, item) {
            var _this = this;
            if (this.$el.children().length == 0) {
                return this.$el.append(item);
            }
            else {
                return this.$el.children().each(function () {
                    if (index === 0) {
                        _this.$el.prepend(item);
                    }
                    else {
                        _this.$el
                            .children()
                            .eq(index - 1)
                            .after(item);
                    }
                });
            }
        };
        return DroppableView;
    }(Backbone.View));

    jQuery(function () {
        var droppable = document.querySelector(".af-form-wrap .droppable");
        if (!droppable) {
            return;
        }
        var dropCollection = new DroppableCollection();
        var dropView = new DroppableView({
            collection: dropCollection,
            el: jQuery(droppable),
            onDrop: function (event, ui) {
                event.preventDefault();
                this.add(ui.item.data("type"), {}, ui.helper.index());
                return true;
            },
        });
        dropView.droppable();
        jQuery(".af-fields li.draggable").draggable({
            containment: ".af-form-wrap",
            helper: "clone",
            revert: "invalid",
            connectToSortable: ".ui-sortable",
            start: function (event, ui) {
                ui.helper.height(ui.helper.prevObject.height());
                ui.helper.width(ui.helper.prevObject.width());
            },
            stop: function (event, ui) {
                ui.helper.remove();
            },
        });
        var draggables = document.querySelectorAll(".af-fields li.draggable");
        [].forEach.call(draggables, function (draggable, i) {
            var dragModel = new DraggableModel({
                type: draggable.getAttribute("data-type"),
            });
            dropCollection.add(dragModel);
        });
        var toggleMore = document.querySelector(".af-fields li.more");
        toggleMore === null || toggleMore === void 0 ? void 0 : toggleMore.addEventListener("click", function (e) {
            e.preventDefault();
            var more = document.querySelector(".af-fields .af-all-fields");
            more.classList.toggle("active");
            if (more.classList.contains("active")) {
                setTimeout(function () { return (toggleMore.querySelector("span").innerHTML = "Load Less"); }, 500);
            }
            else {
                setTimeout(function () { return (toggleMore.querySelector("span").innerHTML = "Load More"); }, 500);
            }
        });
        if (form_metadata) {
            if (form_metadata.fields) {
                form_metadata.fields.forEach(function (field, index) {
                    dropView.add(field.type, field, index);
                });
            }
        }
        window.dropCollection = dropCollection;
    });

})();

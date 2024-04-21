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

    var _uniqueId = 0;
    function uniqueId() {
        return _uniqueId++;
    }
    var RepeaterField = (function (_super) {
        __extends(RepeaterField, _super);
        function RepeaterField(basename, field, data) {
            var _this = _super.call(this) || this;
            _this.data = [];
            _this.data = data;
            _this.field = field;
            _this.basename = basename;
            return _this;
        }
        RepeaterField.prototype.createInnerField = function (_field, index) {
            var el = jQuery("<div></div>").addClass(["af-field", "af-field-".concat(_field.type)]);
            var inputDiv = jQuery("<div></div>").addClass("af-field-input");
            var input = jQuery("<input></input>")
                .addClass("widefat")
                .attr("type", _field.type || "text")
                .attr("name", _field.name ? "".concat(this.basename, "[").concat(this.field.name, "][").concat(uniqueId(), "][").concat(_field.name, "]") : "".concat(this.basename, "[").concat(this.field.name, "][").concat(uniqueId(), "]"))
                .val(this.data && this.data[index] ? this.data[index][_field.name] || "" : "");
            if (_field.label) {
                input.attr("placeholder", _field.label);
            }
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
                _this.data.push({});
                _this.addRepeater(_this.data.length - 1);
            });
            return lnk;
        };
        RepeaterField.prototype.removeRepeater = function (index) {
            this.data.splice(index, 1);
            this.$el.children(".af-repeater-inner").eq(index).remove();
        };
        RepeaterField.prototype.addRepeater = function (index) {
            var _this = this;
            var repeaterDiv = jQuery("<div></div>").addClass("af-repeater-inner");
            this.field.fields.map(function (_field) {
                var inner = _this.createInnerField(_field, index);
                repeaterDiv.append(inner);
            });
            repeaterDiv.append(this.createRemoveButton(index));
            this.$el.append(repeaterDiv);
        };
        RepeaterField.prototype.render = function () {
            var _this = this;
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
                    _this.addRepeater(index);
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
        function SelectField(basename, field) {
            var _this = _super.call(this) || this;
            _this.field = field;
            _this.basename = basename;
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
            var select = jQuery("<select></select>").attr("name", "".concat(this.basename, "[").concat(this.field.name, "]")).addClass("widefat");
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
        function TextAreaField(basename, field) {
            var _this = _super.call(this) || this;
            _this.field = field;
            _this.basename = basename;
            return _this;
        }
        TextAreaField.prototype.createField = function () {
            this.$el.addClass(["af-field", "af-field-".concat(this.field.type)]);
            if (this.field.label) {
                var label = jQuery("<label></label>").html(this.field.label);
                this.$el.append(label);
            }
            var inputDiv = jQuery("<div></div>").addClass("af-field-input");
            var input = jQuery("<textarea></textarea>").attr("name", "".concat(this.basename, "[").concat(this.field.name, "]")).addClass("widefat");
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
        function TextField(basename, field) {
            var _this = _super.call(this) || this;
            _this.field = field;
            _this.basename = basename;
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
                .addClass(["widefat", "af-input-".concat(this.field.name)]);
            if (this.field.default) {
                if (this.field.type === "checkbox" || this.field.type === "radio") {
                    input.attr("checked", "checked");
                    input.val(1);
                }
                else {
                    input.val(this.field.default);
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
        function SettingsSectionFields(basename, fields) {
            var _this = _super.call(this) || this;
            _this.fields = fields;
            _this.basename = basename;
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
                        return new RepeaterField(_this.basename, _field, []);
                    case "select":
                        return new SelectField(_this.basename, _field);
                    case "textarea":
                        return new TextAreaField(_this.basename, _field);
                    default:
                        return new TextField(_this.basename, _field);
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
        function ConstraintsView(basename) {
            var _this = _super.call(this) || this;
            _this.index = 0;
            _this.basename = '';
            _this.basename = basename;
            return _this;
        }
        ConstraintsView.prototype.createSelect = function (index) {
            var _this = this;
            var container = jQuery("<div class='expand-settings'><h3><span class=\"af-text\">Constraint</span><a href=\"#\" class=\"item-toggle\"><svg viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\n                <path d=\"M6 12H12M12 12H18M12 12V18M12 12V6\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"></path>\n            </svg></a></h3></div>");
            var toggleContainer = jQuery('<div class="section-toggle"></div>');
            var fieldsContainer = jQuery('<div class="section-inner"></div>');
            var field = jQuery("<div class='af-field af-field-select'></div>");
            var $select = jQuery("<select></select>");
            $select.attr('name', "".concat(this.basename, "[constraints][").concat(index, "][type]"));
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
                fields.append(new SettingsSectionFields("".concat(_this.basename, "[constraints][").concat(index, "]"), constraint.fields).render().el);
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
            return container;
        };
        ConstraintsView.prototype.render = function () {
            var _this = this;
            this.$el.addClass("constraints-settings");
            var container = jQuery("<div></div>").addClass("constraints-container");
            var addMore = jQuery("<a href='#' class='af-add-more'>Add Constraint</a>");
            addMore.on("click", function (e) {
                e.preventDefault();
                container.append(_this.createSelect(_this.index));
                _this.index++;
            });
            this.$el.append(container);
            this.$el.append(addMore);
            return this;
        };
        return ConstraintsView;
    }(Backbone.View));

    var Settings = (function (_super) {
        __extends(Settings, _super);
        function Settings(index, field, onRemove) {
            var _this = _super.call(this) || this;
            _this.field = field;
            _this.onRemove = onRemove;
            _this.index = index;
            return _this;
        }
        Settings.prototype.createBasicSettings = function () {
            var basicSettings = jQuery("<div></div>").addClass("basic-settings");
            var basicInnerSection = jQuery("<div></div>").addClass("section-inner");
            basicInnerSection.append(new SettingsSectionFields("field[".concat(this.index, "]"), this.field.properties.basic.fields).render().el);
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
            innerSection.append(new SettingsSectionFields("field[".concat(this.index, "]"), data.fields).render().el);
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
            innerSection.append(new ConstraintsView("field[".concat(this.index, "]")).render().el);
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
        function FieldView(index, type, data) {
            var _this = _super.call(this, { tagName: "li" }) || this;
            _this.type = type;
            _this.data = data;
            _this.index = index;
            return _this;
        }
        FieldView.prototype.createSettings = function (field) {
            var _this = this;
            return new Settings(this.index, field, function () {
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
        function DroppableView() {
            var _this = _super !== null && _super.apply(this, arguments) || this;
            _this.fields = [];
            return _this;
        }
        DroppableView.prototype.droppable = function (options) {
            this.$el.data("view", this);
            this.$el.data("collection", this.collection);
            this.collection.view = this;
            this.fields = [];
            this.sortable = this.$el.sortable({
                placeholder: "ui-state-highlight",
                handle: ".ui-sortable-handle",
            });
        };
        DroppableView.prototype.canDrop = function (draggableView) {
            return true;
        };
        DroppableView.prototype.render = function () {
            return this;
        };
        DroppableView.prototype.add = function (type, data) {
            var fieldView = new FieldView(this.fields.length, type, data);
            this.fields.push(fieldView);
            var record = fieldView.render().el;
            this.$el.append(record);
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
        return DroppableView;
    }(Backbone.View));

    var DraggableView = (function (_super) {
        __extends(DraggableView, _super);
        function DraggableView() {
            return _super !== null && _super.apply(this, arguments) || this;
        }
        DraggableView.prototype.initialize = function () {
            this.model.view = this;
            this.$el.attr("draggable", "true");
        };
        DraggableView.prototype.draggable = function (options) {
            if (options === void 0) { options = {}; }
            options.canDrop = this._canDrop.bind(this);
            options.didDrop = this._didDrop.bind(this);
            this.$el.dragdrop(options);
            this.$el.data("view", this);
            this.$el.data("model", this.model);
        };
        DraggableView.prototype._canDrop = function (el) {
            var droppableView = jQuery(el).data("view");
            if (!droppableView || !(droppableView instanceof DroppableView)) {
                return false;
            }
            return droppableView.canDrop(this) && this.canDrop(droppableView);
        };
        DraggableView.prototype._didDrop = function (src, dst) {
            var draggableView = src.data("view");
            var draggableModel = src.data("model");
            var droppableView = dst.data("view");
            var srcCollection = src.parent().data("collection");
            var dstCollection = dst.data("collection");
            if (srcCollection) {
                srcCollection.remove(draggableModel);
                droppableView.trigger("remove");
            }
            if (dstCollection) {
                dstCollection.add(draggableModel);
                droppableView.trigger("add");
            }
            draggableView.trigger("drag");
            draggableModel.trigger("drag");
            this.didDrop(draggableView, droppableView);
        };
        DraggableView.prototype.canDrop = function (droppableView) {
            return true;
        };
        DraggableView.prototype.didDrop = function (draggableView, droppableView) {
            var srcElm = draggableView.$el;
            var type = srcElm.data("type");
            droppableView.add(type, {});
        };
        return DraggableView;
    }(Backbone.View));

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

    jQuery(function () {
        var droppable = document.querySelector(".af-form-wrap .droppable");
        var dropCollection = new DroppableCollection();
        var dropView = new DroppableView({
            collection: dropCollection,
            el: jQuery(droppable),
        });
        dropView.droppable();
        var draggables = document.querySelectorAll(".af-fields li.draggable");
        [].forEach.call(draggables, function (draggable, i) {
            var dragModel = new DraggableModel({
                type: draggable.getAttribute("data-type"),
            });
            dropCollection.add(dragModel);
            var dragView = new DraggableView({
                model: dragModel,
                el: jQuery(draggable),
            });
            dragView.draggable({
                makeClone: true,
                canDropClass: "can-drop",
                dropClass: "af-drop",
            });
        });
        var toggleMore = document.querySelector(".af-fields li.more");
        toggleMore.addEventListener("click", function (e) {
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
        window.dropCollection = dropCollection;
    });

})();

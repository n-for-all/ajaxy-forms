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

    var Settings = {
        templateSettings: {
            evaluate: /<%([\s\S]+?)%>/g,
            interpolate: /<%=([\s\S]+?)%>/g,
            escape: /<%-([\s\S]+?)%>/g,
        },
    };

    var Fieldset = (function (_super) {
        __extends(Fieldset, _super);
        function Fieldset(options) {
            var _this = _super.call(this, options) || this;
            _this.template = _.template("<fieldset data-fields>\n    <% if (legend) { %>\n      <legend><%= legend %></legend>\n    <% } %>\n  </fieldset>", null, Settings.templateSettings);
            options = options || {};
            var schema = (_this.schema = _this.createSchema(options.schema));
            _this.fields = _.pick(options.fields, schema.fields);
            _this.template = options.template || schema.template || _this.template;
            return _this;
        }
        Fieldset.prototype.createSchema = function (schema) {
            if (_.isArray(schema)) {
                schema = { fields: schema };
            }
            schema.legend = schema.legend || null;
            return schema;
        };
        Fieldset.prototype.getFieldAt = function (index) {
            var key = this.schema.fields[index];
            return this.fields[key];
        };
        Fieldset.prototype.templateData = function () {
            return this.schema;
        };
        Fieldset.prototype.render = function () {
            var fields = this.fields, $ = Backbone.$;
            var $fieldset = $($.trim(this.template(_.result(this, "templateData"))));
            $fieldset
                .find("[data-fields]")
                .add($fieldset)
                .each(function (i, el) {
                var $container = $(el), selection = $container.attr("data-fields");
                if (_.isUndefined(selection))
                    return;
                _.each(fields, function (field) {
                    $container.append(field.render().el);
                });
            });
            this.setElement($fieldset);
            return this;
        };
        Fieldset.prototype.remove = function () {
            _.each(this.fields, function (field) {
                field.remove();
            });
            Backbone.View.prototype.remove.call(this);
            return this;
        };
        return Fieldset;
    }(Backbone.View));

    var Validators = {
        errMessages: {
            required: "Required",
            regexp: "Invalid",
            number: "Must be a number",
            range: _.template("Must be a number between <%= min %> and <%= max %>", null, Settings.templateSettings),
            email: "Invalid email address",
            url: "Invalid URL",
            match: _.template('Must match field "<%= field %>"', null, Settings.templateSettings),
        },
        required: function (options) {
            options = _.extend({
                type: "required",
                message: this.errMessages.required,
            }, options);
            return function required(value) {
                options.value = value;
                var err = {
                    type: options.type,
                    message: _.isFunction(options.message) ? options.message(options) : options.message,
                }, $ = Backbone.$;
                if (value === null || value === undefined || value === false || value === "" || $.trim(value) === "")
                    return err;
            };
        },
        regexp: function (options) {
            if (!options.regexp)
                throw new Error('Missing required "regexp" option for "regexp" validator');
            options = _.extend({
                type: "regexp",
                match: true,
                message: this.errMessages.regexp,
            }, options);
            return function regexp(value) {
                options.value = value;
                var err = {
                    type: options.type,
                    message: _.isFunction(options.message) ? options.message(options) : options.message,
                };
                if (value === null || value === undefined || value === "")
                    return;
                if ("string" === typeof options.regexp)
                    options.regexp = new RegExp(options.regexp, options.flags);
                if (options.match ? !options.regexp.test(value) : options.regexp.test(value))
                    return err;
            };
        },
        number: function (options) {
            options = _.extend({
                type: "number",
                message: this.errMessages.number,
                regexp: /^[-+]?([0-9]*.[0-9]+|[0-9]+)$/,
            }, options);
            return this.regexp(options);
        },
        range: function (options) {
            options = _.extend({
                type: "range",
                message: this.errMessages.range,
                numberMessage: this.errMessages.number,
                min: 0,
                max: 100,
            }, options);
            return function range(value) {
                options.value = value;
                var err = {
                    type: options.type,
                    message: _.isFunction(options.message) ? options.message(options) : options.message,
                };
                if (value === null || value === undefined || value === "")
                    return;
                var numberCheck = this.number({ message: options.numberMessage })(value);
                if (numberCheck)
                    return numberCheck;
                var number = parseFloat(options.value);
                if (number < options.min || number > options.max)
                    return err;
            };
        },
        email: function (options) {
            options = _.extend({
                type: "email",
                message: this.errMessages.email,
                regexp: /^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/i,
            }, options);
            return this.regexp(options);
        },
        url: function (options) {
            options = _.extend({
                type: "url",
                message: this.errMessages.url,
                regexp: /^((http|https):\/\/)?(([A-Z0-9][A-Z0-9_\-]*)(\.[A-Z0-9][A-Z0-9_\-]*)+)(:(\d+))?\/?/i,
            }, options);
            return this.regexp(options);
        },
        match: function (options) {
            if (!options.field)
                throw new Error('Missing required "field" options for "match" validator');
            options = _.extend({
                type: "match",
                message: this.errMessages.match,
            }, options);
            return function match(value, attrs) {
                options.value = value;
                var err = {
                    type: options.type,
                    message: _.isFunction(options.message) ? options.message(options) : options.message,
                };
                if (value === null || value === undefined || value === "")
                    return;
                if (value !== attrs[options.field])
                    return err;
            };
        },
    };

    var Editor = (function (_super) {
        __extends(Editor, _super);
        function Editor(options) {
            var _this = _super.call(this, options) || this;
            _this.defaultValue = null;
            _this.hasFocus = false;
            var options = options || {};
            if (options.model) {
                if (!options.key)
                    throw new Error("Missing option: 'key'");
                _this.model = options.model;
                _this.value = _this.model.get(options.key);
            }
            else if (options.value !== undefined) {
                _this.value = options.value;
            }
            if (_this.value === undefined)
                _this.value = _this.defaultValue;
            _.extend(_this, _.pick(options, "key", "form"));
            var schema = (_this.schema = options.schema || {});
            _this.validators = options.validators || schema.validators;
            _this.$el.attr("id", _this.id);
            _this.$el.attr("name", _this.getName());
            if (schema.editorClass)
                _this.$el.addClass(schema.editorClass);
            if (schema.editorAttrs)
                _this.$el.attr(schema.editorAttrs);
            return _this;
        }
        Editor.prototype.getName = function () {
            var key = this.key || "";
            return key.replace(/\./g, "_");
        };
        Editor.prototype.getValue = function () {
            return this.value;
        };
        Editor.prototype.setValue = function (value) {
            this.value = value;
        };
        Editor.prototype.focus = function () {
            throw new Error("Not implemented");
        };
        Editor.prototype.blur = function () {
            throw new Error("Not implemented");
        };
        Editor.prototype.commit = function (options) {
            var error = this.validate();
            if (error)
                return error;
            this.listenTo(this.model, "invalid", function (model, e) {
                error = e;
            });
            this.model.set(this.key, this.getValue(), options);
            if (error)
                return error;
        };
        Editor.prototype.validate = function () {
            var error = null, value = this.getValue(), formValues = this.form ? this.form.getValue() : {}, validators = this.validators, getValidator = this.getValidator;
            if (validators) {
                _.every(validators, function (validator) {
                    error = getValidator(validator)(value, formValues);
                    return error ? false : true;
                });
            }
            return error;
        };
        Editor.prototype.trigger = function (event) {
            var args = [];
            for (var _i = 1; _i < arguments.length; _i++) {
                args[_i - 1] = arguments[_i];
            }
            if (event === "focus") {
                this.hasFocus = true;
            }
            else if (event === "blur") {
                this.hasFocus = false;
            }
            return Backbone.View.prototype.trigger.apply(this, args);
        };
        Editor.prototype.getValidator = function (validator) {
            var validators = Form$1.validators;
            if (_.isRegExp(validator)) {
                return validators.regexp({ regexp: validator });
            }
            if (_.isString(validator)) {
                if (!validators[validator])
                    throw new Error('Validator "' + validator + '" not found');
                return validators[validator]();
            }
            if (_.isFunction(validator))
                return validator;
            if (_.isObject(validator) && validator.type) {
                var config = validator;
                return validators[config.type](config);
            }
            throw new Error("Invalid validator: " + validator);
        };
        return Editor;
    }(Backbone.View));

    var Checkbox = (function (_super) {
        __extends(Checkbox, _super);
        function Checkbox(options) {
            var _this = _super.call(this, _.extend({
                tagName: "input",
                events: {
                    click: function (event) {
                        this.trigger("change", this);
                    },
                    focus: function (event) {
                        this.trigger("focus", this);
                    },
                    blur: function (event) {
                        this.trigger("blur", this);
                    },
                },
            }, options || {})) || this;
            _this.defaultValue = false;
            _this.$el.attr("type", "checkbox");
            return _this;
        }
        Checkbox.prototype.render = function () {
            this.setValue(this.value);
            return this;
        };
        Checkbox.prototype.getValue = function () {
            return this.$el.prop("checked");
        };
        Checkbox.prototype.setValue = function (value) {
            if (value) {
                this.$el.prop("checked", true);
            }
            else {
                this.$el.prop("checked", false);
            }
            this.value = !!value;
        };
        Checkbox.prototype.focus = function () {
            if (this.hasFocus)
                return;
            this.$el.focus();
        };
        Checkbox.prototype.blur = function () {
            if (!this.hasFocus)
                return;
            this.$el.blur();
        };
        return Checkbox;
    }(Editor));

    var DateEditor = (function (_super) {
        __extends(DateEditor, _super);
        function DateEditor(options) {
            var _this = this;
            options = options || {};
            _this = _super.call(this, _.extend({
                events: {
                    "change select": function () {
                        this.updateHidden();
                        this.trigger("change", this);
                    },
                    "focus select": function () {
                        if (this.hasFocus)
                            return;
                        this.trigger("focus", this);
                    },
                    "blur select": function () {
                        if (!this.hasFocus)
                            return;
                        var self = this;
                        setTimeout(function () {
                            if (self.$("select:focus")[0])
                                return;
                            self.trigger("blur", self);
                        }, 0);
                    },
                },
            }, options || {})) || this;
            _this.template = _.template("<div>\n            <select data-type=\"date\"><%= dates %></select>\n            <select data-type=\"month\"><%= months %></select>\n            <select data-type=\"year\"><%= years %></select>\n          </div>\n        ", null, Settings.templateSettings);
            _this.showMonthNames = true;
            _this.monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            var today = new Date();
            _this.options = _.extend({
                monthNames: _this.monthNames,
                showMonthNames: _this.showMonthNames,
            }, options);
            _this.schema = _.extend({
                yearStart: today.getFullYear() - 100,
                yearEnd: today.getFullYear(),
            }, options.schema || {});
            if (_this.value && !_.isDate(_this.value)) {
                _this.value = new Date(_this.value);
            }
            if (!_this.value) {
                var date = new Date();
                date.setSeconds(0);
                date.setMilliseconds(0);
                _this.value = date;
            }
            _this.template = options.template || _this.template;
            return _this;
        }
        DateEditor.prototype.render = function () {
            var options = this.options, schema = this.schema, $ = Backbone.$;
            var datesOptions = _.map(_.range(1, 32), function (date) {
                return '<option value="' + date + '">' + date + "</option>";
            });
            var monthsOptions = _.map(_.range(0, 12), function (month) {
                var value = options.showMonthNames ? options.monthNames[month] : month + 1;
                return '<option value="' + month + '">' + value + "</option>";
            });
            var yearRange = schema.yearStart < schema.yearEnd ? _.range(schema.yearStart, schema.yearEnd + 1) : _.range(schema.yearStart, schema.yearEnd - 1, -1);
            var yearsOptions = _.map(yearRange, function (year) {
                return '<option value="' + year + '">' + year + "</option>";
            });
            var $el = $($.trim(this.template({
                dates: datesOptions.join(""),
                months: monthsOptions.join(""),
                years: yearsOptions.join(""),
            })));
            this.$date = $el.find('[data-type="date"]');
            this.$month = $el.find('[data-type="month"]');
            this.$year = $el.find('[data-type="year"]');
            this.$hidden = $('<input type="hidden" name="' + this.key + '" />');
            $el.append(this.$hidden);
            this.setValue(this.value);
            this.setElement($el);
            this.$el.attr("id", this.id);
            this.$el.attr("name", this.getName());
            if (this.hasFocus)
                this.trigger("blur", this);
            return this;
        };
        DateEditor.prototype.getValue = function () {
            var year = this.$year.val().toString(), month = this.$month.val().toString(), date = this.$date.val().toString();
            if (!year || !month || !date)
                return null;
            return new Date(year, month, date);
        };
        DateEditor.prototype.setValue = function (date) {
            this.value = date;
            this.$date.val(date.getDate());
            this.$month.val(date.getMonth());
            this.$year.val(date.getFullYear());
            this.updateHidden();
        };
        DateEditor.prototype.focus = function () {
            if (this.hasFocus)
                return;
            this.$("select").first().focus();
        };
        DateEditor.prototype.blur = function () {
            if (!this.hasFocus)
                return;
            this.$("select:focus").blur();
        };
        DateEditor.prototype.updateHidden = function () {
            var val = this.getValue();
            if (_.isDate(val))
                val = val.toISOString();
            this.$hidden.val(val);
        };
        return DateEditor;
    }(Editor));

    var DateTime = (function (_super) {
        __extends(DateTime, _super);
        function DateTime(options) {
            var _this = this;
            options = options || {};
            _this = _super.call(this, _.extend({
                events: {
                    "change select": function () {
                        _this.updateHidden();
                        _this.trigger("change", _this);
                    },
                    "focus select": function () {
                        if (_this.hasFocus)
                            return;
                        _this.trigger("focus", _this);
                    },
                    "blur select": function () {
                        if (!_this.hasFocus)
                            return;
                        setTimeout(function () {
                            if (_this.$("select:focus")[0])
                                return;
                            _this.trigger("blur", self);
                        }, 0);
                    },
                },
            }, options)) || this;
            _this.template = _.template("\n    <div class=\"bbf-datetime\">\n      <div class=\"bbf-date-container\" data-date></div>\n      <select data-type=\"hour\"><%= hours %></select>\n      :\n      <select data-type=\"min\"><%= mins %></select>\n    </div>", null, Settings.templateSettings);
            _this.DateEditor = null;
            _this.options = _.extend({
                DateEditor: new DateEditor(options),
            }, options);
            _this.schema = _.extend({
                minsInterval: 15,
            }, options.schema || {});
            _this.dateEditor = new DateEditor(options);
            _this.value = _this.dateEditor.value;
            _this.template = options.template;
            return _this;
        }
        DateTime.prototype.render = function () {
            function pad(n) {
                return n < 10 ? "0" + n : n;
            }
            var schema = this.schema, $ = Backbone.$;
            var hoursOptions = _.map(_.range(0, 24), function (hour) {
                return '<option value="' + hour + '">' + pad(hour) + "</option>";
            });
            var minsOptions = _.map(_.range(0, 60, schema.minsInterval), function (min) {
                return '<option value="' + min + '">' + pad(min) + "</option>";
            });
            var $el = $($.trim(this.template({
                hours: hoursOptions.join(),
                mins: minsOptions.join(),
            })));
            $el.find("[data-date]").append(this.dateEditor.render().el);
            this.$hour = $el.find('select[data-type="hour"]');
            this.$min = $el.find('select[data-type="min"]');
            this.$hidden = $el.find('input[type="hidden"]');
            this.setValue(this.value);
            this.setElement($el);
            this.$el.attr("id", this.id);
            this.$el.attr("name", this.getName());
            if (this.hasFocus)
                this.trigger("blur", this);
            return this;
        };
        DateTime.prototype.getValue = function () {
            var date = this.dateEditor.getValue();
            var hour = this.$hour.val(), min = this.$min.val();
            if (!date || !hour || !min)
                return null;
            date.setHours(hour);
            date.setMinutes(min);
            return date;
        };
        DateTime.prototype.setValue = function (date) {
            if (!_.isDate(date))
                date = new Date(date);
            this.value = date;
            this.dateEditor.setValue(date);
            this.$hour.val(date.getHours());
            this.$min.val(date.getMinutes());
            this.updateHidden();
        };
        DateTime.prototype.focus = function () {
            if (this.hasFocus)
                return;
            this.$("select").first().focus();
        };
        DateTime.prototype.blur = function () {
            if (!this.hasFocus)
                return;
            this.$("select:focus").blur();
        };
        DateTime.prototype.updateHidden = function () {
            var val = this.getValue();
            if (_.isDate(val))
                val = val.toISOString();
            this.$hidden.val(val);
        };
        DateTime.prototype.remove = function () {
            this.dateEditor.remove();
            this.remove.call(this);
            return this;
        };
        return DateTime;
    }(Editor));

    var Text$1 = (function (_super) {
        __extends(Text, _super);
        function Text(options) {
            var _this = _super.call(this, _.extend({
                events: {
                    keyup: "determineChange",
                    keypress: function (event) {
                        setTimeout(function () {
                            _this.determineChange();
                        }, 0);
                    },
                    select: function (event) {
                        this.trigger("select", this);
                    },
                    focus: function (event) {
                        this.trigger("focus", this);
                    },
                    blur: function (event) {
                        this.trigger("blur", this);
                    },
                },
                tagName: "input",
            }, options || {})) || this;
            _this.defaultValue = "";
            _this.previousValue = "";
            var schema = _this.schema;
            var type = "text";
            if (schema && schema.editorAttrs && schema.editorAttrs.type)
                type = schema.editorAttrs.type;
            if (schema && schema.dataType)
                type = schema.dataType;
            _this.$el.attr("type", type);
            return _this;
        }
        Text.prototype.render = function () {
            this.setValue(this.value);
            return this;
        };
        Text.prototype.determineChange = function () {
            var currentValue = this.$el.val();
            var changed = currentValue !== this.previousValue;
            if (changed) {
                this.previousValue = currentValue;
                this.trigger("change", this);
            }
        };
        Text.prototype.getValue = function () {
            return this.$el.val();
        };
        Text.prototype.setValue = function (value) {
            this.value = value;
            this.$el.val(value);
            this.previousValue = this.$el.val();
        };
        Text.prototype.focus = function () {
            if (this.hasFocus)
                return;
            this.$el.focus();
        };
        Text.prototype.blur = function () {
            if (!this.hasFocus)
                return;
            this.$el.blur();
        };
        Text.prototype.select = function () {
            this.$el.select();
        };
        return Text;
    }(Editor));

    var Hidden = (function (_super) {
        __extends(Hidden, _super);
        function Hidden(options) {
            var _this = _super.call(this, _.extend({
                noField: true,
            }, options)) || this;
            _this.defaultValue = "";
            _this.noField = true;
            _this.$el.attr("type", "hidden");
            return _this;
        }
        Hidden.prototype.focus = function () { };
        Hidden.prototype.blur = function () { };
        return Hidden;
    }(Text$1));

    var Number = (function (_super) {
        __extends(Number, _super);
        function Number(options) {
            var _this = _super.call(this, options) || this;
            _this.defaultValue = 0;
            _this.events = _.extend({}, _this.events, {
                keypress: "onKeyPress",
                change: "onKeyPress",
                input: "determineChange",
            });
            var schema = _this.schema;
            _this.$el.attr("type", "number");
            if (!schema || !schema.editorAttrs || !schema.editorAttrs.step) {
                _this.$el.attr("step", "any");
            }
            return _this;
        }
        Number.prototype.onKeyPress = function (event) {
            var _this = this;
            var delayedDetermineChange = function () {
                setTimeout(function () {
                    _this.determineChange();
                }, 0);
            };
            if (event.charCode === 0) {
                delayedDetermineChange();
                return;
            }
            var newVal = this.$el.val();
            if (event.charCode != undefined) {
                newVal = newVal + String.fromCharCode(event.charCode);
            }
            var numeric = /^-?[0-9]*\.?[0-9]*$/.test(newVal.toString());
            if (numeric) {
                delayedDetermineChange();
            }
            else {
                event.preventDefault();
            }
        };
        Number.prototype.getValue = function () {
            var value = this.$el.val();
            return value === "" ? null : parseFloat(value.toString());
        };
        Number.prototype.setValue = function (value) {
            value = (function () {
                if (_.isNumber(value))
                    return value;
                if (_.isString(value) && value !== "")
                    return parseFloat(value);
                return null;
            })();
            if (_.isNaN(value))
                value = null;
            this.value = value;
            _super.prototype.setValue.call(this, value);
        };
        return Number;
    }(Text$1));

    var ObjectEditor = (function (_super) {
        __extends(ObjectEditor, _super);
        function ObjectEditor(options) {
            var _this = _super.call(this, options) || this;
            _this.hasNestedForm = true;
            _this.value = {};
            if (!_this.form)
                throw new Error('Missing required option "form"');
            if (!_this.schema.subSchema)
                throw new Error("Missing required 'schema.subSchema' option for Object editor");
            return _this;
        }
        ObjectEditor.prototype.render = function () {
            var NestedForm = this.form.constructor;
            this.nestedForm = new NestedForm({
                schema: this.schema.subSchema,
                data: this.value,
                idPrefix: this.id + "_",
                Field: NestedForm.NestedField,
            });
            this._observeFormEvents();
            this.$el.html(this.nestedForm.render().el);
            if (this.hasFocus)
                this.trigger("blur", this);
            return this;
        };
        ObjectEditor.prototype.getValue = function () {
            if (this.nestedForm)
                return this.nestedForm.getValue();
            return this.value;
        };
        ObjectEditor.prototype.setValue = function (value) {
            this.value = value;
            this.render();
        };
        ObjectEditor.prototype.focus = function () {
            if (this.hasFocus)
                return;
            this.nestedForm.focus();
        };
        ObjectEditor.prototype.blur = function () {
            if (!this.hasFocus)
                return;
            this.nestedForm.blur();
        };
        ObjectEditor.prototype.remove = function () {
            this.nestedForm.remove();
            Backbone.View.prototype.remove.call(this);
            return this;
        };
        ObjectEditor.prototype.validate = function () {
            var errors = _.extend({}, _super.prototype.validate.call(this), this.nestedForm.validate());
            return _.isEmpty(errors) ? false : errors;
        };
        ObjectEditor.prototype._observeFormEvents = function () {
            if (!this.nestedForm)
                return;
            this.nestedForm.on("all", function () {
                var args = _.toArray(arguments);
                args[1] = this;
                this.trigger.apply(this, args);
            }, this);
        };
        return ObjectEditor;
    }(Editor));

    var Select = (function (_super) {
        __extends(Select, _super);
        function Select(options) {
            var _this = _super.call(this, _.extend({
                events: {
                    keyup: "determineChange",
                    keypress: function (event) {
                        setTimeout(function () {
                            _this.determineChange();
                        }, 0);
                    },
                    change: function (event) {
                        _this.trigger("change", _this);
                    },
                    focus: function (event) {
                        _this.trigger("focus", _this);
                    },
                    blur: function (event) {
                        _this.trigger("blur", _this);
                    },
                },
                tagName: "select",
            }, options || {})) || this;
            _this.previousValue = "";
            if (!_this.schema || !_this.schema.options)
                throw new Error("Missing required 'schema.options'");
            return _this;
        }
        Select.prototype.render = function () {
            this.setOptions(this.schema.options);
            return this;
        };
        Select.prototype.setOptions = function (options) {
            var self = this;
            if (options instanceof Backbone.Collection) {
                var collection = options;
                if (collection.length > 0) {
                    this.renderOptions(options);
                }
                else {
                    collection.fetch({
                        success: function (collection) {
                            self.renderOptions(options);
                        },
                    });
                }
            }
            else if (_.isFunction(options)) {
                options(function (result) {
                    self.renderOptions(result);
                }, self);
            }
            else {
                this.renderOptions(options);
            }
        };
        Select.prototype.renderOptions = function (options) {
            var $select = this.$el, html;
            html = this._getOptionsHtml(options);
            $select.html(html);
            this.setValue(this.value);
        };
        Select.prototype._getOptionsHtml = function (options) {
            var html;
            if (_.isString(options)) {
                html = options;
            }
            else if (_.isArray(options)) {
                html = this._arrayToHtml(options);
            }
            else if (options instanceof Backbone.Collection) {
                html = this._collectionToHtml(options);
            }
            else if (_.isFunction(options)) {
                var newOptions;
                options(function (opts) {
                    newOptions = opts;
                }, this);
                html = this._getOptionsHtml(newOptions);
            }
            else {
                html = this._objectToHtml(options);
            }
            return html;
        };
        Select.prototype.determineChange = function () {
            var currentValue = this.getValue();
            var changed = currentValue !== this.previousValue;
            if (changed) {
                this.previousValue = currentValue;
                this.trigger("change", this);
            }
        };
        Select.prototype.getValue = function () {
            return this.$el.val();
        };
        Select.prototype.setValue = function (value) {
            this.value = value;
            this.$el.val(value);
        };
        Select.prototype.focus = function () {
            if (this.hasFocus)
                return;
            this.$el.focus();
        };
        Select.prototype.blur = function () {
            if (!this.hasFocus)
                return;
            this.$el.blur();
        };
        Select.prototype._collectionToHtml = function (collection) {
            var array = [];
            collection.each(function (model) {
                array.push({ val: model.id, label: model.toString() });
            });
            var html = this._arrayToHtml(array);
            return html;
        };
        Select.prototype._objectToHtml = function (obj) {
            var array = [];
            for (var key in obj) {
                if (obj.hasOwnProperty(key)) {
                    array.push({ val: key, label: obj[key] });
                }
            }
            var html = this._arrayToHtml(array);
            return html;
        };
        Select.prototype._arrayToHtml = function (array) {
            var $ = Backbone.$;
            var html = $();
            _.each(array, _.bind(function (option) {
                if (_.isObject(option)) {
                    if (option.group) {
                        var optgroup = $("<optgroup>").attr("label", option.group).html(this._getOptionsHtml(option.options));
                        html = html.add(optgroup);
                    }
                    else {
                        var val = option.val || option.val === 0 ? option.val : "";
                        html = html.add($("<option>").val(val).text(option.label));
                    }
                }
                else {
                    html = html.add($("<option>").text(option));
                }
            }, this));
            return html;
        };
        return Select;
    }(Editor));

    var Radio = (function (_super) {
        __extends(Radio, _super);
        function Radio() {
            var _this = _super !== null && _super.apply(this, arguments) || this;
            _this.tagName = "ul";
            _this.template = _.template("<% _.each(items, function(item) { %>\n      <li>\n        <input type=\"radio\" name=\"<%= item.name %>\" value=\"<%- item.value %>\" id=\"<%= item.id %>\" />\n        <label for=\"<%= item.id %>\"><% if (item.labelHTML){ %><%= item.labelHTML %><% }else{ %><%- item.label %><% } %></label>\n      </li>\n    <% }); %>\n  ", null, Settings.templateSettings);
            _this.events = {
                "change input[type=radio]": function () {
                    this.trigger("change", this);
                },
                "focus input[type=radio]": function () {
                    if (this.hasFocus)
                        return;
                    this.trigger("focus", this);
                },
                "blur input[type=radio]": function () {
                    if (!this.hasFocus)
                        return;
                    var self = this;
                    setTimeout(function () {
                        if (self.$("input[type=radio]:focus")[0])
                            return;
                        self.trigger("blur", self);
                    }, 0);
                },
            };
            return _this;
        }
        Radio.prototype.getTemplate = function () {
            return this.schema.template;
        };
        Radio.prototype.getValue = function () {
            return this.$("input[type=radio]:checked").val();
        };
        Radio.prototype.setValue = function (value) {
            this.value = value;
            this.$("input[type=radio]").val([value]);
        };
        Radio.prototype.focus = function () {
            if (this.hasFocus)
                return;
            var checked = this.$("input[type=radio]:checked");
            if (checked[0]) {
                checked.focus();
                return;
            }
            this.$("input[type=radio]").first().focus();
        };
        Radio.prototype.blur = function () {
            if (!this.hasFocus)
                return;
            this.$("input[type=radio]:focus").blur();
        };
        Radio.prototype._arrayToHtml = function (array) {
            var self = this;
            var template = this.getTemplate(), name = self.getName(), id = self.id;
            var items = _.map(array, function (option, index) {
                var item = {
                    name: name,
                    id: id + "-" + index,
                };
                if (_.isObject(option)) {
                    item.value = option.val || option.val === 0 ? option.val : "";
                    item.label = option.label;
                    item.labelHTML = option.labelHTML;
                }
                else {
                    item.value = option;
                    item.label = option;
                }
                return item;
            });
            return template({ items: items });
        };
        return Radio;
    }(Select));

    var TextArea = (function (_super) {
        __extends(TextArea, _super);
        function TextArea(options) {
            return _super.call(this, _.extend({
                tagName: "textarea",
            }, options || {})) || this;
        }
        return TextArea;
    }(Text));

    var Field = (function (_super) {
        __extends(Field, _super);
        function Field(options) {
            var _this = this;
            options = options || {};
            _this = _super.call(this, options) || this;
            _this.template = _.template("<div>\n            <label for=\"<%= editorId %>\">\n              <% if (titleHTML){ %><%= titleHTML %>\n              <% } else { %><%- title %><% } %>\n            </label>\n            <div>\n              <span data-editor></span>\n              <div data-error></div>\n              <div><%= help %></div>\n            </div>\n          </div>", null, Settings.templateSettings);
            _this.errorClassName = "error";
            _this.createSchema = function (schema) {
                if (_.isString(schema))
                    schema = { type: schema };
                schema = _.extend({
                    type: "Text",
                    title: this.createTitle(),
                }, schema);
                if (_.isString(schema.type)) {
                    switch (schema.type) {
                        case "Text":
                            schema.type = Text$1;
                            break;
                        case "TextArea":
                            schema.type = TextArea;
                            break;
                        case "Select":
                            schema.type = Select;
                            break;
                        case "Checkbox":
                            schema.type = Checkbox;
                            break;
                        case "Radio":
                            schema.type = Radio;
                            break;
                        case "Hidden":
                            schema.type = Hidden;
                            break;
                        case "Object":
                            schema.type = ObjectEditor;
                            break;
                        case "Number":
                            schema.type = Number;
                            break;
                        case "Date":
                            schema.type = DateEditor;
                            break;
                        case "DateTime":
                            schema.type = DateTime;
                            break;
                        default:
                            schema.type = Text$1;
                            break;
                    }
                }
                return schema;
            };
            _this.createEditor = function () {
                var options = _.extend(_.pick(this, "schema", "form", "key", "model", "value"), { id: this.createEditorId() });
                var constructorFn = this.schema.type;
                return new constructorFn(options);
            };
            _this.createEditorId = function () {
                var prefix = this.idPrefix, id = this.key;
                id = id.replace(/\./g, "_");
                if (_.isString(prefix) || _.isNumber(prefix))
                    return prefix + id;
                if (_.isNull(prefix))
                    return id;
                if (this.model)
                    return this.model.cid + "_" + id;
                return id;
            };
            _this.createTitle = function () {
                var str = this.key;
                str = str.replace(/([A-Z])/g, " $1");
                str = str.replace(/^./, function (str) {
                    return str.toUpperCase();
                });
                return str;
            };
            _this.templateData = function () {
                var schema = this.schema;
                return {
                    help: schema.help || "",
                    title: schema.title,
                    titleHTML: schema.titleHTML,
                    fieldAttrs: schema.fieldAttrs,
                    editorAttrs: schema.editorAttrs,
                    key: this.key,
                    editorId: this.editor.id,
                };
            };
            _this.render = function () {
                var schema = this.schema, editor = this.editor, $ = Backbone.$;
                if (this.editor.noField === true) {
                    return this.setElement(editor.render().el);
                }
                var $field = $($.trim(this.template(_.result(this, "templateData"))));
                if (schema.fieldClass)
                    $field.addClass(schema.fieldClass);
                if (schema.fieldAttrs)
                    $field.attr(schema.fieldAttrs);
                $field
                    .find("[data-editor]")
                    .add($field)
                    .each(function (i, el) {
                    var $container = $(el), selection = $container.attr("data-editor");
                    if (_.isUndefined(selection))
                        return;
                    $container.append(editor.render().el);
                });
                this.setElement($field);
                return this;
            };
            _this._isInput = function ($input) {
                return $input.is("input") || $input.is("textarea") || $input.is("select") || $input.is("button");
            };
            _this._getInputs = function ($el) {
                return $el.find("input,textarea,select,button");
            };
            _this.disable = function () {
                if (_.isFunction(this.editor.disable)) {
                    this.editor.disable();
                }
                else {
                    var $input = this.editor.$el;
                    $input = this._isInput($input) ? $input : this._getInputs($input);
                    $input.attr("disabled", true);
                }
            };
            _this.enable = function () {
                if (_.isFunction(this.editor.enable)) {
                    this.editor.enable();
                }
                else {
                    var $input = this.editor.$el;
                    $input = this._isInput($input) ? $input : this._getInputs($input);
                    $input.attr("disabled", false);
                }
            };
            _this.validate = function () {
                var error = this.editor.validate();
                if (error) {
                    this.setError(error.message);
                }
                else {
                    this.clearError();
                }
                return error;
            };
            _this.setError = function (msg) {
                if (this.editor.hasNestedForm)
                    return;
                this.$el.addClass(this.errorClassName);
                this.$("[data-error]").last().html(msg);
            };
            _this.clearError = function () {
                this.$el.removeClass(this.errorClassName);
                this.$("[data-error]").empty();
            };
            _this.commit = function () {
                return this.editor.commit();
            };
            _this.getValue = function () {
                return this.editor.getValue();
            };
            _this.setValue = function (value) {
                this.editor.setValue(value);
            };
            _this.focus = function () {
                this.editor.focus();
            };
            _this.blur = function () {
                this.editor.blur();
            };
            _this.remove = function () {
                this.editor.remove();
                Backbone.View.prototype.remove.call(this);
                return this;
            };
            _.extend(_this, _.pick(options, "form", "key", "model", "value", "idPrefix"));
            var schema = (_this.schema = _this.createSchema(options.schema));
            _this.template = options.template || schema.template || _this.template;
            _this.errorClassName = options.errorClassName || schema.errorClassName || _this.errorClassName;
            _this.editor = _this.createEditor();
            return _this;
        }
        return Field;
    }(Backbone.View));

    var Form = (function (_super) {
        __extends(Form, _super);
        function Form(options) {
            var _this = _super.call(this, options) || this;
            _this.Fieldset = null;
            _this.createFieldset = function (schema) {
                var options = {
                    schema: schema,
                    fields: _this.fields,
                    legend: schema.legend || null,
                };
                return new Fieldset(options);
            };
            _this.createField = function (key, schema) {
                var options = {
                    form: _this,
                    key: key,
                    schema: schema,
                    idPrefix: _this.idPrefix,
                };
                if (_this.model) {
                    options.model = _this.model;
                }
                else if (_this.data) {
                    options.value = _this.data[key];
                }
                else {
                    options.value = undefined;
                }
                var field = new Field(options);
                _this.listenTo(field.editor, "all", function (e) { return _this.handleEditorEvent(e, field.editor); });
                return field;
            };
            _this.handleEditorEvent = function (event, editor) {
                var formEvent = editor.key + ":" + event;
                _this.trigger.call(_this, formEvent, _this, editor, Array.prototype.slice.call([event, editor], 2));
                switch (event) {
                    case "change":
                        _this.trigger("change", _this);
                        break;
                    case "focus":
                        if (!_this.hasFocus)
                            _this.trigger("focus", _this);
                        break;
                    case "blur":
                        if (_this.hasFocus) {
                            setTimeout(function () {
                                var focusedField = _.find(_this.fields, function (field) {
                                    return field.editor.hasFocus;
                                });
                                if (!focusedField)
                                    _this.trigger("blur", _this);
                            }, 0);
                        }
                        break;
                }
            };
            _this.templateData = function () {
                var options = _this.options;
                return {
                    submitButton: options.submitButton,
                };
            };
            _this.render = function () {
                var fields = _this.fields, $ = Backbone.$;
                var $form = $($.trim(_this.template(_.result(_this, "templateData"))));
                $form
                    .find("[data-editors]")
                    .add($form)
                    .each(function (i, el) {
                    var $container = $(el), selection = $container.attr("data-editors");
                    if (_.isUndefined(selection))
                        return;
                    var keys = selection == "*" ? _this.selectedFields || _.keys(fields) : selection.split(",");
                    _.each(keys, function (key) {
                        var field = fields[key];
                        $container.append(field.editor.render().el);
                    });
                });
                $form
                    .find("[data-fields]")
                    .add($form)
                    .each(function (i, el) {
                    var $container = $(el), selection = $container.attr("data-fields");
                    if (_.isUndefined(selection))
                        return;
                    var keys = selection == "*" ? _this.selectedFields || _.keys(fields) : selection.split(",");
                    _.each(keys, function (key) {
                        var field = fields[key];
                        $container.append(field.render().el);
                    });
                });
                $form
                    .find("[data-fieldsets]")
                    .add($form)
                    .each(function (i, el) {
                    var $container = $(el), selection = $container.attr("data-fieldsets");
                    if (_.isUndefined(selection))
                        return;
                    _.each(_this.fieldsets, function (fieldset) {
                        $container.append(fieldset.render().el);
                    });
                });
                _this.setElement($form);
                $form.addClass(_this.className);
                if (_this.attributes) {
                    $form.attr(_this.attributes);
                }
                return _this;
            };
            _this.validate = function (options) {
                var fields = _this.fields, model = _this.model, errors = {};
                options = options || {};
                _.each(fields, function (field) {
                    var error = field.validate();
                    if (error) {
                        errors[field.key] = error;
                    }
                });
                if (!options.skipModelValidate && model && model.validate) {
                    var modelErrors = model.validate(_this.getValue());
                    if (modelErrors) {
                        var isDictionary = _.isObject(modelErrors) && !_.isArray(modelErrors);
                        if (!isDictionary) {
                            errors._others = errors._others || [];
                            errors._others.push(modelErrors);
                        }
                        if (isDictionary) {
                            _.each(modelErrors, function (val, key) {
                                if (fields[key] && !errors[key]) {
                                    fields[key].setError(val);
                                    errors[key] = val;
                                }
                                else {
                                    errors._others = errors._others || [];
                                    var tmpErr = {};
                                    tmpErr[key] = val;
                                    errors._others.push(tmpErr);
                                }
                            });
                        }
                    }
                }
                return _.isEmpty(errors) ? null : errors;
            };
            _this.commit = function (options) {
                options = options || {};
                var validateOptions = {
                    skipModelValidate: !options.validate,
                };
                var errors = _this.validate(validateOptions);
                if (errors)
                    return errors;
                var modelError;
                var setOptions = _.extend({
                    error: function (model, e) {
                        modelError = e;
                    },
                }, options);
                _this.model.set(_this.getValue(), setOptions);
                if (modelError)
                    return modelError;
            };
            _this.getValue = function (key) {
                if (key)
                    return _this.fields[key].getValue();
                var values = {};
                _.each(_this.fields, function (field) {
                    values[field.key] = field.getValue();
                });
                return values;
            };
            _this.setValue = function (prop, val) {
                var data = {};
                if (typeof prop === "string") {
                    data[prop] = val;
                }
                else {
                    data = prop;
                }
                var key;
                for (key in _this.schema) {
                    if (data[key] !== undefined) {
                        _this.fields[key].setValue(data[key]);
                    }
                }
            };
            _this.getEditor = function (key) {
                var field = _this.fields[key];
                if (!field)
                    throw new Error("Field not found: " + key);
                return field.editor;
            };
            _this.focus = function () {
                if (_this.hasFocus)
                    return;
                var fieldset = _this.fieldsets[0], field = fieldset.getFieldAt(0);
                if (!field)
                    return;
                field.editor.focus();
            };
            _this.blur = function () {
                if (!_this.hasFocus)
                    return;
                var focusedField = _.find(_this.fields, function (field) {
                    return field.editor.hasFocus;
                });
                if (focusedField)
                    focusedField.editor.blur();
            };
            _this.trigger = function (event) {
                var args = [];
                for (var _i = 1; _i < arguments.length; _i++) {
                    args[_i - 1] = arguments[_i];
                }
                if (event === "focus") {
                    _this.hasFocus = true;
                }
                else if (event === "blur") {
                    _this.hasFocus = false;
                }
                return Backbone.View.prototype.trigger.apply(_this, args);
            };
            _this.remove = function () {
                _.each(_this.fieldsets, function (fieldset) {
                    fieldset.remove();
                });
                _.each(_this.fields, function (field) {
                    field.remove();
                });
                return Backbone.View.prototype.remove.apply(_this, []);
            };
            _this.hasFocus = false;
            _this.events = {
                submit: function (event) {
                    _this.trigger("submit", event);
                },
            };
            options = _this.options = _.extend({
                submitButton: false,
            }, options);
            var schema = (_this.schema = (function () {
                if (options.schema)
                    return _.result(options, "schema");
                var model = options.model;
                if (model && model.schema)
                    return _.result(model, "schema");
                if (_this.schema)
                    return _.result(_this, "schema");
                return {};
            })());
            _.extend(_this, _.pick(options, "model", "data", "idPrefix", "templateData"));
            var constructor = _this.constructor;
            _this.template = options.template || _this.template || constructor.template;
            _this.Fieldset = options.Fieldset || _this.Fieldset || constructor.Fieldset;
            _this.Field = options.Field || _this.Field || constructor.Field;
            _this.NestedField = options.NestedField || _this.NestedField || constructor.NestedField;
            var selectedFields = (_this.selectedFields = options.fields || _.keys(schema));
            var fields = (_this.fields = {});
            _.each(selectedFields, _.bind(function (key) {
                var fieldSchema = schema[key];
                fields[key] = this.createField(key, fieldSchema);
            }, _this));
            var fieldsetSchema = options.fieldsets || _.result(_this, "fieldsets") || _.result(_this.model, "fieldsets") || [selectedFields];
            _this.fieldsets = [];
            _.each(fieldsetSchema, _.bind(function (itemSchema) {
                this.fieldsets.push(this.createFieldset(itemSchema));
            }, _this));
            return _this;
        }
        var _a;
        _a = Form;
        Form.template = _.template("<form>\n        <div data-fieldsets></div>\n        <% if (submitButton) { %>\n            <button type=\"submit\"><%= submitButton %></button>\n        <% } %>\n        </form>", null, _a.templateSettings);
        Form.validators = Validators;
        return Form;
    }(Backbone.View));
    var Form$1 = Form;

    var User = (function (_super) {
        __extends(User, _super);
        function User() {
            var _this = _super !== null && _super.apply(this, arguments) || this;
            _this.schema = {
                title: { type: 'Select', options: ['Mr', 'Mrs', 'Ms'] },
                name: 'Text',
                email: { validators: ['required', 'email'] },
                birthday: 'Date',
                password: 'Password',
                notes: { type: 'List', itemType: 'Text' }
            };
            return _this;
        }
        return User;
    }(Backbone.Model));
    jQuery(function () {
        var user = new User();
        var form = new Form$1({
            model: user
        }).render();
        jQuery('#post-body').append(form.el);
    });

})();

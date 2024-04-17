var __extends = (this && this.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (Object.prototype.hasOwnProperty.call(b, p)) d[p] = b[p]; };
        return extendStatics(d, b);
    };
    return function (d, b) {
        if (typeof b !== "function" && b !== null)
            throw new TypeError("Class extends value " + String(b) + " is not a constructor or null");
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
var Template = (function () {
    function Template() {
    }
    Template.renderField = function (index, field) {
        var properties = field.properties;
        var li = document.createElement("li");
        li.classList.add("form-item-".concat(index));
        var header = document.createElement("div");
        header.classList.add("menu-item-bar");
        header.innerHTML = "<div class=\"menu-item-handle ui-sortable-handle\">\n            <label class=\"item-title\" for=\"menu-item-checkbox-141\">\n                <span class=\"menu-item-title\">".concat("No Label", "</span>\n            </label>\n            <span class=\"item-controls\">\n                <span class=\"item-type\">").concat(field.label, "</span>\n                <span class=\"item-order\">\n                    <a href=\"#\" class=\"item-move-up\" aria-label=\"Move up\">\u2191</a> |\n                    <a href=\"#\" class=\"item-move-down\" aria-label=\"Move down\">\u2193</a> \n                </span>\n                <a class=\"item-edit\" id=\"edit-141\" href=\"#\"><span class=\"screen-reader-text\">Edit</span></a> \n            </span>\n        </div>");
        li.appendChild(header);
        var settings = document.createElement("div");
        settings.classList.add("menu-item-settings", "wp-clearfix");
        settings.style.display = "none";
        settings.innerHTML = "<div class=\"basic-settings\">".concat(Template.renderSection(properties.basic), "</div>\n        <div class=\"advanced-settings\">").concat(Template.renderSection(properties.advanced), "</div>\n        <hr/>\n        <div class=\"menu-item-actions description-wide submitbox\">\n            <a class=\"item-delete submitdelete deletion\" href=\"#\">Remove</a>\n        </div>");
        li.appendChild(settings);
        header.querySelector(".item-edit").addEventListener("click", function (e) {
            e.preventDefault();
            settings.style.display = settings.style.display === "none" ? "block" : "none";
        });
        return li;
    };
    Template.renderSection = function (section) {
        if (!section.fields || section.fields.length === 0)
            return "";
        var fieldMap = Object.keys(section.fields).map(function (key) {
            var _field = section.fields[key];
            return "<p class=\"description description-wide\">\n                <label for=\"edit-menu-item-title-141\">\n                    ".concat(_field.label, "\n                    <br>\n                    <input type=\"").concat(_field.type, "\" id=\"edit-menu-item-title-141\" class=\"widefat edit-menu-item-title\" name=\"").concat(_field.name, "\" value=\"\">\n                    <small>").concat(_field.help, "</small>\n                </label>\n            </p>");
        });
        return "\n            <h3>".concat(section.label, "</h3>\n            ").concat(fieldMap.join(""), "\n            ");
    };
    return Template;
}());
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
        var destElm = droppableView.$el;
        console.log("DID DROP", type, srcElm, destElm);
        droppableView.add(type, {});
        destElm.append(droppableView.render().el);
    };
    return DraggableView;
}(Backbone.View));
var DroppableView = (function (_super) {
    __extends(DroppableView, _super);
    function DroppableView() {
        var _this = _super !== null && _super.apply(this, arguments) || this;
        _this.fields = [];
        return _this;
    }
    DroppableView.prototype.droppable = function (options) {
        if (options === void 0) { options = {}; }
        this.$el.data("view", this);
        this.$el.data("collection", this.collection);
        this.collection.view = this;
        this.fields = [];
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
        this.$el.append(fieldView.render().el);
    };
    return DroppableView;
}(Backbone.View));
var FieldView = (function (_super) {
    __extends(FieldView, _super);
    function FieldView(index, type, data) {
        var _this = _super.call(this) || this;
        _this.template = function (data) {
            var type = data.type;
            var field = ajaxyFormsBuilder.fields[type];
            return Template.renderField(_this.index, field);
        };
        _this.type = type;
        _this.data = data;
        _this.index = index;
        return _this;
    }
    FieldView.prototype.render = function () {
        this.$el.html(this.template(new FieldProperties(this.type, this.data)));
        return this;
    };
    return FieldView;
}(Backbone.View));
var FieldProperties = (function (_super) {
    __extends(FieldProperties, _super);
    function FieldProperties(type, data) {
        var _this = _super.call(this, data) || this;
        _this.type = type;
        _this.data = data;
        return _this;
    }
    return FieldProperties;
}(Backbone.Collection));
var FieldProperty = (function (_super) {
    __extends(FieldProperty, _super);
    function FieldProperty() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    FieldProperty.prototype.initialize = function () { };
    return FieldProperty;
}(Backbone.Model));
jQuery(function () {
    var droppable = document.querySelector(".af-form-wrap .droppable");
    var dropCollection = new DroppableCollection();
    var dropView = new DroppableView({
        collection: dropCollection,
        el: jQuery(droppable),
    });
    dropView.droppable();
    var dragModels = [];
    var dragViews = [];
    var draggables = document.querySelectorAll(".af-fields > li.draggable");
    [].forEach.call(draggables, function (draggable, i) {
        var dragModel = new DraggableModel({
            type: draggable.getAttribute("data-type"),
        });
        dragModels.push(dragModel);
        dropCollection.add(dragModel);
        var dragView = new DraggableView({
            model: dragModel,
            el: jQuery(draggable),
        });
        dragView.draggable({
            makeClone: true,
            canDropClass: "can-drop",
        });
        dragView.on("drag", function () {
            console.log("example: DRAG");
        });
        dragViews.push(dragView);
    });
    window.dropCollection = dropCollection;
});

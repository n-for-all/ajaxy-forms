"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
var plugin_node_resolve_1 = __importDefault(require("@rollup/plugin-node-resolve"));
var plugin_typescript_1 = __importDefault(require("@rollup/plugin-typescript"));
var plugin_replace_1 = __importDefault(require("@rollup/plugin-replace"));
var path_1 = __importDefault(require("path"));
exports.default = [
    {
        input: path_1.default.resolve(__dirname, "./script.ts"),
        output: {
            file: path_1.default.resolve(__dirname, "../js/script.js"),
            format: "iife",
        },
        plugins: [
            (0, plugin_node_resolve_1.default)(),
            (0, plugin_typescript_1.default)({
                exclude: path_1.default.resolve(__dirname, "./node_modules"),
                tsconfig: false,
            }),
            (0, plugin_replace_1.default)({
                "process.env.NODE_ENV": JSON.stringify("production"),
            }),
        ],
    },
    {
        input: path_1.default.resolve(__dirname, "./builder.ts"),
        output: {
            file: path_1.default.resolve(__dirname, "../js/builder.js"),
            format: "iife",
            globals: {
                backbone: 'Backbone',
                underscore: '_'
            }
        },
        plugins: [
            (0, plugin_node_resolve_1.default)(),
            (0, plugin_typescript_1.default)({
                exclude: path_1.default.resolve(__dirname, "./node_modules"),
                tsconfig: false,
            }),
            (0, plugin_replace_1.default)({
                "process.env.NODE_ENV": JSON.stringify("production"),
            }),
        ],
    },
];

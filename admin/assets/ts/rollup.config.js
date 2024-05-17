import resolve from "@rollup/plugin-node-resolve";
import typescript from "@rollup/plugin-typescript";
import replace from "@rollup/plugin-replace";
import path from "path";

export default [
	{
		input: path.resolve(__dirname, "./script.ts"),
		output: {
			file: path.resolve(__dirname, "../js/script.js"),
			format: "iife",
		},
		plugins: [
			resolve(),
			typescript({
				exclude: path.resolve(__dirname, "./node_modules"),
                include: [path.resolve(__dirname, "./script.ts")],
				tsconfig: false,
			}),
			replace({
				// If you would like DEV messages, specify 'development'
				// Otherwise use 'production'
                preventAssignment: true,
				"process.env.NODE_ENV": JSON.stringify("production"),
			}),
		],
	},
	{
		input: path.resolve(__dirname, "./builder.ts"),
		output: {
			file: path.resolve(__dirname, "../js/builder.js"),
			format: "iife",
			globals: {
				backbone: "Backbone",
				underscore: "_",
			},
		},
		plugins: [
			resolve(),
			typescript({
				exclude: path.resolve(__dirname, "./node_modules"),
			}),
			replace({
				// If you would like DEV messages, specify 'development'
				// Otherwise use 'production'
                preventAssignment: true,
				"process.env.NODE_ENV": JSON.stringify("production"),
			}),
		],
	},
	{
		input: path.resolve(__dirname, "./actions.ts"),
		output: {
			file: path.resolve(__dirname, "../js/actions.js"),
			format: "iife",
			globals: {
				backbone: "Backbone",
				underscore: "_",
			},
		},
		plugins: [
			resolve(),
			typescript({
				exclude: path.resolve(__dirname, "./node_modules"),
			}),
			replace({
				// If you would like DEV messages, specify 'development'
				// Otherwise use 'production'
                preventAssignment: true,
				"process.env.NODE_ENV": JSON.stringify("production"),
			}),
		],
	},
];

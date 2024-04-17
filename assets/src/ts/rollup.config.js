import resolve from "@rollup/plugin-node-resolve";
import typescript from "@rollup/plugin-typescript";
import replace from "@rollup/plugin-replace";
import path from "path";

export default [
	{
		input: path.resolve(__dirname, "./script.ts").replace(" ", " "),
		output: {
			file: path.resolve(__dirname, "../../js/script.js"),
			format: "iife",
		},
		plugins: [
			resolve(),
			typescript({
				exclude: path.resolve(__dirname, "./node_modules"),
				tsconfig: false,
			}),
			replace({
				// If you would like DEV messages, specify 'development'
				// Otherwise use 'production'
				"process.env.NODE_ENV": JSON.stringify("production"),
			}),
		],
	},
];

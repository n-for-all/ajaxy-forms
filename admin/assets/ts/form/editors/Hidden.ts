/**
 * Hidden editor
 */

import Text from "./Text";

class Hidden extends Text {
	defaultValue = "";

	noField = true;

	constructor(options) {
		super(
			_.extend(
				{
					noField: true,
				},
				options
			)
		);

		this.$el.attr("type", "hidden");
	}

	focus() {}

	blur() {}
}

export default Hidden;

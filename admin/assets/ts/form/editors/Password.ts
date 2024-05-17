/**
 * Password editor
 */

import Text from "./Text";

class Password extends Text {
	constructor(options) {
		super(options);

		this.$el.attr("type", "password");
	}
}

export default Password;

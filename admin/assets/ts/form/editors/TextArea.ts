/**
 * TextArea editor
 */

class TextArea extends Text {
	constructor(options) {
		super(
			_.extend(
				{
					tagName: "textarea",
				},
				options || {}
			)
		);
	}
}

export default TextArea;

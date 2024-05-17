import ListModal from "./ListModal";

class ListObject extends ListModal {
	constructor(options?) {
		super(options);

		var schema = this.schema;

		if (!schema.subSchema) throw new Error('Missing required option "schema.subSchema"');
		this.nestedSchema = schema.subSchema;
	}
}

export default ListObject;

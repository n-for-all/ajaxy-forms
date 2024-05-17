/// <reference path='./node_modules/@types/backbone/index.d.ts' />

import Form from "./form/Form";

class User extends Backbone.Model {
    schema = {
        title:      { type: 'Select', options: ['Mr', 'Mrs', 'Ms'] },
        name:       'Text',
        email:      { validators: ['required', 'email'] },
        birthday:   'Date',
        password:   'Password',
        notes:      { type: 'List', itemType: 'Text' }
    }
}

jQuery(() => {    
    var user = new User();
    
    var form = new Form({
        model: user
    }).render();
    
    jQuery('#post-body').append(form.el);
});

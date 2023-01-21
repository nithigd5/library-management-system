"use strict";

$(".pwstrength").pwstrength();

$("form").parsley({
    errorClass: 'is-invalid',
    successClass: 'is-valid',
    classHandler: function (ParsleyField) {
        return ParsleyField.$element;
    },
    errorsWrapper: '<div class="invalid-feedback"></div>',
});

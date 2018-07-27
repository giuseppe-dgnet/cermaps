$(document).ready(function() {
    //jquery Validate
    validaFormDescrizione();

});


/**
 * la funzione deve essere richiamata nel document ready
 */
function validaFormDescrizione() {
    $("#form_balloon").validate({//id del form
        rules: {
            'showroom[descrizione]': {
                minlength: 5
            }

        },
        submitHandler: function(form) {
            balloon_form_submit(form, true);

        }
    });
}

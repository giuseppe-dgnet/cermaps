$(document).ready(function() {
    //jquery Validate
    validaFormDescrizioneBreve();

});


/**
 * la funzione deve essere richiamata nel document ready
 */
function validaFormDescrizioneBreve() {
    $("#form_balloon").validate({//id del form
        rules: {
            'showroom[descrizione_attivita]': {
                minlength: 5
            }

        },
        submitHandler: function(form) {
            balloon_form_submit(form, true);
        }
    });
}

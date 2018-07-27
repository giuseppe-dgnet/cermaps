$(document).ready(function() {
    validaFormDati();
    $('#showroom_geo').val(nome_comune);
    attiva_ricerca_comune($('#showroom_geo'), $('#showroom_comune'), $('#showroom_indirizzo'), $('#showroom_cap'), 'cb_geo', spinnerButBlack, 'preloader');
});

function cb_geo(msg) {
    $('#showroom_lat').val(msg.lat);
    $('#showroom_lon').val(msg.lon);
}

/**
 * la funzione deve essere richiamata nel document ready
 */
function validaFormDati() {

    $("#form_balloon").validate({//id del form
        rules: {
            'showroom[partita_iva]': {
                minlength: 11,
                digits: true //Solo numeri
            },
            'showroom[codice_fiscale]': {
                minlength: 11,
                maxlength: 16
            }
        },
        submitHandler: function(form) {
            balloon_form_submit(form, true);
        }
    });
}
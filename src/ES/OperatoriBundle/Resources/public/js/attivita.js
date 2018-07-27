var opzioni_secondarie_scelte = 0;

$(document).ready(function() {
    //controllo quale checkbox è precheccato
    $("#sezione_checkbox :checked").each(function() {
        opzioni_secondarie_scelte++;
    });
    /*
     * Aggiungo un controllo extra custom per e lo attacco alla classe dei Checkbox
     */
    $.validator.addMethod('arrayminimo', function(value) {
        return opzioni_secondarie_scelte > 0
    }, 'Selezionare almeno un Opzione');

    $.validator.addClassRules('check_secondario', {
        arrayminimo: true
    });


    //in base alla select checco anche il checkbox
    $("#showroom_attivita_principale").change(function() {
        $(".check_secondario").prop('checked', false);
        if ($("#showroom_attivita_principale").val() == "") {
            if (opzioni_secondarie_scelte > 0)
                opzioni_secondarie_scelte--;

        } else {
            $("." + $("#showroom_attivita_principale").val()).prop('checked', true);
            opzioni_secondarie_scelte++
        }
    })

    //jquery Validate
    validaFormAttivita();
});


/**
 * la funzione deve essere richiamata nel document ready
 */
function validaFormAttivita() {
    
    $("#form_balloon").validate({//id del form
        rules: {

        },
        submitHandler: function(form) {
            balloon_form_submit(form, true);
        }
    });
}

function select_secondario(n) {
    if (n.prop("checked")) {
        opzioni_secondarie_scelte++;
    } else {
        opzioni_secondarie_scelte--;
    }
}
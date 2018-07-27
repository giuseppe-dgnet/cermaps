var opzioni_secondarie_scelte = 0;

$(document).ready(function() {

    //Aggiungo un metodo per la validazione, che almeno un tag sia selezionato
    $('#form_richiesta').find($('fieldset')[1]).find('div').last().after($('#anga-info'));
    
    /*
     * Aggiungo un controllo extra custom per e lo attacco alla classe dei Checkbox
     */
    $.validator.addMethod('arrayminimo', function(value) {
        return opzioni_secondarie_scelte > 0
    }, 'Selezionare almeno un Opzione');

    $.validator.addClassRules('check_secondario', {
        arrayminimo: true
    });

    $.validator.addMethod('phone', function(value) {
        //faccio questo if cosi può anche essere vuoto
        var numbers = value.split(/\d/).length - 1;
        return (8 <= numbers && numbers <= 20 && value.match(/^(\+){0,1}(\d|\s|\(|\)){8,20}$/));
    }, 'inserisci un numero di telefono valido');


    //in base alla select checco anche il checkbox
    $("#form_richiesta_attivita_principale").change(function() {
        $(".check_secondario").prop('checked', false);
        if ($("#form_richiesta_attivita_principale").val() == "") {
            if (opzioni_secondarie_scelte > 0)
                opzioni_secondarie_scelte--;

        } else {
            $("." + $("#form_richiesta_attivita_principale").val()).prop('checked', true);
            opzioni_secondarie_scelte++
        }
    })

    //jquery Validate
    validaFormRichiesta();
    
});

/**
 * la funzione deve essere richiamata nel document ready
 */
function validaFormRichiesta() {
    $("#form_richiesta").validate({//id del form
        rules: {
            'form_richiesta[partita_iva]': {
                minlength: 11,
                digits: true //Solo numeri
            },
            'form_richiesta[codice_fiscale_azienda]': {
                minlength: 11,
            },
            'form_richiesta[telefono]': {
                phone: true,
            },
            'form_richiesta[email]': {
                email: true,
            }

        },
        submitHandler: function(form) {
            salva_richiesta(form);

        }
    });
}

/**
 * Il form deve avere la action e un pulsante submit e un id
 * 
 * @param {object} form oggetto form che gli arriva dal validate
 */

function salva_richiesta(form) {

    $.ajax({
        type: "POST",
        url: form.action,
        data: $("#" + form.id).serialize(),
        dataType: "html",
        beforeSend: function() {
            disabilitaElemento($("#" + form.id).find(":submit"));
            beforeElemento($("#" + form.id).find(":submit"), '<p class="feedback_form">Attendere...</p>', 'slow');

        },
        success: function(msg) {
            msg = $.parseJSON(msg);
            if (msg.status == "OK") {
                eliminaElemento($("#" + form.id), 2000);
                $(".feedback_form").html('Richiesta inviata con successo');
                
                setTimeout(function() {                       
                    $("#titolo_fancybox_richiesta").html("Verrà Contattato al piu preso");
                }, 2000);

                setTimeout(function() {                       
                    parent.$.fancybox.close();
                }, 5000);

            } else {
                $(".feedback_form").html('Si è verificato un errore');
            }

            eliminaElemento(".feedback_form", 2000);
            attivaElemento($("#" + form.id).find(":submit"), 3000);
        },
        error: function(msg) {
            $(".feedback_form").html('Si è verificato un errorere');
            eliminaElemento(".feedback_form", 2000);
            attivaElemento($("#" + form.id).find(":submit"), 2000);
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
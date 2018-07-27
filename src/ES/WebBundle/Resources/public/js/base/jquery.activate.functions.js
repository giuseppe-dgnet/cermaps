// VALIDAZIONE DEL FORM CONTATTI

$(document).ready(function() {
    
    //nel document ready
    $.validator.addMethod('phone', function(value) {
        //faccio questo if cosi può anche essere vuoto
        var numbers = value.split(/\d/).length - 1;
        return (8 <= numbers && numbers <= 20 && value.match(/^(\+){0,1}(\d|\s|\(|\)){8,20}$/));
    }, 'inserisci un numero di telefono valido');

    $.validator.addMethod('fax', function(value) {
        //faccio questo if cosi può anche essere vuoto
        if (value) {
            var numbers = value.split(/\d/).length - 1;
            return (8 <= numbers && numbers <= 20 && value.match(/^(\+){0,1}(\d|\s|\(|\)){8,20}$/));
        } else {
            return true;
        }
    }, 'inserisci un numero di fax valido');

    
    validaFormCollaborazioni();

});



/**
 * la funzione deve essere richiamata nel document ready
 */
function validaFormCollaborazioni() {
    $("#form_collaborazioni").validate({//id del form
        rules: {
            piva: {
                required: true,
                minlength: 11,
                maxlength: 11,
                digits: true //Solo numeri
            },
            tel: {
                //digit: true,
                phone: true
            },
            fax: {
                fax: true
            },

        },
        submitHandler: function(form) {
            salvaFormCollaborazioni(form);
        }
    });
}

/**
 * Il form deve avere la action e un pulsante submit e un id
 * 
 * @param {object} form oggetto form che gli arriva dal validate
 */
function salvaFormCollaborazioni(form) {
    $.ajax({
        type: "POST",
        url: form.action,
        data: $("#" + form.id).serialize(),
        dataType: "html",
        beforeSend: function() {
            disabilitaElemento($("#" + form.id).find(":submit"));
            /*beforeElemento($("#" + form.id).find(":submit"), '<p class="feedback_form">' + Translator.get('messages:caricamento') + '</p>', 'slow');*/
            $('#form_collaborazioni button').html('Attendere').append('<div id="preloader">&nbsp;</div>');
            spinnerDef.spin();
            $('#preloader').append(spinnerDef.el);
        },
        success: function(msg) {
            msg = $.parseJSON(msg);
            if (msg.status == "OK") {
            $('#form_collaborazioni button').html('Invia la richiesta');
                $("#state3").html('<span class="state3">' + Translator.get('messages:success') + '</span>');
            } else {
                $("#state3").html('<span class="state4">' + Translator.get('messages:error') + '</span>');
            }
            spinnerDef.stop();
            removeObj('#preloader');
            /*eliminaElemento(".feedback_form", 2000);
            attivaElemento($("#" + form.id).find(":submit"), 3000);*/
        },
        error: function(msg) {
            $("#state3").html('<span class="state4">' + Translator.get('messages:error') + '</span>');
            /*eliminaElemento(".feedback_form", 2000);
            attivaElemento($("#" + form.id).find(":submit"), 2000);*/
            $('#form_collaborazioni button').html('Invia la richiesta');
            spinnerDef.stop();
            removeObj('#preloader');
        }
    });
}

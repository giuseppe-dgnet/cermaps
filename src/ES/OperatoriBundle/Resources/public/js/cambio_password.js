$(document).ready(function() {
    //alert(first); popolare nascondere readonly
    validaFormPassword();
    if(first =! '') {
        $("#password_corrente").hide();
        $("#fos_user_change_password_form_current_password").val('ecoseekr');
    }

});

/*---------------------------------- 
 Password
 ----------------------------------*/

function validaFormPassword() {
    $("#form_modifica_password_profilo").validate({//id del form
        rules: {
            'fos_user_change_password_form[current_password]': {
                required: true,
                minlength: 8
            },
            'fos_user_change_password_form[plainPassword][first]': {
                required: true,
                minlength: 8
            },
            'fos_user_change_password_form[plainPassword][second]': {
                equalTo: '#fos_user_change_password_form_plainPassword_first'
            }
        },
        submitHandler: function(form) {
            salva_form(form);
        }
    });
}

/**
 * Il form deve avere la action e un pulsante submit e un id
 * 
 * @param {object} form oggetto form che gli arriva dal validate
 */
function salva_form(form) {

    $.ajax({
        type: "POST",
        url: form.action,
        data: $("#" + form.id).serialize(),
        dataType: "html",
        beforeSend: function() {
            disabilitaElemento($("#" + form.id).find(":submit"));
            afterElemento($("#" + form.id).find(":submit"), '<span class="feedback_form"><img src="/bundles/punkavefileuploader/images/spinner.gif" /></span>', 'slow');

        },
        success: function(msg) {
            msg = $.parseJSON(msg);
            if (msg.status == "OK") {
                $(".feedback_form").html(Translator.get('messages:success'));
                if(first =! '') { //al momento della registrazione, per impostare la password custom   
                     eliminaElemento("#form_modifica_password_profilo", 2000);
                     attivaElemento("#redirect_showroom",2000)
                }
            } else if (msg.status == "passwordErr") {
                $(".feedback_form").html(Translator.get('messages:passwordErrore'));
            } else {
                $(".feedback_form").html(Translator.get('messages:error'));
            }

            eliminaElemento(".feedback_form", 2000);
            attivaElemento($("#" + form.id).find(":submit"), 3000);
        },
        error: function(msg) {
            $(".feedback_form").html(Translator.get('messages:error'));
            eliminaElemento(".feedback_form", 2000);
            attivaElemento($("#" + form.id).find(":submit"), 2000);
        }
    });
}
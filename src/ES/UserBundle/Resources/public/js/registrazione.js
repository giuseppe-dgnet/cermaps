$(document).ready(function() {
    validaFormRegistrazione();
    //preimposto la password 
    $("#fos_user_registration_form_email").val(email);
    $("#id_showroom").val(id_showroom);
    $("#id_richiesta").val(id_richiesta);
    $("#fos_user_registration_form_ruolo").val(ruolo);
    $("#fos_user_registration_form_plainPassword_first").val('ecoseekr').attr('readonly', true);
    $("#fos_user_registration_form_plainPassword_second").val('ecoseekr').attr('readonly', true);
    
});



/*---------------------------------- 
 Jquery Validation Form
 
 TODO Internazionalizzazione
 ----------------------------------*/

function validaFormRegistrazione() {
    $("#form_registrazione").validate({
        rules: {
            'fos_user_registration_form[gender]': {
                required: true,
            },
            'fos_user_registration_form[plainPassword][first]': {
                required: true,
                minlength: 8
            },
            'fos_user_registration_form[plainPassword][second]': {
                equalTo: '#fos_user_registration_form_plainPassword_first'
            }
        },
        messages: {
        },
        //Evita il doppio invio del Form
        submitHandler: function(form) {
            if (!this.wasSent) {
                this.wasSent = true;
                $(':submit', form).val('Please wait...')
                        .attr('disabled', 'disabled')
                        .addClass('disabled');
                form.submit();
            } else {
                return false;
            }
        }
    });
}
$(document).ready(function() {
    validaFormCambiaPassword();
});



/*---------------------------------- 
 Jquery Validation Form
 ----------------------------------*/

function validaFormCambiaPassword() {
    $("#form_cambia_password").validate({
        rules: {
            'fos_user_resetting_form[plainPassword][first]': {
                required: true,
                minlength: 8
            },
            'fos_user_resetting_form[plainPassword][second]': {
                equalTo: '#fos_user_resetting_form_plainPassword_first'
            }
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
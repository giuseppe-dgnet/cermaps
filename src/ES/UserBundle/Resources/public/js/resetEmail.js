$(document).ready(function() {
    validaFormResetEmail();
});

/*---------------------------------- 
 Jquery Validation Form
 ----------------------------------*/

function validaFormResetEmail() {
    $("#request_form_password").validate({
        rules: {
            'username': {
                //email: true,
                required: true
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
$(document).ready(function() {
    validaFormLogin();
    validaFormPasswordReset();
});



/*---------------------------------- 
 Jquery Validation Form
 ----------------------------------*/

function validaFormLogin() {


    $("#form_login").validate({
        rules: {
            '_username': {
                required: true,
                email: true
            },
            '_password': {
                required: true
            }
        },
        submitHandler: function(form) {
            if (!this.wasSent) {
                this.wasSent = true;
                $(':submit', form).val('Please wait...')
                    .attr('disabled', 'disabled')
                    .addClass('disabled');
                //form.submit();
                login_submit();
            } else {
                return false;
            }
        }
    });
}


function validaFormPasswordReset() {
    $("#request_form_password").validate({
        rules: {
            'username': {
                email: true,
                required: true,
                remote: {
                    url: Routing.generate('verifica_invio_password_email'),
                    type: "post",
                    data: {
                        email: function() { //nome variabile inviata nella request del Controler
                            return $("#username_email").val(); //dato inviato
                        }
                    }
                }
            }
        },
        messages: {
            'username': {
                //email: "deve essere una Email",
                remote: jQuery.format("L'email inserita non esiste in Ecoseekr")
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
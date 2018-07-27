/*---------------------------------- 
 Rinvio Attivazione
 Funzione resendEmailAction dentro RegistrationController
 ----------------------------------*/

function resendAttivazione() {
   
     $.ajax({
        type: "POST",
        url: url_resend_attivazione,
        data: { email: email},
        //dataType: "html",
        beforeSend: function() {
           // disabilitaElemento('#resend_email');
            creaElemento('#feedback','<p id="loading">'+Translator.get('messages:caricamento')+'</p>',1000);
        },
        success: function(msg) {
            
            eliminaFigli("#feedback");
            msg = $.parseJSON(msg);
           
            if (msg.status == "OK") {
                creaMessaggioFlash("#feedback",'<p id="success">'+Translator.get('messages:success')+'</p>',1000,2000,1000);
                attivaElemento('#resend_email',1000);
                //qualcosa     
            }else{
                creaMessaggioFlash("#feedback",'<p id="error">'+Translator.get('messages:error')+'</p>',1000,2000,1000);
                attivaElemento('#resend_email',1000);
            }
        },
        error: function(msg) {
            creaMessaggioFlash("#feedback",'<p id="error">'+Translator.get('messages:error')+'</p>',1000,2000,1000);
            attivaElemento('#resend_email',1000);
        }
    });
    
}
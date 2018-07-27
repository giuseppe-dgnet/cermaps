//var a_mps = new Array();
//var switch_rdo = false;
$(document).ready(function() {
    validaFormMpsPreventivo();
    //nel document ready
    $.validator.addMethod('arrayminimo', function(value) {
        return tags.length > 0
    }, Translator.get('messages:form_configurazione_profilo.seleziona_tag'));
    
    $.validator.addMethod('phone', function(value) {
        var numbers = value.split(/\d/).length - 1;
        return (8 <= numbers && numbers <= 20 && value.match(/^(\+){0,1}(\d|\s|\(|\)){8,20}$/));
    }, 'inserisci un numero di telefono valido');
    
    attiva_ricerca_comune($("#servizi_geo"), $("#servizi_comune"), $("#servizi_indirizzo"), $("#servizi_cap"), "setCoordinateGeo", spinnerDef, "preloader")
    $("#servizi_servizi_list").val(a_servizi);
    
    /**
     * popola la parte sinistra del Fancybox
     */
    a_servizi.forEach(function(id){
        row = $('#servizi'+id).html();
        row = row.remove(' class="desc-col"').remove(/<ul[^>]+>[^\\]+<\/ul>/).stripTags('span').stripTags('p');
        $('<li></li>')
        .append('<p><span class="servizi_small left margin-right-5"></span>'+row+'</p>')
        .prependTo( $('#ul_servizi') );
    });
    
    $('#tot_servizi').html(a_servizi.length);
});



/**
 * la funzione deve essere richiamata nel document ready
 */
function validaFormMpsPreventivo() {
    $("#form_rdo_servizi").validate({//id del form
        rules: {
            
            'servizi[from_email][first]': {
                 email:true
            },
            'servizi[from_email][second]': {
                equalTo: '#servizi_from_email_first'
            },
            
            'servizi[telefono]': {//name del campo
                phone:true
            }
        },
        submitHandler: function(form) {
            salvaFormPreventivoMps(form);
        }
    });
}

/**
 * Il form deve avere la action e un pulsante submit e un id
 * 
 * @param {object} form oggetto form che gli arriva dal validate
 */
function salvaFormPreventivoMps(form) {

    $.ajax({
        type: "POST",
        url: form.action,
        data: $("#" + form.id).serialize(),
        dataType: "html",
        beforeSend: function() {
            disabilitaElemento($("#" + form.id).find(":submit"));
            //beforeElemento($("#" + form.id).find(":submit"), '<p class="feedback_form">' + Translator.get('messages:caricamento') + '</p>', 'slow');
            $("#" + form.id).find(":submit").html(Translator.get('messages:attendere'));
            $("#" + form.id).find(":submit").append('<div id="preloader">&nbsp;</div>');
            spinnerDef.spin();
            $('#preloader').append(spinnerDef.el);
        },
        success: function(msg) {
            msg = $.parseJSON(msg);
            if (msg.status == "OK") {
                $("#" + form.id).find(":submit").html(Translator.get('messages:success'));
                $("#form-container").toggle();
                $("#form-ok").toggle();
            } else {
                $("#" + form.id).find(":submit").html(Translator.get('messages:error'));
            }
            spinnerDef.stop();
            removeObj('#preloader');
            //eliminaElemento(".feedback_form", 2000);
            attivaElemento($("#" + form.id).find(":submit"), 3000);
        },
        error: function(msg) {
            $("#" + form.id).find(":submit").html(Translator.get('messages:error'));
            //eliminaElemento(".feedback_form", 2000);
            attivaElemento($("#" + form.id).find(":submit"), 2000);
        }
    });
}


function setCoordinateGeo(json) {
    response = JSON.parse(json);
    if(response.cap) {
        $("#servizi_cap").val(response.cap);
    } else {    
        $("#servizi_cap").val('');
        if(!response.lat) {
            alert('Sei sicuro di aver scritto correttamente il tuo indirizzo?');
        }
    }
    //console.log(json);
    $("#servizi_latitudine").val(response.lat);
    $("#servizi_longitudine").val(response.lon);
}
var pre_populate = [];

$(document).ready(function() {


    $("#more_cer_rdo").click(function() {
        $('#more_cer_rdo').toggleClass('vert-close');
        $('#more_cer_rdo').toggleClass('vert-open');
        $(".more_information_rdo").toggle();
        $.fancybox.update()
    });

    $(".nuvola-input-dropdown-rdo").css("z-index", "2000000");

    $('#rdo_cerca_cer').nuvolaInput(Routing.generate(testVariabile('csr') ? 'tag_cer_cerca' : 'tag_cer_sr_cerca', url_param), {
        theme: 'rdo',
        preventDuplicates: true,
        minChars: 2,
        tokenLimit: 5,
        zindex: 2000000,
        hintText: 'Descrivi il tuo rifiuto o il codice CER e premi invio',
        prePopulate: pre_populate,
        onDelete: function(item) {
            a_cer.remove(item.id);
            $("#rdo_cer_list").val(JSON.stringify(a_cer));
        },
        onReady: function(item) {
            $(".nuvola-input-dropdown-rdo").css("z-index", "2000000");
        },
        onAdd: function(item) {
            a_cer.add(item.id);
            //var html = $("#hidden_cer").html().assign(item);
            $("#rdo_cer_list").val(JSON.stringify(a_cer));
            //            $("#nuvola-input-cer").blur();
        }
    });

    //nel document ready
    $.validator.addMethod('arrayminimo', function(value) {
        return a_cer.length > 0
    }, Translator.get('messages:form_configurazione_profilo.seleziona_tag'));

    $.validator.addMethod('phone', function(value) {
        var numbers = value.split(/\d/).length - 1;
        return (8 <= numbers && numbers <= 20 && value.match(/^(\+){0,1}(\d|\s|\(|\)){8,20}$/));
    }, 'inserisci un numero di telefono valido');

    attiva_ricerca_comune($("#rdo_geo"), $("#rdo_comune"), $("#rdo_indirizzo"), $("#rdo_cap"), "setCoordinateGeo", spinnerDef, "preloader")


    validaFormPreventivo();
    /**
     * popola la parte sinistra del Fancybox
     
    a_cer.forEach(function(id) {
        //console.log(id);
        row = $('#cer' + id).html();
        row = row.remove(' class="desc-col"').remove(/<ul[^>]+>[^\\]+<\/ul>/).stripTags('span').stripTags('p');
        //console.log(row);
        $('<li></li>')
                .append('<p>' + row + '</p>')
                .prependTo($('#ul_cer'));
    });

    $('#tot_cer').html(a_cer.length);
*/
});



/**
 * la funzione deve essere richiamata nel document ready
 */
function validaFormPreventivo() {
    $("#rdo").validate({//id del form
        rules: {
            'rdo[quantita]': {//name del campo
                digits: true
            },
            'rdo[from_email]': {
                email: true
            },
            'rdo[telefono]': {//name del campo
                phone: true
            },
            'rdo[from_nome]': {
                required: true
            },
            'rdo[testo]': {
                required: true
            },
            'rdo[from_cognome]': {
                required: true
            },
            'rdo[indirizzo]': {
                required: false
            },
            'rdo[uumm]': {
                required: function() {
                    return $("#rdo_quantita").val() !== '';
                }
            },
            'rdo[geo]': {
                required: false
            }
        },
        submitHandler: function(form) {
            salvaFormPreventivo(form);
        }
    });
}

/**
 * Il form deve avere la action e un pulsante submit e un id
 * 
 * @param {object} form oggetto form che gli arriva dal validate
 */
function salvaFormPreventivo(form) {

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

                if (fb) {
                    $('#titolo_fancybox_richiesta').html('La Sua richiesta è stata spedita correttamente');
                    $('#form-container').toggle('normal', function() {
                        $(".form_ok").toggle();
                    });

                    setTimeout(function() {
                        parent.$.fancybox.close();
                    }, 10000);

                } else {
                    $('#form-container-showroom').toggle('normal', function() {
                        $(".form_ok").toggle();
                    });

                    setTimeout(function() {
                        $('#form-container-showroom').toggle('normal', function() {
                            $("#" + form.id).find(":submit").html(Translator.get('messages:invia_richiesta'));
                            $('.nuvola-input-list-rdo li:not(:last)').remove();
                            $(".more_information_rdo").hide();
                            a_cer = [];
                            $("#rdo_cer_list").val(JSON.stringify(a_cer));
                            $("#" + form.id)[0].reset();
                            $(".form_ok").toggle();
                        });
                    }, 10000);
                }
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
    if (response.cap) {
        $("#rdo_cap").val(response.cap);
    } else {
        $("#rdo_cap").val('');
        if (!response.lat) {
            alert('Sei sicuro di aver scritto correttamente il tuo indirizzo?');
        }
    }
    //console.log(json);
    $("#rdo_latitudine").val(response.lat);
    $("#rdo_longitudine").val(response.lon);
}
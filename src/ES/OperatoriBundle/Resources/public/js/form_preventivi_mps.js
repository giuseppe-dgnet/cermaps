//var a_mps = new Array();
//var switch_rdo = false;
var pre_populate = [];
$(document).ready(function() {

    $("#more_mps_rdo").click(function() {
        $('#more_mps_rdo').toggleClass('vert-close');
        $('#more_mps_rdo').toggleClass('vert-open');
        $(".more_information_rdo").toggle();
        $.fancybox.update()
    });

    $(".nuvola-input-dropdown-rdo").css("z-index", "2000000");

    $('#mps_cerca_mps').nuvolaInput(Routing.generate('tag_mps_sr_cerca', url_param), {
        theme: 'rdo',
        preventDuplicates: true,
        minChars: 2,
        tokenLimit: 5,
        zindex: 2000000,
        hintText: 'Descrivi il tuo rifiuto o il codice MPS e premi invio',
        prePopulate: pre_populate,
        onDelete: function(item) {
            a_mps.remove(item.id);
            $("#mps_mps_list").val(JSON.stringify(a_mps));
        },
        onReady: function(item) {
            $(".nuvola-input-dropdown-mps").css("z-index", "2000000");
        },
        onAdd: function(item) {

            a_mps.add(item.id);

            //var html = $("#hidden_cer").html().assign(item);

            $("#mps_mps_list").val(JSON.stringify(a_mps));

            //            $("#nuvola-input-cer").blur();
        }
    });

    //nel document ready
    $.validator.addMethod('arrayminimo', function(value) {
        return tags.length > 0
    }, Translator.get('messages:form_configurazione_profilo.seleziona_tag'));

    $.validator.addMethod('phone', function(value) {
        var numbers = value.split(/\d/).length - 1;
        return (8 <= numbers && numbers <= 20 && value.match(/^(\+){0,1}(\d|\s|\(|\)){8,20}$/));
    }, 'inserisci un numero di telefono valido');

    attiva_ricerca_comune($("#mps_geo"), $("#mps_comune"), $("#mps_indirizzo"), $("#mps_cap"), "setCoordinateGeo", spinnerDef, "preloader")

    validaFormMpsPreventivo();

    // $("#mps_mps_list").val(a_mps);

    /**
     * popola la parte sinistra del Fancybox
     */
    a_mps.forEach(function(id) {
        row = $('#mps' + id).html();
        row = row.remove(' class="desc-col"').remove(/<ul[^>]+>[^\\]+<\/ul>/).stripTags('span').stripTags('p');
        $('<li></li>')
                .append('<p><span class="mps_small left margin-right-5"></span>' + row + '</p>')
                .prependTo($('#ul_mps'));
    });

    $('#tot_mps').html(a_mps.length);
});



/**
 * la funzione deve essere richiamata nel document ready
 */
function validaFormMpsPreventivo() {
    $("#form_rdo_mps").validate({//id del form
        rules: {
            'mps[from_email]': {
                email: true
            },
            'mps[from_nome]': {
                required: true
            },
            'mps[testo]': {
                required: true
            },
            'mps[from_cognome]': {
                required: true
            },
            'mps[telefono]': {//name del campo
                phone: true
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
                            a_mps = [];
                            $("#rdo_mps_list").val(JSON.stringify(a_mps));
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
        $("#mps_cap").val(response.cap);
    } else {
        $("#mps_cap").val('');
        if (!response.lat) {
            alert('Sei sicuro di aver scritto correttamente il tuo indirizzo?');
        }
    }
    //console.log(json);
    $("#mps_latitudine").val(response.lat);
    $("#mps_longitudine").val(response.lon);
}
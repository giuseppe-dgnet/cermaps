var opzioni_secondarie_scelte = 0;

$(document).ready(function() {

    //controllo quale checkbox è precheccato
    $("#sezione_checkbox :checked").each(function() {
        opzioni_secondarie_scelte++
    });

    /*
     * Aggiungo un controllo extra custom per e lo attacco alla classe dei Checkbox
     */
    $.validator.addMethod('arrayminimo', function(value) {
        return opzioni_secondarie_scelte > 0;
    }, 'Selezionare almeno un Opzione');

    $.validator.addClassRules('check_secondario', {
        arrayminimo: true
    });

    $.validator.addMethod('phone', function(value) {
        //faccio questo if cosi può anche essere vuoto
        var numbers = value.split(/\d/).length - 1;
        return (8 <= numbers && numbers <= 20 && value.match(/^(\+){0,1}(\d|\s|\(|\)){8,20}$/));
    }, 'inserisci un numero di telefono valido');


    //in base alla select checco anche il checkbox
    $("#form_crea_showroom_attivita_principale").change(function() {
        $(".check_secondario").prop('checked', false);
        if ($("#form_crea_showroom_attivita_principale").val() == "") {
            if (opzioni_secondarie_scelte > 0)
                opzioni_secondarie_scelte--;

        } else {
            $("." + $("#form_crea_showroom_attivita_principale").val()).prop('checked', true);
            opzioni_secondarie_scelte++;
        }
        open = false;
        $('.cer_trattati').each(function(elem){
            if($(this).prop('checked')) {
                open = true;
            }
        });
        if(open) {
            $('#tr_elenco_cer').show(); 
        } else {
            $('#tr_elenco_cer').hide(); 
        }
    });
    
    $('#form_crea_showroom_elenco_cer').blur(function() {
        $(this).val($(this).val().words());
    });

    $('.check_secondario').click(function(){
        open = false;
        $('.cer_trattati').each(function(elem){
            if($(this).prop('checked')) {
                open = true;
            }
        });
        if(open) {
            $('#tr_elenco_cer').show(); 
        } else {
            $('#tr_elenco_cer').hide(); 
        }
    });

    //jquery Validate
    validaFormCreaShowroom();

});


/**
 * la funzione deve essere richiamata nel document ready
 */
function validaFormCreaShowroom() {
    $("#crea_utenza_form").validate({//id del form
        rules: {
            'form_crea_showroom[partita_iva]': {
                minlength: 11,
                digits: true //Solo numeri
            },
            'form_crea_showroom[codice_fiscale_azienda]': {
                minlength: 11,
            },
            'form_crea_showroom[telefono]': {
                phone: true,
            },
            'form_crea_showroom[email]': {
                email: true,
            }

        },
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

/**
 * Il form deve avere la action e un pulsante submit e un id
 * 
 * @param {object} form oggetto form che gli arriva dal validate
 */

function salva_showroom_utenza(form) {

    $.ajax({
        type: "POST",
        url: form.action,
        data: $("#" + form.id).serialize(),
        dataType: "html",
        beforeSend: function() {
            disabilitaElemento($("#" + form.id).find(":submit"));
            beforeElemento($("#" + form.id).find(":submit"), '<p class="feedback_form">Attendere...</p>', 'slow');

        },
        success: function(msg) {
            msg = $.parseJSON(msg);
            if (msg.status == "OK") {
                eliminaElemento($("#" + form.id), 2000);
                $(".feedback_form").html('Richiesta inviata con successo');



            } else {
                $(".feedback_form").html('Si è verificato un errore');
            }

            eliminaElemento(".feedback_form", 2000);
            attivaElemento($("#" + form.id).find(":submit"), 3000);
        },
        error: function(msg) {
            $(".feedback_form").html('Si è verificato un errorere');
            eliminaElemento(".feedback_form", 2000);
            attivaElemento($("#" + form.id).find(":submit"), 2000);
        }
    });

}

function select_secondario(n) {
    if (n.prop("checked")) {
        opzioni_secondarie_scelte++;
    } else {
        opzioni_secondarie_scelte--;
    }
}

function seleziona() {
    if ($('#go_denominazione').val()) {
        $("#form_crea_showroom_ragione_sociale").val($('#go_denominazione').val());
    }
    if ($('#go_attivita').val()) {
        $("#form_crea_showroom_descrizione_attivita").val($('#go_attivita').val());
    }
    if ($('#go_descrizione').val()) {
        $("#form_crea_showroom_descrizione").val($('#go_descrizione').val());
    }
    if ($('#go_denominazione').val()) {
        $("#form_crea_showroom_ragione_sociale").val($('#go_denominazione').val());
    }
    if ($('#go_codice_fiscale').val()) {
        $("#form_crea_showroom_partita_iva").val($('#go_codice_fiscale').val());
    }
    if ($('#go_partita_iva').val()) {
        $("#form_crea_showroom_codice_fiscale").val($('#go_partita_iva').val());
    }
    $("#form_crea_showroom_sezione").val(detail.anga.sezione);
    $("#form_crea_showroom_numero_iscrizione").val(detail.anga.numero_iscrizione);
    if ($('#go_indirizzo').val()) {
        $("#form_crea_showroom_indirizzo").val($('#go_indirizzo').val());
    }
    if ($('#go_comune').val()) {
        $("#form_crea_showroom_comune_testuale").val($('#go_comune').val());
    }
    if ($('#go_cap').val()) {
        $("#form_crea_showroom_cap").val($('#go_cap').val());
    }
    if ($('#go_geo').val()) {
        $("#geo").val($('#go_geo').val());
        $("#form_crea_showroom_latitudine").val($('#go_geo').val().first($('#go_geo').val().search('x')));
        $("#form_crea_showroom_longitudine").val($('#go_geo').val().from(1 + $('#go_geo').val().search('x')));
    }
    if ($('#go_telefono').val()) {
        $("#form_crea_showroom_telefono").val($('#go_telefono').val());
    }
    if ($('#go_fax').val()) {
        $("#form_crea_showroom_fax").val($('#go_fax').val());
    }
    if ($('#go_cellulare').val()) {
        $("#form_crea_showroom_cellulare").val($('#go_cellulare').val());
    }
    if ($('#go_email').val()) {
        $("#form_crea_showroom_email").val($('#go_email').val());
    }
    if ($('#go_sito').val()) {
        $("#form_crea_showroom_sito").val($('#go_sito').val());
    }

    $('#form_crea_showroom_anga').val(JSON.stringify(detail))

    $("#anga_detail").hide();
    $("#crea_utenza").show();
}

function torna_indietro() {
    $('#anga_detail').hide();
    $('#anga_list_table').show();
}


var detail = {};
var anga_search = {};

$(document).ready(function() {
    if (!nuovo_showroom_diretto) {
        anga_search = {
            denominazione: $('#form_crea_showroom_ragione_sociale').val(),
            indirizzo: $('#form_crea_showroom_indirizzo').val(),
            email: $('#form_crea_showroom_email').val(),
            partita_iva: $('#form_crea_showroom_partita_iva').val(),
            codice_fiscale: $('#form_crea_showroom_codice_fiscale').val(),
            cap: $('#form_crea_showroom_cap').val()
        };
    }
    $('#bt_forza').click(function() {
        $("#anga_list_table").hide();
        $('#tr_elenco_cer').show(); 
        $("#crea_utenza").show();
    });
    $('#bt_anga').click(function() {
        if (nuovo_showroom_diretto) {
            anga_search = {
                denominazione: $('#form_crea_showroom_ragione_sociale').val(),
                indirizzo: $('#form_crea_showroom_indirizzo').val(),
                email: $('#form_crea_showroom_email').val(),
                partita_iva: $('#form_crea_showroom_partita_iva').val(),
                codice_fiscale: $('#form_crea_showroom_codice_fiscale').val(),
                cap: $('#form_crea_showroom_cap').val()
            };
        }
        $.post(url_anga_list, anga_search, function(json) {
            json.each(function(row) {
                if (row.tipo === 'anga') {
                    trow = $('#dettagli_list_row').html().assign({
                        id: row.id,
                        tipo: row.tipo,
                        denominazione: row.denominazione,
                        sede: row.sede.indirizzo + ' cap: ' + row.sede.cap + ' - ' + row.sede.comune + ' (' + row.sede.provincia + ') ' + row.sede.regione + ' ' + row.sede.nazione,
                        punti: row.punti
                    });
                    $('#anga_list').append(trow);
                } else {
                    trow = $('#nodettagli_list_row').html().assign({
                        id: row.id,
                        tipo: row.tipo,
                        denominazione: row.denominazione,
                        sede: row.sede.indirizzo + ' cap: ' + row.sede.cap + ' - ' + row.sede.comune + ' (' + row.sede.provincia + ') ' + row.sede.regione + ' ' + row.sede.nazione,
                        punti: row.punti
                    });
                    $('#anga_list').append(trow);
                }
            });
        });
    });
});

function anga_detail(id) {
    $.post(url_anga_detail, {id: id}, function(json) {
        detail = json;
        detail['relazions'] = [];
        tbody = $('#anga_detail_body').html().assign({
            denominazione: json.denominazione,
            attivita: json.abstract ? json.abstract : '',
            descrizione: json.descrizione ? json.descrizione : '',
            codice_fiscale: json.codice_fiscale ? json.codice_fiscale : '',
            partita_iva: json.partita_iva ? json.partita_iva : '',
            indirizzo: json.sede.indirizzo,
            cap: json.sede.cap,
            comune: json.sede.comune,
            provincia: json.sede.provincia,
            lat: json.sede.lat,
            lon: json.sede.lon,
            telefono: json.contatti.telefono ? json.contatti.telefono : '',
            cellulare: json.contatti.cellulare ? json.contatti.cellulare : '',
            fax: json.contatti.fax ? json.contatti.fax : '',
            email: json.contatti.email ? json.contatti.email : '',
            sito: json.contatti.sito ? json.contatti.sito : '',
            sezione: json.anga.sezione,
            numero_iscrizione: json.anga.numero_iscrizione,
            categorie: json.anga.categorie,
            cer: json.anga.cer,
            cercp: json.anga.cercp,
            tipologie: json.anga.tipologie
        });
        $('#anga_detail').html('');
        $('#anga_detail').append(tbody);

        if (!json.anga.cer || json.anga.cer.has('labelGray')) {
            $('#c_cer').html(json.anga.cer);
        }
        if (!json.anga.cercp || json.anga.cercp.has('labelGray')) {
            $('#c_cercp').html(json.anga.cercp);
        }
        if (!json.anga.tipologie || json.anga.tipologie.has('labelGray')) {
            $('#c_tipologie').html(json.anga.tipologie);
        }
        if (!json.sede.lat || !json.sede.lon) {
            $('#go_geo').val('');
        }

        json.full_match.each(function(row) {
            trow = $('#match_list_row').html().assign({
                id: row.id,
                tipo: row.tipo,
                denominazione: row.denominazione,
                sede: row.sede.indirizzo + ' cap: ' + row.sede.cap + ' - ' + row.sede.comune + ' (' + row.sede.provincia + ') ' + row.sede.regione + ' ' + row.sede.nazione,
                punti: row.punti
            });
            $('#match_list_table').append(trow);
        });
        json.partial_match.each(function(row) {
            trow = $('#match_list_row').html().assign({
                id: row.id,
                tipo: row.tipo,
                denominazione: row.denominazione,
                sede: row.sede.indirizzo + ' cap: ' + row.sede.cap + ' - ' + row.sede.comune + ' (' + row.sede.provincia + ') ' + row.sede.regione + ' ' + row.sede.nazione,
                punti: row.punti
            });
            $('#match_list_table').append(trow);
        });
        $("#anga_list_table").hide();
        $('#anga_detail').show();
    });
}

function confronta_detail(id) {
    $.post(url_anga_detail, {id: id}, function(json) {
        detail['relazions'].add(json);
        $('#sw_denominazione').html(json.denominazione);
        $('#sw_attivita').html(json.abstract ? json.abstract : '');
        $('#sw_descrizione').html(json.descrizione ? json.descrizione.stripTags() : '');
        $('#sw_codice_fiscale').html(json.codice_fiscale ? json.codice_fiscale : '');
        $('#sw_partita_iva').html(json.partita_iva ? json.partita_iva : '');
        $('#sw_indirizzo').html(json.sede.indirizzo);
        $('#sw_cap').html(json.sede.cap);
        $('#sw_geo').html(json.sede.lat + 'x' + json.sede.lon);
        $('#sw_comune').html(json.sede.comune + '(' + json.sede.provincia + ')');
        $('#sw_telefono').html(json.contatti.telefono ? json.contatti.telefono : '');
        $('#sw_cellulare').html(json.contatti.cellulare ? json.contatti.cellulare : '');
        $('#sw_fax').html(json.contatti.fax ? json.contatti.fax : '');
        $('#sw_email').html(json.contatti.email ? json.contatti.email : '');
        $('#sw_sito').html(json.contatti.sito ? json.contatti.sito : '');

        $('.sw').prepend('<button class="change">&lt;</button>');
        $('.change').click(function() {
            cambia($(this).closest('td'));
        });
        window.location = '#';
    });
}

function cambia(td) {
    target = td.attr('id').replace('sw_', 'go_');
    text = td.html().removeTags('button').trim();
    if (text) {
        $('#' + target).val(text);
    }
}

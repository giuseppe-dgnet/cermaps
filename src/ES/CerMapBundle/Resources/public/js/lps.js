var result_json = [];
ac_comune = false;
ac_suggerimento = false;
var selected = [];
var contatore = 0;
var id_immagine;
var invio = false;

$(document).ready(function() {
    checkVariabili(['aggiungi_utente_rdo', 'url_cerca', 'url_redirect', 'url_cerca_invio']);

    var ricerca;
    // Codice necessario per attivare i tooltip su codici cer e procedure di recupero
    $('.button-cer-code-small').attr('title', function() {
        cer = $(this);
        $('.button-cer-code').each(function(i) {
            if ($(this).html() == cer.html()) {
                cer.attr('title', $(this).attr('title'));
                cer.attr('href', $(this).attr('href'));
                if ($(this).hasClass('cer-per')) {
                    cer.addClass('cer-per');
                }
            }
        });
    });
    $('.button-op-code-small').attr('title', function() {
        op = $(this);
        $('.button-op-code').each(function(i) {
            if ($(this).html() == op.html()) {
                op.attr('title', $(this).attr('title'));
            }
        });
    });
    $('.lps-tab > ul.tab-menu > li > a').click(function() {
        switch_tabs($(this), '.tab-content-lps');
    });

    $('.lps-tab-sheet > ul.tab-menu > li > a').click(function() {
        switch_tabs($(this), '.tab-content-lps-sheet');
    });
    $(".button-cer-code").tipTip({
        keepAlive: false,
        delay: 0
    });
    $(".button-op-code").tipTip({
        keepAlive: false,
        delay: 0
    });
    $(".button-cer-code-small").tipTip({
        keepAlive: false,
        delay: 0
    });
    $(".button-op-code-small").tipTip({
        keepAlive: false,
        delay: 0
    });
    $(".nav, .navtip").tipTip({
        keepAlive: false,
        delay: 0
    });

    $('#rdoRequest').hide();



    $('.rdo-selector').click(function() {
        id_immagine = $(this).attr("id").replace('btn_', '');

        if ($(this).find('#bottone_' + id_immagine).hasClass('add-rdo')) {
            if ($('#btn_' + id_immagine).attr("disabled") != "disabled") {
                aggiungiElemento(id_immagine);
                $(this).find('.add-rdo').switchClass("add-rdo", "remove-rdo", 1).html('Rimuovi');
            }

            $('#btn_' + id_immagine).attr("disabled", true);

        } else {
            if ($('#btn_' + id_immagine).attr("disabled") != "disabled") {
                togliElemento(id_immagine);
                $(this).find('.remove-rdo').switchClass("remove-rdo", "add-rdo", 1).html('Seleziona e<br>Richiedi offerta');
            }
        }


    });


    $(".rdoRequest a").fancybox({
        helpers: {
            overlay: {
                css: {
                    'background-color': '#000'
                },
                speedIn: 500,
                opacity: 0.75
            }
        },
        type: 'ajax',
        padding: 0,
        margin: 0
    });

    search = $('#q_lps');
    preloader_div = 'preloader';

    autocomplete_mdr_lps(ricerca);

    search.keyup(function(e) {
        //alert(e.keyCode);
        ac_suggerimento = true;
        if (e.keyCode == 13) {

            invio = true;
            $(this).autocomplete("close")
            ac_suggerimento = false;
            result_json = [];
            //            $(".colsx-filter h2").html("Hai Cercato:");
            //            $(".coldx-listing h4").html("Ricerca Libera");
            //            $(".coldx-listing h4").html("Ricerca Libera");


            var testo = $(this).val();
            $('.breadcrumbs strong').html(" &raquo; Ricerca Libera per <strong>'"+testo+"'<strong>");

            if ($(this).val().length >= 2) {
                if (result_json.isEmpty()) {

                    $.ajax({
                        async: false,
                        url: url_cerca + '.html',
                        dataType: "html",
                        data: {
                            term: testo
                        },
                        success: function(data) {
                            setTimeout(function(){
                                spinnerDef.stop();
                                removeObj('#' + preloader_div)
                            }, 500);
                            if ($('.colsx').length) {
                                $('.coldx-listing h2').html(testo);
                                $('.colsx').html(data);
                            } else {
                                
                                /**
                                 * come viene modificato il Dom
                                 * Facciamo un controlle se è isotope e quindi lo eliminiamo
                                 * ed Eliminaiamo la Colonna di sinistra per fare spazio alla ricerca
                                 */
                                $('.colmono h2').html("Ricerca Libera");
                                $('.left').remove();
                                $("#contenuti").attr('class', 'cer-index-cat clearfix');
                                $(".contenuto_ricerca").removeAttr("style");
                                if (typeof sheet === 'undefined') { 
                                    if (typeof isotope != 'undefined') { // Any scope
                                        $("#contenuti").html(data).isotope('destroy');
                                    } else {
                                         $("#contenuti").html(data);
                                    } 
                                } else {
                                    $(".contenuto_ricerca").html("<ul class='rec-index-cat clearfix'  id='contenuti'></ul>");
                                    $("#contenuti").html(data);
                                }
                            }
                            result_json = [];
                        },
                        error: function(data) {
                            setTimeout(function(){
                                spinnerDef.stop();
                                removeObj('#' + preloader_div)
                            }, 500);
                        }
                    });
                } else {
                    $.ajax({
                        async: false,
                        //type: 'POST',
                        url: url_cerca_invio,
                        dataType: "html",
                        data: {
                            maxRows: 10,
                            term: result_json
                        },
                        success: function(data) {
                            //$('.coldx-listing h2').html(term);
                            //                            alert(data);
                            //                            alert(data.length);
                           spinnerDef.stop();
                            removeObj('#' + preloader_div);
                            if ($('.colsx').length) {
                                $('.coldx-listing h2').html(testo);
                                $('.colsx').html(data);
                            } else {
                                $('.colmono h2').html("Ricerca Libera");
                                $('.left').remove();
                                $("#contenuti").attr('class', 'cer-index-cat clearfix');
                                $(".contenuto_ricerca").removeAttr("style");
                                $("#contenuti").html(data).isotope('destroy');
                            }
                            result_json = [];
                        }
                    });
                }
            }
        }
    });
});

function aperturaFancybox() {
    $(".send-rdo").click();
}

/**
 * Chiamata ajax per aggiungere a rubrica
 */

function aggiungiElemento(id) {
    //$('.tab-request').trigger('click');
    $('#rdoRequest').show();
    $('#op-choice').hide();
    //$('#content_large').css('margin-bottom', '250px');
    $('.token-input-list-facebook').append('<li id="preloader-li" style="height: 28px;"><div id="preloader">&nbsp;</div></li>');
    spinnerDef.spin();
    $('#preloader').append(spinnerDef.el);
    //$('#loading_contatto').show();
    $.post(aggiungi_utente_rdo, {
        contatto: id
    }, function(msg) {
        $('#check_' + msg.id).fadeToggle('slow');
        $('#btn_' + msg.id).attr("disabled", false);
        $('#loading_contatto').hide();
        //$('#loading_contatto').hide();
        spinnerDef.stop();
        removeObj('#preloader-li');
        selected.add(msg.id);
        $(".token-input-list-facebook").append(
                "<li class='token-input-token-facebook'>\n\
            <p id='proprietario_" + msg.id + "'>" + msg.nome + "</p><span class='token-input-delete-token-facebook' id='btn_x_" + msg.id + "'>×</span>\n\n\
            </li>"
        );

        $('#btn_x_' + msg.id).live("click", function() {
            $("#bottone_" + msg.id).switchClass("remove-rdo", "add-rdo", 1).html('Seleziona e<br>Richiedi offerta');
            togliElemento(msg.id);
            $('#btn_x_' + msg.id).die("click");
        });

        if (selected.length == 3) {
            aperturaFancybox();
        }

    });
}

function togliElemento(id) {
    //$('.tab-request').trigger('click');
    selected.remove(id);
    $('#check_' + id).fadeToggle('slow');
    $("#proprietario_" + id).parent().remove();
    if (selected.length == 0) {
        $('#rdoRequest').hide();
        $('#op-choice').show();
        // $('.tab-content').css('margin-bottom', '0');
    }
}

function autocomplete_mdr_lps(ricerca) {
    search.autocomplete({
        minLength: 2,
        delay: 500,
        source: function(request, response) {
            search.closest('section').append('<div id="' + preloader_div + '">&nbsp;</div>');
            spinnerDef.spin();
            $('#' + preloader_div).append(spinnerDef.el);
            if (invio === false) {
                $.ajax({
                    url: url_cerca + '.json',
                    dataType: "json",
                    data: {
                        maxRows: 10,
                        term: request.term
                    },
                    success: function(data) {
                        result_json = [];
                        if (data.length == 0) {
                            spinnerDef.stop();
                            removeObj('#' + preloader_div);
                        } else {
                            //alert(ac_suggerimento);
                            if (ac_suggerimento) {
                                response($.map(data, function(item) {
                                    if (item.vai) {
                                        result_json.push({
                                            label: item.label,
                                            slug: item.slug,
                                            vai: item.vai
                                        });
                                    } else {
                                        result_json.push({
                                            label: item.label,
                                            slug: item.slug
                                        });
                                    }
                                    return item;
                                }));
                                spinnerDef.stop();
                                removeObj('#' + preloader_div);
                            }
                        }
                    }
                });
            }
            invio = false;
        },
        select: function(event, ui) {
            if (ui.item) {
                if (ui.item.vai) {
                    window.location = ui.item.vai;
                } else {
                    window.location = url_redirect + '/' + ui.item.slug;
                }
            }
        },
        open: function() {
            $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
            $(".ui-autocomplete").css("z-index", 1000).css("width", '310px').css('height', '310px');
        },
        close: function() {
            $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
            //            if(comune.val() == '') {
            //                geo.val(oldComune);
            //                comune.val(oldComuneId);
            //            }
        }
    }).focus(function() {
        ac_suggerimento = true;
        result_json = [];
    }).blur(function() {
        ac_suggerimento = false;
    })
}

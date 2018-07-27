var last_form_submit = '';
var form_target = null;
var last_elem = null;
var modifica = 'no';
var showroom = true; //variabile che serve per l'upload immagine per fare uploadare solo 1 immagine e sovrascrivere se c'e ne gia una presente
var tab_menu_vert_height = null;
var tab_content_vertical_height = null;
var diff = null;

function setTabHeight() {
    if (tab_menu_vert_height > tab_content_vertical_height) {
        $('.tab-content-vertical ul').css('height', tab_menu_vert_height + 'px')
    }
}

$(document).ready(function() {
    $('a.balloon-edit').click(function() {
        if (form_target === null) {
            var button = $(this);
            form_target = $(this).attr('form_target');
            onEdit();
            $(this).closest('ul').show();
            var url = $(this).attr('url');
            $(this).append('<div id="preloader-balloon">&nbsp;</div>');
            $(this).find('span').removeClass('edit');
            spinnerButBlack.spin();
            $('#preloader-balloon').append(spinnerButBlack.el);
            $.post(url, {}, function(html) {
                spinnerButBlack.stop();
                removeObj('#preloader-balloon');
                button.find('span').addClass('edit');
                showBalloon(button, html);
            }).error(function() {
                form_target = null;
                apriModifica();
                spinnerButBlack.stop();
                removeObj('#preloader-balloon');
                button.find('span').addClass('edit');
            });
        }
    });

//    tab_menu_vert_height = $('.tab-menu-vertical').outerHeight();
//    tab_content_vertical_height = $('.tab-content-vertical').outerHeight();

    setTabHeight();
//    console.log(tab_menu_vert_height);
//    console.log(tab_content_vertical_height);
//    console.log(diff);
    
    
    //Click per mostrare le infomazioni dello showroom, quali telefono cellulare ecc
    $(".contatti_showroom").click(function() {
        mostraInformazioni($(this));
    });

    chiudiModifica();
});

/**
 * OperatoriBundle/Resources/views/Showroom/home/contatti/contatti.html.twig
 * splitto l'attributo del this che è fatto cosi : attr="cellulare-{{showroom.id}}
 * prendendo la chiave (cellulare) e l'id dello showroom con shift e pop
 * e facico una chiamata ajax a OperatoriBundle/Controller/ShowroomController.php
 * 
 * @param {object} target è il This del Pulsante
 * @returns {undefined}
 */
function mostraInformazioni(target) {
    $(target).append('<div id="preloader">&nbsp;</div>');
    spinnerBut.spin();
    $('#preloader').append(spinnerBut.el);
    
    $.ajax({
        type: "POST",
        url: Routing.generate('mostra_informazioni_showroom'),
        data: {informazione_richiesta: target.attr('attr').split("-").shift(), id_showroom : target.attr('attr').split("-").pop()}, //{ name: "John", location: "Boston" }
        dataType: "html",
        
        beforeSend: function() {
            target.removeAttr('onclick');// Non lo faccio piu cliccare
            //beforeElemento(target, '<p class="feedback_form">' + Translator.get('messages:caricamento') + '</p>', 'slow');
        },
        success: function(msg) {
            msg = $.parseJSON(msg);
            //eliminaElemento(".feedback_form", 2000);
            if (msg.status == "OK") {                
                //$(".feedback_form").html(Translator.get('messages:success'));
                eliminaElemento(target,500);
                afterElemento(target, '<p class="margin-top-5 left">' + msg.result + '</p>', 'slow');
                spinnerBut.stop();
                removeObj('#preloader');
            } else {
                //$(".feedback_form").html(Translator.get('messages:error'));
                spinnerBut.stop();
                removeObj('#preloader-balloon');
            }    
            //creaElemento();
            //$("#contenitore_cer_" + n.id.split("-").pop()).html(msg);
        },
        error: function(msg) {
            //$(".feedback_form").html(Translator.get('messages:error'));
            eliminaElemento(".feedback_form", 2000);
            spinnerBut.stop();
                removeObj('#preloader');
        }
    });
}


/**
 * Apre il balloon relativo al pulsante
 * 
 * @param Object elem elemento jquery
 * @param string html form da inserire
 * @returns none
 */
function showBalloon(elem, html) {
    last_elem = elem;
    last_form_submit = '';
    // fix sulle Y da rivedere matematicamente

    var obj = $('#balloon');
    obj.html(html);

    var offset = elem.offset();
    var y = offset.top;
    var x = offset.left;
    var h = $('#header').height();
    var button_offset = $('.editing-tools').height();
    var w = parseInt(obj.children('div').attr('w'), 10);
    var dx = parseInt(obj.children('div').attr('dx'), 10);
    var top = y - h + button_offset;
    var left = x - w + dx;

    obj.children('div').css({
        top: top + "px",
        left: left + "px"
    });
    $('.form-balloon').css({width: w + 'px'});
    obj.show();
}
/**
 * Calcola la nuova posizione del balloon dopo il resize della finestra
 */
function resizeBalloon() {
    if (last_elem !== null) {
        elem = last_elem;
        var obj = $('#balloon');
        var offset = elem.offset();
        var y = offset.top;
        var x = offset.left;
        var h = $('#header').height();
        var button_offset = $('.editing-tools').height();
        var w = parseInt(obj.children('div').attr('w'), 10);
        var dx = parseInt(obj.children('div').attr('dx'), 10);
        var top = y - h + button_offset;
        var left = x - w + dx;

        obj.children('div').css({
            top: top + "px",
            left: left + "px"
        });
    }
}
// sposta il balloon con il resize della finestra
$(window).resize(function() {
    resizeBalloon();
});

/**
 * Funzione di invio dato form nei balloon
 */
function balloon_form_submit(elem, close) {
    form = $('#form_balloon');
    //console.log(form);
    //console.log(form.attr('action'));
    callback = form.attr('cb');
    validate = form.attr('vd');
    if (last_form_submit !== form.attr('id')) {
        last_form_submit = form.attr('id');
        ok = true;
        if (validate) {
            eval('ok = ' + validate + '();');
        }
        if (ok) {
            //elem.append('<div id="preloader-balloon">&nbsp;</div>');
            creaElemento($("#form_balloon").find(".baloon-save span"), '<div id="preloader-balloon">&nbsp;</div>', 500);
            spinnerButBlack.spin();
            $('#preloader-balloon').append(spinnerButBlack.el);
            $(".baloon-save span").removeClass('ok');
            $.post(form.attr('action'),
                    form.serialize(),
                    function(data) {
                        $('#' + form_target).html(data).effect("highlight", {}, 3000);
                        if (close) {
                            $('#balloon').hide();
                        }
                        spinnerButBlack.stop();
                        removeObj('#preloader-balloon');
                        $(".baloon-save").find('span').addClass('ok');
                        apriModifica();
                        form_target = null;
                        if (callback) {
                            eval(callback + '();');
                        }
                    });
        } else {
            last_form_submit = '';
        }
    }
}

/**
 * Entra in modifica di una sezione
 */
function enableBalloonForm() {
//    $('.baloon-save').click(function(){
//        balloon_form_submit($(this), true);
//    });
    $('.baloon-cancel').click(function() {
        $('#balloon').hide();
        apriModifica();
        form_target = null;
        last_form_submit = '';
    });
}
/**
 * Entra in modifica di una sezione
 */
function chiudiModifica() {
    modifica = 'si';
    $('.editing-tools').hide();
    $('.pre-edit').show();
    $('.in-edit').hide();
    $('.box_foto').hide();
    $('.on-show').show();
    $('.on-edit').hide();
    $('.defocus').css({ opacity: 1 });
    $('#balloon').hide();
    form_target = null;
}
/**
 * Entra in modifica di una sezione
 */
function onEdit() {
    $('.editing-tools').hide();
    $('.pre-edit').show();
    $('.in-edit').hide();
    $('.box_foto').hide();
}
/**
 * Esce dalla modifica di una sezione
 */
function apriModifica() {
    modifica = 'no';
    $('.editing-tools').show();
    $('.pre-edit').show();
    $('.in-edit').hide();
    $('.box_foto').show();
    $('.on-show').hide();
    $('.on-edit').show();
    $('.defocus').css({ opacity: 0.35 });
    $('#balloon').html('');
}

/**
 * * Mostra la tipologia della categoria cliccata
 * @param {type} n è il this ovvero il pulsante
 * @returns {undefined}
 */
function mostraTipologia(n) {
    //alert(n.id.split("-").pop());
    $.ajax({
        type: "POST",
        url: Routing.generate('tipologia_categoria'),
        data: {id_categoria: n.id.split("-").pop()},
        dataType: "html",
        beforeSend: function() {
            $("#" + n.id).removeAttr('onclick');// Non lo faccio piu cliccare
            beforeElemento(n, '<p class="feedback_form">' + Translator.get('messages:caricamento') + '</p>', 'slow');
        },
        success: function(msg) {
            eliminaElemento(".feedback_form", 2000);
            $("#contenitore_cer_" + n.id.split("-").pop()).html(msg);
        },
        error: function(msg) {
            $(".feedback_form").html(Translator.get('messages:error'));
            eliminaElemento(".feedback_form", 2000);
        }
    });

}

function mostraContenutoRecuperabili(radice, slug, n) {
    $.ajax({
        type: "POST",
        url: Routing.generate(radice, {slug: slug}),
        dataType: "html",
        beforeSend: function() {
            $("#" + n.id).removeAttr('onclick');// Non lo faccio piu cliccare
            beforeElemento(n, '<p class="feedback_form2">' + Translator.get('messages:caricamento') + '</p>', 'slow');
        },
        success: function(msg) {
            eliminaElemento(".feedback_form2", 2000);
            $("#contenitore_cer_tipologia-" + n.id.split("-").pop()).html(msg);

            $('.lps-tab > ul.tab-menu > li > a').click(function() {
                switch_tabs($(this), '.tab-content-lps');
            });

            $('.lps-tab-sheet > ul.tab-menu > li > a').click(function() {
                switch_tabs($(this), '.tab-content-lps-sheet');
            });

        },
        error: function(msg) {
            $(".feedback_form2").html(Translator.get('messages:error'));
            eliminaElemento(".feedback_form", 2000);
        }
    });


}
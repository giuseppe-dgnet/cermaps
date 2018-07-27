mode = 'list';
var isScrollTo = false;
var isOpen = false;
var isSend = false;
var isChange = false;
var isAdd = false;
var arrData = [];
var invio = false;
filtri = {
    servizi: true, 
    mps: true, 
    cer: true, 
    rdi: true, 
    post:true,
    system:true
};

tipo = {
    spam:false,
    read:null,
    stored:false,
    send:null
};
var callback_scroll_result = 'attivaJsListing';
var sezione_attiva = ' in Tutti i Messaggi';
var stack_chiamate_pre_notifiche = new Array();

/**chiamate
 * Variabili per la gestione dell'aggiornamento automatico
 */
var aggiorna_centro_messaggi = true;
var aggiorna_discussione = false;
var aggiornamento_attivo = true;

var link_attivi = [];

slider = null;

$('document').ready(function(){
    
    //Variabili del Form baloon invito a Rubrica utente non ecoseekr
    riattivaInvitoRubirca();
    
    checkVariabili(['url_aggiungi_rubrica_invito','url_email','invio_filtro','tutti_messaggi_utente','cerca_messenger','disabilita_spam','segna_spam','disarchivia_discussione','segna_da_leggere','segna_letto','archivia_discussione','messenger_filtro','rubrica_aggiungi','rubrica_elimina','contatti','leggi_discussione']);
    
    checkboxFiltri();
    // Attivazione filtri di ricerca
    $('.cb-filtro').click(function(){
        elem = $(this).children('input');
        if(elem.attr('readonly')) {
            elem.attr('readonly', false);
            elem.attr('checked', true);
        }
        if(elem.attr('checked')) {
            gotoStato = 'true';
        } else {
            gotoStato = 'false';
        }
        switchStato(elem, eval(elem.val()), gotoStato);
    });
    
    var timer;
    var timer_running = 0;
    var click_tendina = false;
    
    $('.filtro-tendina').click(function(){
        $('#list_filter').fadeOut('slow');
        sezione_attiva = ' in '+$(this).children().text();
        click_tendina = true;
        //if(timer_running ==1){
        clearTimeout(timer);               
        timer_running =0
        // }
        aggiorna_json($(this));
    });
    
    attivaJsListing();
    
    $('#baloon_scm').mouseleave(function(){
        $(this).fadeOut();
    });
    
    //Apertura ricerca avanza, bind unbind previene l'aperura e chiusura ripetuta
    //chiusura automatica al leave dopo 2000 ms
    var handler = function() {
        $('#select_filter').unbind('click', handler);
        $('#list_filter').slideToggle('slow', function() {
            
            if(timer_running ==1){
                clearTimeout(timer);               
                timer_running =0
            }
           
            $("ul.message-filter").mouseover(function(){
                if(timer_running ==1){
                    clearTimeout(timer);               
                    timer_running =0;
                    click_tendina = false;
                }
            }).mouseleave(function(){
                if(timer_running ==0 && click_tendina == false) {                   
                    timer_running = 1;                    
                    timer = setTimeout('chiusura_tendina('+timer+','+timer_running+')',2000);
                }
                click_tendina = false;
            });
            
            $('#select_filter').bind('click', handler);
        });
    };
        
    $('#select_filter').bind('click', handler);
    
    $('li#sezione').append(sezione_attiva);
    
    cerca_autocomplete();
    search = $('#ricerca_messaggi');

    $( "#ricerca_messaggi" ).click(function(){
        $(this).val("");
    });
    
    search.keyup(function(e) {
        if(e.keyCode == 13) {
            if($(this).val().length >=2) {
                
                invio = true;
                var testo  = $(this).val();            
                spinnerDef.spin();
                $('#preloader').append(spinnerDef.el);
                ricerca_invio(testo,$(this));                       
            }
        }
    });
     
    //    if( $(".listing-result .discussione").length == 10) {
    //        $('#more-result').show();
    //    }else{
    //        $('#more-result').hide();
    //    }
    
    $("#baloon-favoriti-close").click(function () {
        $("#favoriti_baloon").hide();
    });
    
    
});


function riattivaInvitoRubirca() {
    var email = $('#email_invito_rubrica');
    var nome = $('#nome_invito_rubrica');
    var cognome = $('#cognome_invito_rubrica');
    var azienda = $('#azienda_invito_rubrica');

    campi_obbligatori = [
        nome, 
        cognome, 
        email,
    
    ];
    
    colleghi.each(function(n) {
        $('#delete_'+n).remove();
    });
    
    //invita un utente non ecoseekr e lo inserisce in Rubrica
    check_email = true;
    email.change(function() {
        // console.log("change email2");
        $.post(url_email, {
            email: email.val()
        }, function(json){
            
            $(nome).attr('readonly', false);                
            $(cognome).attr('readonly', false);                    
            $(azienda).attr('readonly', false);  
            $('#nome_invito_rubrica').val("")
            $('#cognome_invito_rubrica').val("");
            $('#azienda_invito_rubrica').val("");
            //removeObj('.form-alert')
            $('.form-alert').html('');
            check_email = true;
            o = JSON.parse(json);
            if(o.t == 'a') {                
                // setCampiObbligatori(campi_obbligatori, 'attivaInvio');
                $('.form-alert').append('L\'e-mail è già registrata<br/>da un\'azienda</div>');
                nome.val(o.n);
                $(nome).attr('readonly', true);
                cognome.val(o.c);
                $(cognome).attr('readonly', true);                
                azienda.val(o.ac);
                $(azienda).attr('readonly', true);          
                check_email = false;
            }
            if(o.t == 'p') {                
                //setCampiObbligatori(campi_obbligatori, 'attivaInvio');
                $('.form-alert').append('L\'e-mail è già registrata<br/>da un professionista</div>');
                nome.val(o.n);
                $(nome).attr('readonly', true);
                cognome.val(o.c);
                $(cognome).attr('readonly', true);
                $(azienda).attr('readonly', true); 
                check_email = false;
            }
            if(o.t == "" && o.i != "") {   
                $('.form-alert').append('Questo utente è già stato invitato, è in attesa di registrazione</div>');
                nome.val(o.n);
                $(nome).attr('readonly', true);
                cognome.val(o.c);
                $(cognome).attr('readonly', true);                
                azienda.val(o.ac);
                $(azienda).attr('readonly', true);                
                check_email = false;
            }
        })
    });
    
    setCampiObbligatori(campi_obbligatori, 'attivaInvio');
    
    var form_email = email.closest('form');
    invito_rubrica(form_email);

    $("#aggiungi_rubrica").click(function(){                
        baloon_rubrica();
    });
}

function attivaInvio(show) {
    if(check_email) {
        if(show) {        
            $('#submit_invito_rubrica').css({
                opacity: 1
            }).attr('disabled', false).html('Invia l\'invito &raquo;');
       
        } else {        
            $('#submit_invito_rubrica').css({
                opacity: 0.5
            }).attr('disabled', true).html('Tutti i campi sono obbligatori');
        }
    }
}


function baloon_rubrica() {
    $("#invito_rubrica_baloon").toggle();
}

function baloon_preferiti() {
    $("#favoriti_baloon").toggle();
}



function invito_rubrica(form_email) {
    var form_submit = false;
    $('#submit_invito_rubrica').click(function(){        
        if(check_email) {
            if(!form_submit) {
                submit = true;
                if(!checkEmail($('#email_invito_rubrica').val())) {
                    $('.form-alert').html('L\'e-mail non è corretta</div>');
                    submit = false;
                }
                //                if($('#nome_invito_rubrica').val().trim().length < 2 || $('#nome_invito_rubrica').val().toLowerCase() == 'nome') {
                //                    $('.form-alert').html('Nome non valido</div>');
                //                    submit = false;
                //                }
                //                if($('#cognome_invito_rubrica').val().trim().length < 2 || $('#cognome_invito_rubrica').val().toLowerCase() == 'cognome') {
                //                    $('.form-alert').html('Cognome non valido</div>');
                //                    submit = false;
                //                }
                
                
                if(submit) {                    
                    $.ajax({
                        url: url_aggiungi_rubrica_invito,
                        data: form_email.serialize(),
                        method: 'POST',
                        beforeSend:function() {
                            $('#submit_invito_rubrica').css({
                                opacity: 0.5
                            }).attr('disabled', true).html('Invio Email');
                        },
                        success: function(msg) {
                            msg = JSON.parse(msg);

                            if(msg.status=='OK') {
                                aggiungiRubrica(msg.id_utente);
                                
                                $("#status_invito").show("fast", function () {
                                    $(this).text("Email Invitata correttamente");
                                    $("#invito_rubrica_baloon").delay(2000).toggle('normal', function() {
                                        $("#status_invito").hide();
                                    });
                                });
                                
                                
                            } else {
                                $("#status_invito").show("fast", function () {
                                    $(this).text("Non è stato possibile inviare l'email");
                                    $("#invito_rubrica_baloon").delay(2000).toggle('normal', function() {
                                        $("#status_invito").hide();
                                    });
                                });
                            }
                            
                            $('#email_invito_rubrica').val("");
                            $('#nome_invito_rubrica').val("")
                            $('#cognome_invito_rubrica').val("");
                            $('#azienda_invito_rubrica').val("");
                            $('#submit_invito_rubrica').html('Tutti i campi sono obbligatori');
                            $("#status_invito").html("");
                        },
                        error: function(msg) {
                            $('#email_invito_rubrica').val("");
                            $('#nome_invito_rubrica').val("")
                            $('#cognome_invito_rubrica').val("");
                            $('#azienda_invito_rubrica').val("");
                            $("#status_invito").show("fast", function () {
                                $(this).text("Non è stato possibile inviare l'email");
                                $("#invito_rubrica_baloon").delay(2000).toggle('normal', function() {
                                    $("#status_invito").hide();
                                });
                            });
                        }
                    });
                }
                
            }         
        }
    });
}


function ricerca_invio(testo,tendina) {
    
    $.ajax({
        url: invio_filtro,
        dataType: "html",
        data: {
            maxRows: 10,
            name_startsWith: testo
        },
        success: function( data ) {    
            if(data.length > 0) {
                $('.listing-result').html(data);            
                spinnerDef.stop();
                $('#preloader').remove();
                
                if( $(".listing-result .discussione").length == 10) {
                    $('#more-result').show();
                }else{
                    $('#more-result').hide();
                }
        
            }else{
                spinnerDef.stop();
                $('#preloader').remove();               
                fancyAlert('Nessun Risultato');
            }
            tendina.autocomplete( "close" );
            attivaJsListing();
        }
        
    });
}


function cerca_autocomplete() {
    
    $( "#ricerca_messaggi" ).autocomplete({
   
        source: function( request, response ) {           
            $('.search-mess-area').append('<div id="preloader">&nbsp;</div>');
            spinnerDef.spin();
            $('#preloader').append(spinnerDef.el);
            if(invio == false) {
                $.ajax({
                    url: cerca_messenger,
                    dataType: "json",
                    data: {
                        maxRows: 10,
                        name_startsWith: request.term
                    },
                    success: function( data ) {  
                        
                        spinnerDef.stop();
                        $('#preloader').remove();
                        response( $.map( data, function( item ) {
                            return {
                                label: item.id,
                                value: item.label,
                                slug: item.slug
                            }
                        }));
                    }
                });
            }
            invio = false;
        },
        minLength: 2,
        select: function( event, ui ) {
            //            alert(ui.item.label);
            //            alert(ui.item.slug);
            if(ui.item.slug){
                apriDiscussione(ui.item.slug);
            }
        },
        open: function() {
            $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
        },
        close: function() {
            $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
        }
    }); 
}

/**
 * Chiamata ajax per segnare messaggi letto/non letto
 */
var cambia_stato = false;
function segna_letto_discussione() {
    var action;
    $('.readstate').click(function(){   
        if(!cambia_stato) {
            cambia_stato = true;
            if($(this).attr('letto') == 'n') {
                action = segna_letto;
                $(this).removeClass('readstate-ok-mess');
                $(this).addClass('readstate-mess');
                $(this).parent().find("#archivia").show();                
                $(this).attr('letto', 's');
            } else {
                action = segna_da_leggere;
                $(this).removeClass('readstate-mess');
                $(this).addClass('readstate-ok-mess');
                $(this).attr('letto', 'n'); 
                $(this).parent().find("#archivia").hide();                
            }
            stack_chiamate_pre_notifiche.add($(this).closest('li').attr('id').from(2));
            cambia_stato_discussione($(this).closest('li').attr('id').from(2),action,'read');
        }
    });
}

/**
 * Chiamata ajax per l'archiviazione/disarchiviazione messaggi
 */
function attiva_archiviazione() {
    var action;
    $('.storestate').click(function(){  
        if(!cambia_stato) {
            cambia_stato = true;
            if(!tipo["stored"]) {
                action = archivia_discussione;
                $(this).removeClass('recover-mess');
                $(this).addClass('store-mess');
            }else{
                action = disarchivia_discussione;    
                $(this).removeClass('recover-mess');
                $(this).addClass('store-mess');
            }
            cambia_stato_discussione($(this).closest('li').attr('id').from(2),action,'stored');
        }
    });
}

/**
 * Chiamata ajax per mettere/togliere messaggi dallo spam
 */
function attiva_spam() {
    var action;
    $('.spamstate').click(function(){  
        if(!cambia_stato) {
            cambia_stato = true;
            if(!tipo["spam"]) {
                action = segna_spam;
                $(this).removeClass('no-spam-mess');
                $(this).addClass('span-mess');
            }else{
                action = disabilita_spam;
                $(this).removeClass('no-span-mess');
                $(this).addClass('span-mess');
            }
            cambia_stato_discussione($(this).closest('li').attr('id').from(2),action,'spam');
        }
    });
}

/**
 * Funzione ajax per modificare lo stato di una discussione
 */
function cambia_stato_discussione(id,action,area) {
    if(!isChange) {
        isChange = true;
        if(tipo[area] != null) {  
            aggiorna_body_messaggi = false;
        }
        $.post(action, {
            slug: id
        }, function(msg) {
            if(msg == "ok") {
                if(tipo[area] != null) {  
                    $("#d_"+id).animate({
                        opacity: 0.5
                    }, 500, function() {
                        $("#d_"+id).slideUp("slow", function(){
                            $(this).remove();
                        });
                    });           
                }     
                if(area == 'read') {  
                    stack_chiamate_pre_notifiche.remove(id);
                    if(stack_chiamate_pre_notifiche.isEmpty()) {
                        aggiorna_body_messaggi = true;
                        checkNuoviMessaggi();
                    }
                    if(action == segna_letto) {
                        $('#d_'+id).removeClass('da-leggere');
                    } else {
                        $('#d_'+id).addClass('da-leggere');
                    }
                }
            }
            isChange = false;
            cambia_stato = false;
        });
    }
}

/**
 * timer per la chiusura della tendina
 */
function chiusura_tendina(timer,timer_running) {    
    $('#list_filter').fadeOut();
    timer_running = 0;
    clearTimeout(timer);
}

/**
 * Attiva gli hook per aggiungere/togliere contatti dalla rubrica
 */
function attivaAggiungiRubrica() {
    $('.add-rubrica').click(function(){
        id = $(this).attr('rel');
        aggiungiRubrica(id);
    }).show();
    contatti.forEach(function(a) {
        $(".rubrica_"+a).hide();
        //event.preventDefault();
    });
}

/**
 * Chiamata ajax per aggiungere a rubrica
 */
function aggiungiRubrica(id) {
    if(!isAdd) {
        isAdd = true;
        $.post(rubrica_aggiungi, {
            contatto:id
        },function(msg){                
            contatti.add(id);
            $("#rubrica").html(msg);
            $(".rubrica_"+id).hide();           
            isAdd = false;
            riattivaInvitoRubirca();
        }).error(function(){
            isAdd = true;
        });
    }
}

/**
 * Chiamata ajax per rimuovere dalla rubrica
 */
function eliminaRubrica(id) {
    if(!isAdd) {
        isAdd = true;
        $.post(rubrica_elimina, {
            contatto:id
        },function(msg){
            //alert(msg);
            contatti.remove(id);
            contatti.remove(''+id);
            $("#rubrica").html(msg);
            $(".rubrica_"+id).show();
            isAdd = false;
            riattivaInvitoRubirca();
        }).error(function(){
            alert('Si è verificato un errore, riprova più tardi.')
            isAdd = false;
        });
    }
}

/**
 * Cambia stato ai filtri e aggiorna i risultati
 */
function switchStato(elem, elenco, nuovo_stato) {
    Object.each(elenco, function(key, value) {
        eval('filtri.'+value+' = '+nuovo_stato+';');
    });
    elem.attr('checked', eval(nuovo_stato));
    checkboxFiltri();
    aggiorna();
}

/**
 * re-inizializzo i tipi, in modo da rimettere tutto da default
 * quando vado da una sezione a un altra
 */
function init_tipo() {
    
    tipo = {
        spam:false,
        read:null,
        stored:false,
        send:null
    };
 
}

function init_filtri() {
    filtri = {
        servizi: true, 
        mps: true, 
        cer: true, 
        rdi: true, 
        partner:true
    };
}


/**
 * Chiamata per aggiornare i risultati dopo la selezione da menu a tendina
 */
function aggiorna_json(voce_selezionata) { 
    init_tipo();
    var _tipo = JSON.parse(voce_selezionata.attr('tipo'));
    Object.each(_tipo, function(key, value) {
        //alert(key+" "+value);
        tipo[key] = value;        
    });
    
    show(eval(voce_selezionata.attr('filtri')));
}

/**
 * Chiamata per reload automatico del listing da click automatico su Centro Messaggi
 */
function show(elenco) {
    if(elenco.length > 0) {
        Object.each(filtri, function(key, value) {
            eval('filtri.'+key+' = false;');
        });

        elenco.forEach(function(elem){
            eval('filtri.'+elem+' = true;');
        });
    }
    
    checkboxFiltri();
    aggiorna();
}

/**
 *
 */

function tuttiMessaggi(id) {
    blocca_aggiornameto_messaggi = true;
    spinnerMessengerAjaxStart();
    $('.listing-result').html('');
    init_tipo();
    init_filtri();
    $.post(tutti_messaggi_utente, {
        id_utente: id
    }, function(msg){
        $('.listing-result').html(msg);
        $('#more-result-container').hide();
        attivaJsListing();
        spinnerMessengerAjaxStop();
        //$('#row_filtri').show();
        $('#row_subject').hide();
        $('#titolo_pagina').text('Messaggi ed Email');
        
        
    });
    
}

/**
 * Aggiorna il listing
 */
function aggiorna() {
    $('#more-result-container').hide();
    isOpen = false;
    blocca_aggiornameto_messaggi = false;
    attesa_notifiche_messaggi = true;
    aggiornamento_attivo = false;
    aggiorna_body_messaggi = false;
    scroll_available = true;
    spinnerMessengerAjaxStart();

    $('.listing-result').html('');
    data = {
        filtri: filtri, 
        tipo: tipo
    };
    $.post(messenger_filtro, data, function(mgs){
        link_attivi = [];
        $('.listing-result').html(mgs);
        $('#more-result-container').show();
        aggiornaTempiInvio();
        spinnerMessengerAjaxStop();
        $('#row_subject').hide();
        $('#titolo_pagina').text('Centro messaggi');
        
        $('li#sezione').append(sezione_attiva);
        aggiorna_discussione = false;

        if(mode != 'list') {
            $('.msg_button').toggle();
            mode = 'list';
        }
        aggiornamento_attivo = true;
        attesa_notifiche_messaggi = false;
        $('#more-result-container').show();
        
        //mostra il pulsante piu risultati, se abbiamo piu di 10
        if( $(".listing-result .discussione").length == 10) {
            $('#more-result').show();
        }else{
            $('#more-result').hide();
        }
        aggiornaTempiInvio();
        attivaJsListing();
    });
}

/**
 * Chiamate alle funzioni di attivazioni jquery su listing
 */
function attivaJsListing() {
    attivaBaloon();
    attivaAggiungiRubrica();
    attivaRollOver();
    attivaLettura();
    attiva_archiviazione();
    attiva_spam();
    attivatiptip();
    segna_letto_discussione();
    
}

/**
 * Partenza spinner
 */
function spinnerMessengerAjaxStart() {
    $('.tab-content .coldx-listing').append('<div id="preloader-util" style="top: 50px;">&nbsp;</div>');
    spinnerBigDef.spin();
    $('#preloader-util').append(spinnerBigDef.el);
}
/**
 * Arresto spinner
 */
function spinnerMessengerAjaxStop() {
    spinnerBigDef.stop();
    $('#preloader-util').remove();
}

/**
 * Cambia lo stato di una variabile (passata come stringa) dei filtri
 */
function cambiaStatoFiltro(filtro) {
    variabile = !eval(filtro);
    eval(filtro+' = '+variabile);
    setStatoFiltro(filtro);
    return variabile;
}

/**
 * Imposta la visualizzazione di una filtro a senconda del valore della variabile (passata come stringa)
 */
function setStatoFiltro(filtro) {
    variabile = eval(filtro);
    switch(filtro.at(0)) {
        case 'f':
            if(variabile) {
                $('#bt_'+filtro).animate({
                    opacity: 1
                }, 250);
            } else {
                $('#bt_'+filtro).animate({
                    opacity: 0.33
                }, 250);
                break;
            }
        case 's':
            if(variabile) {
                $('#cb_'+filtro).attr('checked', 'checked');
            } else {
                $('#cd_'+filtro).removeAttr('checked');
                break;
            }
    }
}

/**
 * Abilita la lettura di una discussione da click sul titolo
 */
function attivaLettura() {
    $('.goto_discussione').click(function(){
        $('.listing-result').html('');
        slug = $(this).attr('id');
        apriDiscussione(slug)
        event.preventDefault();
    });
}

/**
 * Chiamata ajax per apertura discussione
 */
function apriDiscussione(slug) {
    $('#more-result-container').hide();
    if(!isOpen) {
        scroll_available = false;
        isOpen = true;
        blocca_aggiornameto_messaggi = false;
        aggiorna_discussione = slug;
        attesa_notifiche_messaggi = true;
        aggiorna_body_messaggi = false;
        spinnerMessengerAjaxStart();
        $.post(leggi_discussione, {
            slug: slug
        }, function(msg){
            spinnerMessengerAjaxStop();
            $('.listing-result').html(msg);
            isScrollTo = true;
            attivaJsDiscussione(slug);
            attesa_notifiche_messaggi = false;
            checkNuoviMessaggi();
            aggiornaTempiInvio();
            event.preventDefault();
            attivaBaloon();
            attivaAggiungiRubrica();
        }).error(function(){
            isScrollTo = false;
            attesa_notifiche_messaggi = true;
            alert('Si è verificato un errore, riprova più tardi')
        });
    }
}
/**
 * Chiamate alle funzioni di attivazioni jquery su discussione
 */
function attivaJsDiscussione(slug) {
    $('#messaggio_slug').val(slug);
    abilitaFormRisposta();
    if(isScrollTo) {
        scrollTo('.message-insert',1000);
    }
    isScrollTo = false;    
    //setFixedFloat(headerListingMarginTopHeight, allegati);
    //setFixedFloat(headerListingMarginTopHeight, dashboardbox);
    $("textarea").autoGrow();
    $('#messaggio_testo_risposta').focus();
}

/**
 * Chiamata ajax per iniziare una nuova discussione
 */
function nuovaDiscussione() {
    scroll_available = false;
    blocca_aggiornameto_messaggi = false;
    aggiornamento_attivo = false;
    aggiorna_body_messaggi = false;
    attesa_notifiche_messaggi = true;
    isSend = false;
    mode = 'new';

    $('#nuovo_messaggio').append('<div id="preloader" style="right: 15px;">&nbsp;</div>');
    spinnerBut.spin();
    $('#preloader').append(spinnerBut.el);
    $.post(nuova_discussione, {}, function(msg){
        spinnerBut.stop();
        $('#preloader').remove();
        $('.listing-result').html(msg);
        abilitaFormNuova();
        //scrollTo('.message-insert',3000);
        $("#messaggio_destinatari").tokenInput(messenger_destinatari,{
            preventDuplicates: true,
            hintText: 'Cerca tra i tuoi contatti',
            noResultsText: 'Nessun contatto trovato',
            searchingText: 'Ricerca in corso',
            onAdd: function (item) {
                ids = eval($('#messaggio_destinatari_id').val());
                ids.add(item.id);
                //alert(item.id);
                $('#messaggio_destinatari_id').val(serialize(ids));
                //alert($('#messaggio_destinatari_id').val(serialize(ids)));
            },
            onDelete: function (item) {
                ids = eval($('#messaggio_destinatari_id').val());
                ids.remove(item.id);
                $('#messaggio_destinatari_id').val(serialize(ids));
            },
            theme: 'facebook'
           
        });
        
        fn_check_email();
        $("textarea").autoGrow();
        attesa_notifiche_messaggi = false;;
    });
}


function fn_check_email() {
    var subject_email  =  $('#messaggio_subject');
    var messaggio_testo_risposta  =  $('#messaggio_testo_risposta');

    setCampiObbligatori([
        subject_email, 
        messaggio_testo_risposta,         
    ], 'attivaInvioEmail');
}


function attivaInvioEmail(show) {
    if(show) {
        $('#submit_mess').css({
            opacity: 1
        }).attr('disabled', false).html('Invia');
    } else {
        $('#submit_mess').css({
            opacity: 0.5
        }).attr('disabled', true).html('Invia');
    }
}

/**
 * Chiamata ajax per iniziare una nuova discussione
 */
function nuovoMessaggio(id) {
    aggiornamento_attivo = false;
    aggiorna_body_messaggi = false;
    attesa_notifiche_messaggi = true;
    isSend = false;
    scroll_available = false;
    mode = 'new';
    $('#nuovo_messaggio').append('<div id="preloader" style="right: -40px;">&nbsp;</div>');
    spinnerDef.spin();
    $('#preloader').append(spinnerDef.el);
    if(is_int(id)) {
        prepopola = [{
                id: id, 
                name: $('#cn_'+id).text()
            }];
    } else {
        prepopola = id;
    }
    
    $.post(nuova_discussione, {}, function(msg){
        spinnerDef.stop();
        $('#preloader').remove();
        $('.listing-result').html(msg);
        
        abilitaFormNuova();
        //scrollTo('.message-insert',3000);
        $("#messaggio_destinatari").tokenInput(messenger_destinatari,{
            preventDuplicates: true,
            hintText: 'Cerca tra i tuoi contatti',
            noResultsText: 'Nessun contatto trovato',
            searchingText: 'Ricerca in corso',
            prePopulate: prepopola,
            onAdd: function (item) {
                ids = eval($('#messaggio_destinatari_id').val());
                ids.add(item.id);
                $('#messaggio_destinatari_id').val(serialize(ids));
            },
            onDelete: function (item) {
                ids = eval($('#messaggio_destinatari_id').val());
                ids.remove(item.id);
                $('#messaggio_destinatari_id').val(serialize(ids));
            },
            theme: 'facebook'
        });
        $("textarea").autoGrow();
        ids = [];
        prepopola.each(function(pp){
            ids.add(pp.id);
        });
        
        $("#messaggio_destinatari_id").val(serialize(ids));
        fn_check_email();
        attesa_notifiche_messaggi = false;
    });
}

/**
 * Abilita comportamenti sull'onsubmit del form di risposta
 */
function abilitaFormRisposta() {
    $('#messaggio_testo_risposta').keyup(function(){
        if($(this).val().trim() == '') {
            aggiornamento_attivo = true;
        } else {
            aggiornamento_attivo = false;
        }
    });
    $('#reply_form').submit(function(){
        if(!isSend) {
            isSend = true;
            $('#submit_mess').html('Attendere&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
            $('#submit_mess').append('<div id="preloader-button">&nbsp;</div>');
            spinnerBut.spin();
            $('#preloader-button').append(spinnerBut.el);
            $.post($(this).attr('action'), $(this).serialize(), function(msg){
                $('#reply').before(msg);
                $('#messaggio_testo_risposta').val('');
                $('#messaggio_allegati').val('[]');
                $('#docs').html('');
                spinnerBut.stop();
                removeObj('#preloader-button');
                $('#submit_mess').html('Invia');
                aggiornamento_attivo = true;
                isSend = false;
            });
        }
        return false;
    });
    attivaAllegati();
}

/**
 * Abilita comportamenti sull'onsubmit del form di nuova discussione
 */
function abilitaFormNuova() {
    $('#more-result-container').hide();
    aggiornamento_attivo = false;
    $('#new_form').submit(function(){
        if(!isSend) {
            isSend = true;
            var destinatari_email  =  $('#messaggio_destinatari_id');

            if(!(eval(destinatari_email.val()).isEmpty())) {
                $('#submit_mess').html('Attendere&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
                $('#submit_mess').append('<div id="preloader-button">&nbsp;</div>');
                spinnerBut.spin();
                $('#preloader-button').append(spinnerBut.el);
                $.post($(this).attr('action'), $(this).serialize(), function(msg){
                    spinnerBut.stop();
                    removeObj('#preloader-button');
                    aggiorna();
                    aggiornamento_attivo = true;
                });
            }else{
                fancyAlert('Inserisci almeno un destinatario');
            }
        }
        return false;
    });
    attivaAllegati();
}

/**
 * Comportamenti D&D per gli allegati
 */
function attivaAllegati() {
    $('#reply_form').mouseleave(function() {
        $('#allegati_drag_drop').hide();
    });
}

/**
 * Attiva i baloon
 */
function attivaBaloon() {
    $('.baloon').click(function(){
        obj = '#baloon_scm';
        $(obj);

        diffY = 30;
        diffX = -5;

        var offsetLi = $(this).offset();
        var offsetUl = $(this).closest('ul').offset();

        var tPosY = offsetLi.top + diffY;
        var tPosX = offsetUl.left + diffX;

        $(obj)
        .html($('#'+$(this).attr('rel')).html())
        .show()
        .children().css({
            "top": + tPosY +"px", 
            "left": + tPosX + "px"
        });
        
        eval($(this).attr('cb')+'()');
        
    });
}

/**
 * Attiva il rollover sulle discussioni
 */
function attivaRollOver() {
    $('li.discussione').mouseover(function() {        
        $(this).addClass('light-grey');
    }).mouseleave(function() {
        $(this).removeClass('light-grey');
    });
}

/**
 * Imposta lo stato ai filtri checkbox nell'header 
 */
function checkboxFiltri() {
    $('.cb-filtro').each(function(index) {
        elem = $(this).children('input');
        temp = eval(elem.val());
        n = 0;
        temp.forEach(function(key){
            if(eval('filtri.'+key)) {
                n++;
            }
        });
        if(n == 0) {
            elem.attr('checked', false);
            elem.attr('readonly', false);
        } else if(n == temp.length){
            elem.attr('checked', true);
            elem.attr('readonly', false);
        } else {
            elem.attr('checked', true);
            elem.attr('readonly', true);
        }
    });
}

/**
 * Modifica l'intestazione in fase di lettura discussione
 */
//function setIntestazione(titolo, mittenti) {
function setIntestazione() {
    //$('#row_filtri').hide();
    //    $('#text_subject').html(titolo);
    $('#row_subject').show();
    //    $('#titolo_pagina').text(mittenti);
    $('.msg_button').toggle();
    mode = 'disc';
}

/**
 * Funzione ajax per modificare lo stato di una discussione
 */
function dettagliContatto(id) {
    $.post(action, {
        id: id
    }, function(msg) {
        if(msg == "ok") {
            if(tipo[area] != null) {  
                $("#d_"+id).animate({
                    opacity: 0.5
                }, 500, function() {
                    $("#d_"+id).slideUp("slow", function(){
                        $(this).remove();
                    });
                });           
            }     
            if(area == 'read') {  
                stack_chiamate_pre_notifiche.remove(id);
                if(stack_chiamate_pre_notifiche.isEmpty()) {
                    aggiorna_body_messaggi = true;
                    checkNuoviMessaggi();
                }
            }
        }
    }
);
}
function aggiornaTestoFiltro(indice) {
    if(indice == 0) {
        $('#tipo-messaggi').text('');
    } else {
        $('#tipo-messaggi').text(
        $('#testo'+indice)
        .html()
        .removeTags('span')
        .stripTags('strong')
        .toLowerCase()
    );
    }
}

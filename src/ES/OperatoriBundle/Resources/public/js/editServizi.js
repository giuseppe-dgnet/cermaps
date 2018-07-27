var servizi_scelti = new Array(); 
var categorie_scelte = new Array(); 
var categorie = new Array(); 
var categorie_nomi = new Array(); 
$(document).ready(function(){
    // Codice necessario per la seconda fase di registrazione del Professionista
    nuovo_servizio = $('#ecoseekr_bundle_servizibundle_serviziotype_servizio');
        
    sanitize_regex([nuovo_servizio], /[^a-zA-Z\. àéèìòù]/g);
    nuovo_servizio.change(function(){
        nuovo_servizio.val(nuovo_servizio.val().trim().capitalize());
    });
    // var lionize = new Array();
    checkVariabili(['servizi_massimi']);
    switch_tabs($('.defaulttab'));
    $( "#categorie_servizi" ).change(function(){
        $('#hide-drag').show();
        $('.div_drag').hide();
        $('#'+$(this).val()).show();
    });
    $('.div_drag').hide();
    $('#'+$( "#categorie_servizi" ).val()).show();
    $( ".all_draggable li.sa" ).draggable({
        appendTo: "body",
        helper: "clone",
        cursorAt: {
            left: 5
        },
        start: function(e, ui){
            $(ui.helper).addClass("ui-draggable-helper");
        }
    }).click(function(){
        if($(this).attr('class') != 'add_servizio ui-draggable') {
            if(servizi_scelti.length < servizi_massimi) {
                if(!servizi_scelti.find(function() { return $(this).attr('id'); })) {
                    //selezionaServizio(find, id, nomi_cats, text, nuovo_servizio, categorie_id) {
                    selezionaServizio(".droppable ul", $(this).attr('id'), JSON.parse($(this).attr('cats')), $(this).html(), false, JSON.parse($(this).attr('categorie_id')));
                } else {
                    fancyAlert('Servizio già selezionato');
                }
            } else {
                fancyAlert('Hai già selezionato '+servizi_massimi+' servizi');
            }
        }
    });
        
    $( ".droppable ul" ).droppable({
        hoverClass: "overdrop",
        drop: function( event, ui ) {
            if(ui.draggable.attr('class') != 'add_servizio ui-draggable') {
                if(servizi_scelti.length < servizi_massimi) {
                    if(!servizi_scelti.find(function() { return ui.draggable.attr('id'); })) {
                        //selezionaServizio(find, id, nomi_cats, text, nuovo_servizio, categorie_id) {
                        selezionaServizio(".droppable ul", ui.draggable.attr('id'), JSON.parse(ui.draggable.attr('cats')), ui.draggable.html(), false, JSON.parse(ui.draggable.attr('categorie_id')));
                    } else {
                        fancyAlert('Servizio già selezionato');
                    }
                } else {
                    fancyAlert('Hai già selezionato '+servizi_massimi+' servizi');
                }
            }
        }
    })
        
    $('.drop_list').click(function() {
        servizi_scelti.forEach(function(id) {
            $('#s'+id).remove();
            if($('#'+id).attr('class') == 'nuovo_servizio') {
                $('#'+id).remove();
            } else {
                $('#'+id).removeClass('selezionato');
            }   
        });
        servizi_scelti = new Array();
        categorie_scelte = new Array();
        aggiornaTestiServiziCategorie();
        $('#riepilogo_sa_list').html('');
    });
        
    $('.add_servizio').click(function() {
        if(servizi_scelti.length < servizi_massimi) {
            $('#bt_add_sa').show();
            $('#form_sa_ok').show();
            $('#form_sa_ko').hide();
            cs = $("#categorie_servizi").val().from(10);
            $('#ecoseekr_bundle_servizibundle_serviziotype_servizio').val('');
            $('#ecoseekr_bundle_servizibundle_serviziotype_categorie').val(cs);
            $('#categoria_nuovo_servizio').text($('#opt_'+cs).html());
        } else {
            $('#form_sa_ok').hide();
            $('#form_sa_ko').show();
        }
    });
    $('.fancybox').fancybox({
        hideOnOverlayClick: false,
        transitionIn: 'elastic',
        padding:3,
        margin:0
    });
    $('#confirm').click(function() {
        if(servizi_scelti.length > 0 || confirm('Non hai selezionato nessun servizio. Vuoi continuare?')) {
            $('#confirm').html('Attendere...');
            servizi = new Array();
            servizi_scelti.forEach(function(val){
                servizi.add(val.from(2));
            });
            $('#servizi_json').val(JSON.stringify(servizi));
            fancyConfirm("Hai selezionato tutti i tuoi Servizi?", "Sì, salva e continua", "No, continua la selezione", "checkRDO"); 
        }
        return false;
    });
    
});
function checkRDO(ret) {
    if(ret) {
        $('#rdo_form').submit();
    } else {
        $('#confirm').html('Conferma le scelte');
    }
}
/* submit form lightwindows */

function aggiungiServizio() {
    checkVariabili('url_suggerisci_servizio');
    $('#form_sa .button-orange').html('Attendere...');
    servizio = $('#ecoseekr_bundle_servizibundle_serviziotype_servizio').val();
    categoria = $('#ecoseekr_bundle_servizibundle_serviziotype_categorie').val();
    data =  "servizio=" + servizio +
    "&categoria=" + categoria ;
    categoria_id = categoria;
    $.ajax({
        url: url_suggerisci_servizio,
        data: data,
        success: function(msg) {
            if(servizi_scelti.length < servizi_massimi) {
                id = msg;
                id = id.from(id.indexOf('"') + 1);
                id = id.first(id.indexOf('"'));
                if(!servizi_scelti.find(function() { return id; })) {
                    text = msg;
                    text = text.from(text.indexOf('>', 1) + 1);
                    text = text.first(text.indexOf('</li>'));
                    nuovo_servizio = msg.indexOf('nuovo_servizio') >= 0;
                    //selezionaServizio(find, id, nomi_cats, text, nuovo_servizio, categorie_id) {
                    selezionaServizio(".droppable ul", id, [$('#categoria_nuovo_servizio').html()], text, (nuovo_servizio ? msg : false), [categoria]);
                    $('#ul_'+categoria_id).append(msg);
                } else {
                    fancyAlert('Servizio già selezionato');
                }
            } else {
                fancyAlert('Hai già selezionato '+servizi_massimi+' servizi');
            }
            $('#form_sa .button-orange').html('Aggiungi Servizio');
            $.fancybox.close();
        }
    });
};

/* funziona che chiama i servizi droppati in generale */
var last_id_delete = 0;
function selezionaServizio(find, id, nomi_cats, text, nuovo_servizio, categorie_id) {
    servizi_scelti.add(id);
    categoria = '';
    nomi_cats.each(function(categoria_nome){
//        if(categorie_nomi.find(categoria_nome)){
            categoria += ' '+categoria_nome;
//        }
    });
    categorie_id.each(function(categoria_id){
//        if(categorie.find(categoria_id)){
            if(!categorie_scelte[categoria_id]) {
                categorie_scelte[categoria_id] = 1;
            } else {
                categorie_scelte[categoria_id]++;
            }
//        }
    });
    $('.'+id).addClass('selezionato');
    aggiornaTestiServiziCategorie();
    $( find ).find( ".placeholder" ).remove();
    $( "<li id='s"+id+"' "+(nuovo_servizio ? 'class="nuovo_servizio tips"' : '')+"></li>" ).html('<span class="icon servizi_small">'+(nuovo_servizio ? '<span class="plus">' : '')+'servizio:'+(nuovo_servizio ? '</span>' : '')+'</span><h3>'+ text + '</h3><p>'+categoria+'</p><img src="/bundles/esweb/images/icone/delete.png" id="'+(nuovo_servizio ? 'x' : 'd')+id+'" class="delete" alt="Cancella"/>' ).prependTo( $( find ) );
    $( "<li id='_s"+id+"' "+(nuovo_servizio ? 'class="nuovo_servizio tips"' : '')+"></li>" ).html('<span class="icon servizi_small">'+(nuovo_servizio ? '<span class="plus">' : '')+'servizio:'+(nuovo_servizio ? '</span>' : '')+'</span><h3>'+ text + '</h3><p>'+categoria+'</p><img src="/bundles/esweb/images/icone/delete.png" id="'+(nuovo_servizio ? 'x' : 'd')+id+'" class="delete" alt="Cancella"/>' ).appendTo( $('#riepilogo_sa_list') );
    $('.delete').click(function() {
        if($(this).attr('id').at(0) == 'x') {
            left = $('#'+$(this).attr('id').from(1));
            if(left.attr('class') == 'nuovo_servizio') {
                left.remove();
            }   
        }
        $('.'+$(this).attr('id').from(1)).removeClass('selezionato');
        servizi_scelti.remove($(this).attr('id').from(1));
        if(last_id_delete != $(this).attr('id')) {
            categorie_id.each(function(categoria_id){
                if(categorie.find(function() { return categoria_id; })){
                    categorie_scelte[categoria_id]--;
                }
            });
            last_id_delete = $(this).attr('id');
        }
        aggiornaTestiServiziCategorie();
        $('#_'+$(this).closest('li').attr('id')).remove();
        $(this).closest('li').remove();
        if($('#droppable').children().length == 0) {
            $( "<li class='placeholder'></li>" ).text( '' ).appendTo( $('#droppable') );
        }
    });
}
function aggiornaTestiServiziCategorie() {
    n_servizi = servizi_scelti.length;
    n_categorie = categorie_scelte.exclude(function(n) {
        return !is_int(n);
    }).exclude(function(n) {
        return n <= 0;
    }).length;
    $('.numero_servizi').text(n_servizi+' '+(n_servizi == 1 ? 'servizio' : 'servizi'));
    $('.numero_categorie').text(n_categorie+' '+(n_categorie == 1 ? 'categoria' : 'categorie'));
}
function addCategoria(categoria) {
    $('.categoria_'+categoria).each(function() {
        if(servizi_scelti.length < servizi_massimi) {
            if(!servizi_scelti.find(function() { return $(this).attr('id'); })) {
                //selezionaServizio(find, id, nomi_cats, text, nuovo_servizio, categorie_id) {
                selezionaServizio(".droppable ul", $(this).attr('id'), JSON.parse($(this).attr('cats')), $(this).html(), false, JSON.parse($(this).attr('categorie_id')));
            }
        } else {
            fancyAlert('Hai già selezionato '+servizi_massimi+' servizi');
        }
    });
}

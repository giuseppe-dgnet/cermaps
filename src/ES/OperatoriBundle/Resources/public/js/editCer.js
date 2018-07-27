var cer_scelti = new Array(); 
var cer_loaded = new Array();
var provenienze_cer_scelte = new Array(); 
$(document).ready(function(){
    // Codice necessario per la seconda fase di registrazione dell'azienda'
    // var lionize = new Array();
    checkVariabili(['cer_massimi', 'url_get_cer']);
    switch_tabs($('.defaulttab'));
    

    $( "#provenienze_cer" ).change(function(){
        $('#hide-drag').show();
        openCerList($(this));
    //if(!lionize.find(function() { return $(this).val(); })) {
    // lionize.add($(this).val());
    //  $('#dc_'+$(this).val()).lionbars();
    //}
    });
    openCerList($( "#provenienze_cer" ));
        
    $( ".droppable ul" ).droppable({
        accept: '.cer_item',
        hoverClass: "overdrop",
        drop: function( event, ui ) {
            if(cer_scelti.length < cer_massimi) {
                if(!cer_scelti.find(function() { return ui.draggable.attr('id'); })) {
                    selezionaCer(".droppable ul", ui.draggable.attr('id'), ui.draggable.html(), ui.draggable.attr('name').from(10));
                } else {
                    fancyAlert('Indice CER già selezionato');
                }
            } else {
                fancyAlert('Hai già selezionato '+cer_massimi+' indici CER');
            }
        }
    })
    /*
        .sortable({
            items: "li:not(.placeholder)",
            sort: function() {
                $(this).removeClass( "ui-state-default" );
            },
            stop: function(event, ui) {
                servizi_scelti.removeAt(0, servizi_scelti.length - 1);
                $('#'+$(this).attr('id')+' li').each(function() {
                    servizi_scelti.add($(this).attr('id').from(1));
                });
            }
        });
         */
        
    $('.drop_list').click(function() {
        cer_scelti.forEach(function(id) {
            $('#s'+id).remove();
            $('#'+id).removeClass('selezionato');
        });
        cer_scelti = new Array();
        provenienze_cer_scelte = new Array();
        aggiornaTestiCerProvenienze();
        $('#riepilogo_cer_list').html('');
    });
    $('.add_servizio').click(function() {
        if(cer_scelti.length < servizi_massimi) {
            $('#bt_add_sa').show();
            $('#form_sa_ok').show();
            $('#form_sa_ko').hide();
            cs = $("#categorie_servizi").val().from(10);
            $('#ecoseekr_bundle_serviziambientalibundle_serviziotype_servizio').val('');
            $('#ecoseekr_bundle_serviziambientalibundle_serviziotype_categoria').val(cs);
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
        if(cer_scelti.length > 0 || confirm('Non hai selezionato nessun indice CER. Vuoi continuare?')) {
            $('#confirm').html('Attendere...');
            cer = new Array();
            cer_scelti.forEach(function(val){
                cer.add(val.from(2));
            });
            $('#cer_json').val(JSON.stringify(cer));
            fancyConfirm("Hai selezionato tutti i tuoi CER?", "Sì, salva e continua", "No, continua la selezione", "checkRDO"); 
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
/* funziona che chiama i cer droppati in generale */
var last_id_delete = 0;
function selezionaCer(find, id, text, categoria_id) {
    cer_scelti.add(id);
    if(!provenienze_cer_scelte[parseInt(categoria_id, 10)]) {
        provenienze_cer_scelte[parseInt(categoria_id, 10)] = 1;
    } else {
        provenienze_cer_scelte[parseInt(categoria_id, 10)]++;
    }
    $('#'+id).addClass('selezionato');
    aggiornaTestiCerProvenienze();
    $( find ).find( ".placeholder" ).remove();
    $( "<li id='s"+id+"'></li>" ).html('<h3>'+ text + '</h3><img src="/bundles/esweb/images/icone/delete.png" id="d'+id+'" class="delete" alt="Cancella"/>' ).prependTo( $( find ) );
    $( "<li id='_s"+id+"'></li>" ).html('<h3>'+ text + '</h3><img src="/bundles/esweb/images/icone/delete.png" id="d'+id+'" class="delete" alt="Cancella"/>' ).appendTo( $('#riepilogo_cer_list') );
    $('.delete').click(function() {
        if($(this).attr('id').at(0) == 'x') {
            left = $('#'+$(this).attr('id').from(1));
            if(left.attr('class') == 'nuovo_servizio') {
                left.remove();
            }   
        }
        $('#'+$(this).attr('id').from(1)).removeClass('selezionato');
        cer_scelti.remove($(this).attr('id').from(1));
        if(last_id_delete != $(this).attr('id')) {
            provenienze_cer_scelte[parseInt(categoria_id, 10)]--;
            last_id_delete = $(this).attr('id');
        }
        aggiornaTestiCerProvenienze();
        $('#_'+$(this).closest('li').attr('id')).remove();
        $(this).closest('li').remove();
        if($('#droppable').children().length == 0) {
            $( "<li class='placeholder'></li>" ).text( '' ).appendTo( $('#droppable') );
        }
    });
}
function aggiornaTestiCerProvenienze() {
    n_cer = cer_scelti.length;
    n_provenienze = provenienze_cer_scelte.exclude(function(n) {
        return !is_int(n);
    }).exclude(function(n) {
        return n <= 0;
    }).length;
    $('.numero_cer').text(n_cer+' '+(n_cer == 1 ? 'indice CER' : 'indici CER'));
    $('.numero_provenienze').text(n_provenienze+' '+(n_provenienze == 1 ? 'classe' : 'classi'));
}

function openCerList(widget) {
    $('.div_drag').hide();
    var spinner = new Spinner(opts).spin();
    if(!cer_loaded.find(function() { return widget.val(); })) {
        cers = new Array();
        cer_scelti.forEach(function(val){
            cers.add(val.from(2));
        });
        $('.loader').append('<div id="preloader">&nbsp;</div>');
        spinner.spin();
        $('#preloader').append(spinner.el);
        $.ajax({
            url: url_get_cer,
            data: "classe="+widget.val().from(10)+"&cers="+JSON.stringify(cers),
            success: function(msg) {
                spinner.stop()
                removeObj('#preloader')
                cer_loaded.add(widget.val());
                $('#'+widget.val()).html(msg);
                $('#'+widget.val()).show();
                $( ".cer_"+widget.val().from(10)+" li" ).draggable({
                    appendTo: "body",
                    addClasses: false,
                    helper: "clone",
                    cursorAt: {
                        left: 5
                    },
                    start: function(e, ui){
                        $(ui.helper).addClass("ui-draggable-helper ui-draggable-item");
                    }
                }).click(function() {
                    if($(this).attr('class').trim() != 'cat_cer') {
                        if($(this).attr('class').indexOf('cer') >= 0) {
                            if(cer_scelti.length < cer_massimi) {
                                if(!cer_scelti.find(function() { return $(this).attr('id'); })) {
                                    selezionaCer(".droppable ul",  $(this).attr('id'),  $(this).html(),  $(this).attr('name').from(10));
                                } else {
                                    fancyAlert('Indice CER già selezionato');
                                }
                            } else {
                                fancyAlert('Hai già selezionato '+cer_massimi+' indici CER');
                            }
                        }
                    }
                });
            }
        });
    } else {
        $('#'+widget.val()).show();
    }
}
function addSottoCategoria(sottoclasse) {
    $('.sottoclasse_'+sottoclasse).each(function() {
        if(cer_scelti.length < cer_massimi) {
            if(!cer_scelti.find(function() { return $(this).attr('id'); })) {
                selezionaCer(".droppable ul",  $(this).attr('id'),  $(this).html(),  $(this).attr('name').from(10));
            }
        } else {
            fancyAlert('Hai già selezionato '+cer_massimi+' indici CER');
        }
    });
}
function addCategoria(classe) {
    $('.classe_'+classe).each(function() {
        if(cer_scelti.length < cer_massimi) {
            if(!cer_scelti.find(function() { return $(this).attr('id'); })) {
                selezionaCer(".droppable ul",  $(this).attr('id'),  $(this).html(),  $(this).attr('name').from(10));
            }
        } else {
            fancyAlert('Hai già selezionato '+cer_massimi+' indici CER');
        }
    });
}

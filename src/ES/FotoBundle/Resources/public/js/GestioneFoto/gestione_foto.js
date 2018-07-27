/**
 * 
 * Gestione dei vari form per l'eliminaizone e aggiornamento delle fotografie
 * Quando salvo o elimino una foto disabilito tutto i pulsanti per fare altre azioni di form (semaforo)
 * Se una foto è profilo non può essere privata e viceversa !!!!! NON ABILITATO !!!
 * Gestione FancyBox e Jcrop
 */
/**
 * Variabili necessarie per il Jcrop
 */
var jcrop_api = "";
var boundx,
        boundy,
        // Grab some information about the preview pane
        $preview = $('#preview-pane'),
        $pcnt = $('#preview-pane .preview-container'),
        $pimg = $('#preview-pane .preview-container img'),
        xsize = $pcnt.width(),
        ysize = $pcnt.height();
//necessario per il jcrop
var html = '<input type=\"hidden\" id=\"x\" name=\"x\" />\n\
                <input type=\"hidden\" id=\"y\" name=\"y\" />\n\
                <input type=\"hidden\" id=\"w\" name=\"w\" />\n\
                <input type=\"hidden\" id=\"h\" name=\"h\" />\n\
                ';

//indice per la gestione hide/show dei checkbox
oldindex = null;
//diventa true quando trova l'immagine profilo, evitando cicli inutili
trovato = false
//controllo per sapere lo stato del pannello di modifica che ospita il form
editarea_switch = true;
// Altezza del pannello che contiene il form, messo come variabile globale, perche serve anche dentro altri scope
var editarea = 0;

$(document).ready(function() {

    $(".apri_scheda").click(function() {
        var temp_id = null;
    }).attr('rel', 'gallery_gestione_foto').fancybox({
        autoSize: true,
        scrolling: 'no',
        padding: 0,
        margin: 0,
        transitionIn: 'none',
        transitionOut: 'none',
        modal: false,
        /**
         * Funzione che viene chiamata dopo il caricamento si da Click (Fancybox chiuso)
         * sia da click sulle frecce della galleria
         * 
         * prendo traccia dell'id corrente con temp_id = current.index
         * Chiudo il pannello precedente se scorro la foto da galleria
         * Distruggo il Jcrop se è aperto e nascondo il pulsante del Jcrop 
         * faccio unbind sul pulsante per evitare il loop animate
         * 
         * @see https://github.com/fancyapps/fancyBox/issues/143
         * @param {int} current
         * @param {int} previous
         * @returns {undefined}
         */
        afterLoad: function(current, previous) {
            editarea_switch = true;
            temp_id = current.index;


            if (previous) {

                if (jcrop_api != "") {
                    jcrop_api.destroy();
                    nascondiElemento($preview, 'normal');
                }

                nascondiElemento($("#btn_crop-" + previous.index), 'normal');
                
                $('.editarea-' + previous.index).animate({
                    bottom: "0px"
                }, 500);

                //fare un controllo se è selected, per gestire l'orientamento dell immagine
                $('.open-close-editarea-' + previous.index).toggleClass('selected');
                $('.open-close-editarea-' + previous.index).unbind("click");
                //console.info('Navigating: ' + (current.index > previous.index ? 'right' : 'left'));
            }
        },
        /**
         * Svuoto il div che contiene la x y w h del jcrop, OBBLIGATORIO 
         * faccio unbind sul pulsante per evitare il loop animate 
         * @returns {undefined}
         */
        beforeClose: function() {
            $(".coordinate_jcrop").empty(); //elimino le coordinate per il Jcrop
            $('.open-close-editarea-' + temp_id).unbind("click");
        },
        beforeShow: function() {

        },
        /**
         * Spostiamo le frecce della galleria fuori dalla foto 
         * prendiamo l'altezza del contenitore che ospita il form, per non dovere avere un altezza fissa
         * inizialmente faccio un animate per buttarlo giu e metterlo visibile, altrimenti si vedrebbe all'apertura del fancybox
         * per pochi secondi e fa caa
         * attacco tramite bind l'evento animate che serve per tirare su e giu tale pannello
         * @returns {undefined}
         */
        afterShow: function() {

            $(".fancybox-nav").css("width", "60px");
            $(".fancybox-next").css("right", "-60px");
            $(".fancybox-prev").css("left", "-60px");

            widtharea = $('#edit_image-' + temp_id).width();
            
            $('.editarea-' + temp_id).css("width", widtharea);
            editarea = $('.editarea-' + temp_id).outerHeight()-3;

            $('.editarea-' + temp_id).animate({
                bottom: "0px"
            }, 1, "linear", function() {
                $('.editarea-' + temp_id).fadeIn();
            });

            $('.open-close-editarea-' + temp_id).bind("click", (function() {
                if (!editarea_switch) {
                    $('.editarea-' + temp_id).animate({
                        bottom: "0px"
                    }, 500);
                    $(this).toggleClass('selected');
                    editarea_switch = true;
                } else {
                    $('.editarea-' + temp_id).animate({
                        bottom: "-" + editarea + "px"
                    }, 500);
                    $(this).toggleClass('selected');
                    editarea_switch = false;
                }
            }));
        },
        helpers: {
            overlay: {
                css: {
                    'background': 'rgba(0,0,0, 0.8)'
                }
            },
            title: null,
            overlay: {
                showEarly: false
            }
        }
    });

    checkNumeroFoto();
    checkProfiloFoto();

    $('.form_gestione_foto').each(function(index, numero_form) {

        if (!trovato) {
            //checkProfiloFoto(index);
        }

        //checkPrivata(index);

        var theRules = {};
        /** come aggiungere le regole **/
        /*theRules[
         'form_gestione_foto_' + numero_form + '[titolo]'] = {required: true,  minlength: 8};
         theRules[
         'form_gestione_foto_' + numero_form + '[descrizione]'] = {required: true,  minlength: 8};
         */
        $(this).validate({
            rules: theRules,
            submitHandler: function(form) {
                salva_form(form);
            }
        });
    });
    
    
    /**
     * Icone sopra le immagini e gestioni Isotope
     */
    //Tooltip sulle immagini appena caricate
    $(".thumbnail").on("mouseenter", function() {
        $(this).find('.caption').css('opacity', '1');
    });

    $(".thumbnail").on("mouseleave", function() {
        $(this).find('.caption').css('opacity', '.3');
    });

    var $container = $('#iso-container');

    // Call imagesLoaded and position images
    $container.imagesLoaded(function() {
        $container.isotope({
            itemSelector: '.thumbnail',
            animationEngine: 'best-available',
            resizesContainer: true
        });
    });

    

});



/**
 * Verifico che almeno una foto Esista altrimenti elimino il pulsante elimina, 
 * cioè almeno una foto l'utente la deve avere
 */
function checkNumeroFoto() {
    if (numero_form <= 1) {
        disabilitaElemento($(".elimina_btn"));
    }
}

/**
 * se checco l'opzione foto profilo faccio venire fuori il Fancybox. Questa funzione viene chiamata dal checkbox stesso e 
 * viene agganciata direttamente nel Form del Twig con onClick /FotoBundle/Resources/views/GestioneFoto/index.html.twig
 * Abbasso anche il pannello che ospita il form, per una migliore visione
 * 
 * @param {int} n il pulsante, $(this)
 * @returns {undefined}
 */
function mostra_opzioni_modifica(n) {
    if (n.is(':checked')) {

        //nascondo il pannello per un miglior Crop
        $('.editarea-' + n.closest('form').attr('id').split("-").pop()).animate({
            bottom: "-" + editarea + "px"
        }, 500);
        $(this).toggleClass('selected');
        editarea_switch = false;

        $(".coordinate_jcrop").empty();
        $("#jcrop_foto-" + n.closest('form').attr('id').split("-").pop() + " #coordinate_jcrop-" + n.closest('form').attr('id').split("-").pop()).append(html);
        jcrop($("#crop_foto-" + n.closest('form').attr('id').split("-").pop()), $("#crop_foto-" + n.closest('form').attr('id').split("-").pop()).width(), $("#crop_foto-" + n.closest('form').attr('id').split("-").pop()).height(), n.closest('form').attr('id').split("-").pop());
        mostraElemento($("#btn_crop-" + n.closest('form').attr('id').split("-").pop()), 'normal');
        //mostraElemento($("#btn_jcrop_foto-" + n.closest('form').attr('id').split("-").pop()), 'normal');
    } else {
        if (jcrop_api != "") {
            jcrop_api.destroy();
            nascondiElemento($preview, 'normal');
        }
        nascondiElemento($("#btn_crop-" + n.closest('form').attr('id').split("-").pop()), 'normal');
        //nascondiElemento($("#btn_jcrop_foto-" + n.closest('form').attr('id').split("-").pop()), 'normal');
    }
}

/*---------------------------------- 
 Gestione Jcrop
 ----------------------------------*/

/**
 * 
 * La funzione principale del Jcrop che aggancia al target il comportamento del Jcrop stesso
 * Aggiorna in tempo realte l'anteprima, ed ha una dimensione minima 200x200, come l'avatar
 * 
 * @param {div} target es $("#crop_foto-0"), il target soggetto al jcrop
 * @param {int} larghezza Width dell'oggetto per il responsivo
 * @param {int} altezza Height dell'oggetto per il responsivo
 * @param {int} numero_foto l'id della foto 
 * @returns {undefined}
 */
function jcrop(target, larghezza, altezza, numero_foto) {

    $preview = $('#preview-pane-' + numero_foto);
    mostraElemento($preview, 'normal');
    $pcnt = $('#preview-pane-' + numero_foto + ' .preview-container');
    $pimg = $('#preview-pane-' + numero_foto + ' .preview-container img');
    xsize = $pcnt.width();
    ysize = $pcnt.height();

    target.Jcrop({
        aspectRatio: 1,
        allowSelect: false, //non posso cancellare il crop, non permetto nuovi crop.
        onChange: updatePreview,
        onSelect: updateCoords,
        bgOpacity: 0.3,
        bgColor: '#a136af',
        minSize: [200, 200]        // Impostazione Minima si Crop
                //trueSize: [larghezza,altezza]
    }, function() {
        jcrop_api = this;
        jcrop_api.animateTo([0, 0, 200, 200]); //Faccio venire al volo il crop in postazione 0,0 con dimensioni 200x200

        // Use the API to get the real image size
        var bounds = this.getBounds();
        boundx = bounds[0];
        boundy = bounds[1];
    });
}

function updateCoords(c) {
    $('#x').val(c.x);
    $('#y').val(c.y);
    $('#w').val(c.w);
    $('#h').val(c.h);
}

/**
 * Aggiorna la Preview che sto croppando
 * @param {int} c
 * @returns {undefined},
 */
function updatePreview(c) {
    if (parseInt(c.w) > 0)
    {
        var rx = xsize / c.w;
        var ry = ysize / c.h;
        $pimg.css({width: Math.round(rx * boundx) + 'px',
            height: Math.round(ry * boundy) + 'px',
            marginLeft: '-' + Math.round(rx * c.x) + 'px',
            marginTop: '-' + Math.round(ry * c.y) + 'px'
        });
    }
}


/**
 * Funzione chiamata dal Fancybox del Jcrop, dal pulsante 
 * disabilito tutti i pulsanti :button creando una sorta di semaforo
 * @param {object} n è il $(this) del pulsante in /GestioneFoto/index.html.twig
 * @returns {undefined}
 */
function jcrop_ajax(n) {
    var form = n.closest('form');

    //prendo id che mi dal pulsante id="btn_crop-{{loop.index0}}"
    var id = n.attr('id').split("-").pop();

    $.ajax({
        type: "POST",
        url: form.attr('action'),
        data: $("#" + form.attr('id')).serialize(),
        dataType: "html",
        beforeSend: function() {
            //disabilitaElemento($("#" + form.attr('id') + " :button"));
            disabilitaElemento($(":button"));
            afterElemento($("#" + form.attr('id')).find(":submit"), '<span class="feedback_form"><img src="/bundles/punkavefileuploader/images/spinner.gif" /></span>', 'slow');
        },
        success: function(msg) {
            msg = $.parseJSON(msg);

            if (msg.status == "OK") {
                $(".feedback_form").html(Translator.get('messages:success'));
            } else {
                $(".feedback_form").html(Translator.get('messages:error'));
            }

            eliminaElemento($("#preview_profilo-" + id), 0);
            // afterElemento($("#btn_crop-" + id), '<div id="preview_profilo-' + id + '"><img src=' + msg.croppata + '></div>', 'slow'); //==> SE   VOGLIAMO FARLA VEDERE UNA VOLTA CROPPATA
            eliminaElemento(".feedback_form", 2000);
            abilitaElemento($(":button"), 3000);
            //attivaElemento($("#" + form.attr('id') + " :button"), 3000);
        },
        error: function(msg) {
            $(".feedback_form").html(Translator.get('messages:error'));
            eliminaElemento(".feedback_form", 2000);
            abilitaElemento($(":button"), 3000);
        }
    });
}

/*---------------------------------- 
 
 Gestione Salvataggio ed eliminazione foto
 
 ----------------------------------*/

/**
 * Il form deve avere la action e un pulsante submit e un id
 * 
 * @param {object} form oggetto form che gli arriva dal validate
 */
function salva_form(form) {

    //($("#" + form.id).attr('class'));
    $.ajax({
        type: "POST",
        url: form.action,
        data: $("#" + form.id).serialize(),
        dataType: "html",
        beforeSend: function() {
            disabilitaElemento($(".form_gestione_foto :button"));
            afterElemento($("#" + form.id).find(":submit"), '<span class="feedback_form"><img src="/bundles/punkavefileuploader/images/spinner.gif" /></span>', 'slow');
        },
        success: function(msg) {
            msg = $.parseJSON(msg);
            if (msg.status == "OK") {
                $(".feedback_form").html(Translator.get('messages:success'));
                //checkProfiloFoto(msg.id_form);
            } else {
                $(".feedback_form").html(Translator.get('messages:error'));
            }

            eliminaElemento(".feedback_form", 2000);
            attivaElemento($(".form_gestione_foto :button"), 2000);

        },
        error: function(msg) {
            $(".feedback_form").html(Translator.get('messages:error'));
            eliminaElemento(".feedback_form", 2000);
            attivaElemento($(".form_gestione_foto :button"), 2000);
        }
    });
}

/**
 *  Controllo che non cancelli la foto profilo
 * 
 * @param {object} n passo il $(this) dal twig
 * @returns {undefined}
 */
function elimina_foto(n) {
    var form = n.closest('form');

    //Forzo l utente a impostar un'altra foto profilo evitando di far cancellare quelal checcata
//    if ($("#form_gestione_foto_" + n.attr('id').split("-").pop() + "_foto_profilo").is(':checked')) {
//        //mostraElemento($("#jcrop_foto-" + n.attr('id').split("-").pop()), 'normal');
//        alert(Translator.get('SNFotoBundle:form_gestione_foto.eliminaFotoProfilo'));
//        return false;
//    }

    //domando se è sicuro di cancellare la foto
    if (!confirm(Translator.get('SNFotoBundle:form_gestione_foto.eliminaFoto'))) {
        return false;
    }

    $.ajax({
        type: "POST",
        url: form.attr('delete_url'),
        data: $("#" + form.attr('id')).serialize(),
        dataType: "html",
        beforeSend: function() {
            disabilitaElemento($(".form_gestione_foto :button"));
            afterElemento($("#" + form.attr('id')).find(":submit"), '<span class="feedback_form"><img src="/bundles/punkavefileuploader/images/spinner.gif" /></span>', 'slow');
        },
        success: function(msg) {
            msg = $.parseJSON(msg);

            //decremento il numero di form e verifico se disabilitare il btn elimina
            numero_form--;
            checkNumeroFoto();

            if (msg.status == "OK") {
                $(".feedback_form").html(Translator.get('messages:success'));
            } else {
                $(".feedback_form").html(Translator.get('messages:error'));
            }

            eliminaElemento(".feedback_form", 2000);
            eliminaElemento("#contenitore_foto_" + msg.foto_id, 2000);
            attivaElemento($(".form_gestione_foto :button"), 3000);
        },
        error: function(msg) {
            $(".feedback_form").html(Translator.get('messages:error'));
            eliminaElemento(".feedback_form", 2000);
            attivaElemento($(".form_gestione_foto :button"), 2000);
        }
    });
}
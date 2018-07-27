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

//Variabile che ospita le api di isotope. l ho messo come globale, perchè viene chiamato anche in js/FileUploader.js
var elem_isotope;

editarea_switch = true;
// Altezza del pannello che contiene il form, messo come variabile globale, perche serve anche dentro altri scope
var editarea = 0;

$(document).ready(function() {
    
    
    /* FANCYBOX SCHEDA */
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
        
        afterLoad: function(current, previous) {
            editarea_switch = true;
            temp_id = current.index;
            console.log("temp id first" + temp_id);


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
//            if(jcrop_api != "") { //se chiudo mentre ho un jcrop attivo
//                if(!$("#foto_profilo-"+id_profilo).is(':checked'))
//                jcrop_api.destroy();
//            }
        },
        afterShow: function() {

            $(".fancybox-nav").css("width", "60px");
            $(".fancybox-next").css("right", "-60px");
            $(".fancybox-prev").css("left", "-60px");

            widtharea = $('#edit_image-' + temp_id).width();
            console.log("widtharea" + widtharea);
            console.log("temp id" + temp_id);

            $('.editarea-' + temp_id).css("width", widtharea);
            editarea = $('.editarea-' + temp_id).outerHeight() - 3;

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
            title: null
        }
    });

});

/**
 * Chiamata che salva le foto jquery da temp a fisse
 * Aggiunge Il titolo e la Descrizione
 * 
 * TODO: SANITIZE TEXT
 * 
 */
function caricaFoto() {

    var arrayNomeFoto = [];
    var arrayTitoloFoto = [];
    var arrayDescrizioneFoto = [];
    var arrayPrivataFoto = [];
    var arrayProfiloFoto = [];


    /**
     * Creo Gli array che passo poi all'azione per salvare in modo permanente le fotografie
     * Potremmo fare un Json...un giorno...
     */
    $(".thumbnails .element").each(function() {
        arrayNomeFoto.push($(this).attr('data-name')); //nome del file
        arrayTitoloFoto.push($(this).find(".titolo_foto").val());
        arrayDescrizioneFoto.push($(this).find(".descrizione_foto").val());
        arrayPrivataFoto.push($(this).find(".foto_privata").is(':checked'));
        arrayProfiloFoto.push($(this).find(".foto_profilo").is(':checked'));

    });

    $.ajax({
        type: "POST",
        url: url_salva_blocco_foto_nuove,
        data: {
            arrayNomeFoto: arrayNomeFoto,
            arrayTitoloFoto: arrayTitoloFoto,
            arrayDescrizioneFoto: arrayDescrizioneFoto,
            arrayPrivataFoto: arrayPrivataFoto,
            arrayProfiloFoto: arrayProfiloFoto
        },
        dataType: "html",
        beforeSend: function() {
            creaElemento('#contenitore_foto_upload', '<p id="loading_foto">' + Translator.get('SNFotoBundle:attendere') + '</p>', 0.5);
            //disabilito tutti i pulsanti Button durante il caricamento
            disabilitaElemento($("#contenitore_foto_upload :button"));
        },
        success: function(msg) {
            msg = $.parseJSON(msg);
            if (msg.status === "OK") {

                eliminaElemento("#loading_foto", 0);
                creaMessaggioFlash('#fine_configurazione', '<p>' + Translator.get('SNFotoBundle:form_upload_foto.success') + '</p>', 1000, 3000, 1000);

                //Controllo per vedere se li arriva un parametro dal Form in modo da far vedere o meno il pulsante per continuare
                //Ad esempio dentro la Configurazione, serve il tasto continua per andare al passo dopo
                //Puo succedere che non serva tale pulsante
                //Lo impostiamo con il parametro redirect dentro il render nel Twig, che serve per richiamare tutta la parte dell'upload
                if ($('#url_action').length > 0) {
                    eliminaElemento("#contenitore_foto_upload", 0);
                    creaElemento('#fine_configurazione', '<a href="' + $('#url_action').val() + '"><p>' + Translator.get('SNFotoBundle:continua') + '</p></a>', 0.5);
                } else {
                    //qui invece è il comportamento Ajax(cioè una volta salvato posso caricare altre foto, una volta che salvo, do il feedback di caricamento e in piu tolfo le foto caricate
                    //e i pulsanti per caricare
                    //Svuoto tutti le foto precaricate
                    $(".thumbnails .element").fadeOut(1000, "linear", function() {
                        eliminaFigli(".thumbnails");
                        eliminaFigli("#upload_foto");
                    });
                    //attivo tutti i pulsanti Button dopo il caricamento
                    abilitaElemento('#contenitore_foto_upload :button', 1000);
                }
            }
        },
        error: function(msg) {
            eliminaElemento("#loading_foto", 0);
            creaMessaggioFlash('#contenitore_foto_upload', '<p id="error_load_foto">' + Translator.get('SNFotoBundle:error') + '</p>', 1000, 3000, 1000);
            abilitaElemento('#contenitore_foto_upload :button', 1000);
        }
    });

}

/**
 * funzioni che vengono chiamate quando vengono caricati i file
 * per verificare se far vedere i pulsanti s
 * cmabio il comportamento del radioButton, se ci clicco, ho la possibilità di deselezionare   
 * 
 * Quando clicco un nuovo RadioButton nascondo dal vecchio pulsante selezionato , il pulsante per aprire il FancyBox
 * 
 * @param {int} numero_foto è un sequenziale che mi arriva da fileUploader.js, è in numero della foto Caricata
 * @returns {undefined},
 */
function attiva_pulsante(numero_foto) {
    $('#foto_profilo-' + numero_foto).mousedown(function(e) {
        var $self = $(this);
        var id = $(this).attr('id').split("-").pop();

        if ($self.is(':checked')) {
            jcrop_api.destroy();
            //$(".apri_scheda").hide(); //nasconde il Pulsante che Apre il fancybox
            nascondiElemento($(".apri_scheda"), 'normal');
            var uncheck = function() {
                setTimeout(function() {
                    $self.removeAttr('checked');
                }, 0);
            };
            var unbind = function() {
                $self.unbind('mouseup', up);
            };
            var up = function() {
                uncheck();
                unbind();
            };
            $self.bind('mouseup', up);
            $self.one('mouseout', unbind);
        } else {
            $self.prop("checked", true); //jquery 1.9 
            //$(".apri_scheda").hide(); //nasconde il Pulsante che Apre il fancybox
            nascondiElemento($(".apri_scheda"), 'normal'); //nasconde il Pulsante che Apre il fancybox
            mostraElemento($("#btn_crop-" + id), 'normal');
            mostraElemento($("#modifica_foto-" + id), 'normal');

            if (jcrop_api != "")
                jcrop_api.destroy();
            //#crop_foto-" + numero_foto è l'immagine che passo al Crop
            jcrop($("#crop_foto-" + numero_foto), $("#crop_foto-" + numero_foto).width(), $("#crop_foto-" + numero_foto).height(), numero_foto);
        }
    });

    //apri_baloon('.toggle-content-' + numero_foto, 200000000);


    checkPrivata(numero_foto);

    //Booleano che lo imposto dal Twig...
    //se ho didascalia faccio venire fuori la possibilita di aggiungere dei campi al che descrivono la foto e la possibilita di effetturare un Jcrop
    if (didascalia) {
        //Click per impostare la foto profilo faccio aprire il Jcrop, quello che mi fa aprire il fancybox
        $("#modifica_foto-" + numero_foto).click(function() {
            $(".coordinate_jcrop").empty();
            $("#jcrop_foto-" + numero_foto + " #coordinate_jcrop-" + numero_foto).append(html);
        });
    }

    if ($(".thumbnails .element").length === 1) {

        if ($("#upload_foto_btn").length <= 0) { //verifica se il pulsante esiste di già
            creaElemento('#upload_foto', '<button id="upload_foto_btn" class="small" onclick="caricaFoto()" type="submit">' + Translator.get('SNFotoBundle:form_upload_foto.save') + '</button>', 0.5);
        }
    }

    //Tooltip sulle immagini appena caricate
    $(".element").on("mouseenter", function() {
        $(this).find('.caption').css('opacity', '1');
    });

    $(".element").on("mouseleave", function() {
        $(this).find('.caption').css('opacity', '.3');
    });

}

/*---------------------------------- 
 * 
 Gestione e Salvataggio Jcrop (no webcam per ora)
 
 ----------------------------------*/

/**
 * Aggiorna i campi che poi passerò per salvare l'immagine croppata
 * sono campi hidden del form in UploadFotoBundle/Resources/views/Default/templates.html.twig
 * 
 * @param {int} c
 * @returns {undefined}
 */
function updateCoords(c) {
    //console.log("x "+c.x+" y "+c.y+" w "+c.w+" h "+c.h);
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
    $pcnt = $('#preview-pane-' + numero_foto + ' .preview-container');
    $pimg = $('#preview-pane-' + numero_foto + ' .preview-container img');
    xsize = $pcnt.width();
    ysize = $pcnt.height();

    target.Jcrop({
        aspectRatio: 1,
        //allowSelect: false, //non posso cancellare il crop, non permetto nuovi crop.
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

/**
 * Chiama croppa_foto funzione all'interno di Controller/UploadFotoController.php
 * Salva la nuova Immagine e la imposta anche come profilo
 * @param {object} n è il This del Pusalnte dove clicco  che trovo in Default/templates.html.twig
 * @returns {undefined} */
function jcrop_ajax(n) {
    var form = n.closest('form');

    //prendo id che mi dal pulsante
    var id = n.attr('id').split("-").pop();

    $.ajax({
        type: "POST",
        url: form.attr('action'),
        data: $("#" + form.attr('id')).serialize(),
        dataType: "html",
        beforeSend: function() {
            //disabilitaElemento($("#" + form.attr('id') + " :button"));
            disabilitaElemento($("#contenitore_foto_upload :button"));
            disabilitaElemento($(".fancybox-overlay :button"));
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
            afterElemento($("#btn_crop-" + id), '<div id="preview_profilo-' + id + '"><img src=' + msg.croppata + '></div>', 'slow'); //==> SE VOGLIAMO FARLA VEDERE UNA VOLTA CROPPATA
            eliminaElemento(".feedback_form", 2000);

            abilitaElemento($("#contenitore_foto_upload :button"));
            abilitaElemento($(".fancybox-overlay :button"));
            //attivaElemento($("#" + form.attr('id') + " :button"), 3000);
        },
        error: function(msg) {
            $(".feedback_form").html(Translator.get('messages:error'));
            eliminaElemento(".feedback_form", 2000);
            abilitaElemento($("#contenitore_foto_upload  :button"), 3000);
            abilitaElemento($(".fancybox-overlay :button"), 3000);
        }
    });
}

/*---------------------------------- 
 
 Fine gestione Jcrop
 
 ----------------------------------*/


/**
 * Verifica dove sono i checkbox delle foto private per mettere disable i checkbox del foto profilo
 * Ci vengono attaccati anche i comportamenti sul Change dei checkbox stessi
 * Distruggo
 * @param {type} index
 */
function checkPrivata(index) {

    $("#foto_privata-" + index).change(function() {
        if ($(this).is(':checked')) {
            disabilitaElemento($("#foto_profilo-" + index));
            if (jcrop_api != "") {
                jcrop_api.destroy();
            }
            eliminaElemento($("#preview_profilo-" + index), 0);
            nascondiElemento($("#btn_crop-" + index), 'normal');
            $("#foto_profilo-" + index).attr('checked', false);
        } else {
            attivaElemento($("#foto_profilo-" + index));
        }
    });
}

/**
 * Verifica se mantenere il pulsante per upload foto, dato che le foto si pososno cancellare
 * se non esistono piu foto non ha senso mantenerlo
 */
function verifica_pulsante() {
    if ($(".thumbnails .element").length == 0) {
        eliminaElemento("#upload_foto_btn", 0);
    }
}
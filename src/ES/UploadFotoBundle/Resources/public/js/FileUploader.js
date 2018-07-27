/**
 * Javascript che viene chaiamto dal bundle esterno Punkave
 * ho aggiunto dei nuovi valori che mi faccio ritornare come la medium_url e il numero della foto
 * chiamo anche una funzione attiva_pulsante, che mi serve per far venire fuori un pulsante di salvataggio, quando esiste almeno una foto. questo attiva_pulsante è in un altro js
 * Ho aggiunto al momento dell'append della foto la possibilità di agganciarci l'isotope, la variabile iso_container arrivata da un altro js
 * verifico che esista, se esiste faccio il comportamente isotope altrimenti comportamento append classico
 * 
 * @see https://github.com/desandro/isotope/issues/259
 * @see http://isotope.metafizzy.co/docs/adding-items.html
 * 
 * @type Number* 
 * numero_foto mi serve per gestire il Jcrop
 */

var numero_foto = 0;

function PunkAveFileUploader(options)
{




    var self = this,
            uploadUrl = options.uploadUrl,
            viewUrl = options.viewUrl,
            $el = $(options.el),
            uploaderTemplate = _.template($('#file-uploader-template').html());
    $el.html(uploaderTemplate({}));

    //$el è il pezzo di codice che si trova in templates  id="file-uploader-template"

    var fileTemplate = _.template($('#file-uploader-file-template').html()),
            editor = $el.find('[data-files="1"]'),
            thumbnails = $el.find('[data-thumbnails="1"]');

    //editor è il pulsante file per caricare i FILE

    self.uploading = false;

    self.errorCallback = 'errorCallback' in options ? options.errorCallback : function(info) {
        if (window.console && console.log) {
            console.log(info)
        }
    },
            self.addExistingFiles = function(files)
    {
        _.each(files, function(file) {
            appendEditableImage({
                // cmsMediaUrl is a global variable set by the underscoreTemplates partial of MediaItems.html.twig
                'medium_url': viewUrl + '/medium/' + file,
                'thumbnail_url': viewUrl + '/thumbnails/' + file,
                'url': viewUrl + '/originals/' + file,
                'name': file,
                'numero_foto': numero_foto //nuemro foto globale, parametri che passo a templates.html.twig nel blocco che inizia a riga 33
            });
        });
    };

    // Delay form submission until upload is complete.
    // Note that you are welcome to examine the
    // uploading property yourself if this isn't
    // quite right for you
    self.delaySubmitWhileUploading = function(sel)
    {
        $(sel).submit(function(e) {
            if (!self.uploading)
            {
                return true;
            }
            function attempt()
            {
                if (self.uploading)
                {
                    setTimeout(attempt, 100);
                }
                else
                {
                    $(sel).submit();
                }
            }
            attempt();
            return false;
        });
    }

    if (options.blockFormWhileUploading)
    {
        self.blockFormWhileUploading(options.blockFormWhileUploading);
    }

    if (options.existingFiles)
    {
        self.addExistingFiles(options.existingFiles);
    }


//editor è il pulsante file per caricare i FILE

    editor.fileupload({//https://github.com/blueimp/jQuery-File-Upload/wiki/API     
        dataType: 'json',
        url: uploadUrl, //Salva nelle cartelle temp route upload_img
        dropZone: $("#drop_zone"),
        done: function(e, data) {
            if (data)
            {
                //console.log(data); oggetto
                _.each(data.result, function(item) {
                    //console.log("DETTAGLIO IMMAGINE "+item);
                    console.log(item);



                    appendEditableImage(item); //aggiunge al <li>
                    //attiva il pulnsate per salvare le foto permanentemente
                    attiva_pulsante(numero_foto);
                    //console.log(numero_foto)
                    numero_foto++;

                    //$("#crop_url_image").val($(".thumbnail-image").attr("src"));
                });
            }


            //jcrop();

            //$('.thumbnail-image').Jcrop();

        },
        start: function(e) {
            $el.find('[data-spinner="1"]').show();
            self.uploading = true;
        },
        stop: function(e) {
            $el.find('[data-spinner="1"]').hide();
            self.uploading = false;
        }
    });

    // Expects thumbnail_url, url, and name properties. thumbnail_url can be undefined if
    // url does not end in gif, jpg, jpeg or png. This is designed to work with the
    // result returned by the UploadHandler class on the PHP side
    function appendEditableImage(info)
    {
        if (info.error)
        {
            self.errorCallback(info);
            return;
        }

        //è tutto il <li> della foto
        var immagine = "";
        //console.log("--------");
        $.each(info, function(index, value) {
            //alert(index + ': ' + value);
            if(index === 'profilo_url') {
                immagine = value;
            }
        });

        //console.log("--------");
        //console.log(info);
        var li = $($.parseHTML(fileTemplate(info)));

        li.find('[data-action="delete"]').click(function(event) {

            //domando se è sicuro di cancellare la foto
            if (!confirm(Translator.get('ESFotoBundle:form_gestione_foto.eliminaFoto'))) {
                return false;
            }

            var file = $(this).closest('[data-name]');
            var name = file.attr('data-name');
            $.ajax({
                type: 'delete',
                url: setQueryParameter(uploadUrl, 'file', name),
                beforeSend: function() {
                    disabilitaElemento($(":button"));
                },
                success: function() {
                    file.remove();

                    //verifica se far vedere il pulsante upload permanente oppure no (se non ci sono piu foto)
                    verifica_pulsante();

                    abilitaElemento($(":button"), 1000);

                },
                error: function(msg) {
                    abilitaElemento($(":button"), 3000);
                },
                dataType: 'json'
            });
            return false;
        });

        thumbnails.css('opacity', '1');

        //$("#button").click(function() {  
        if (typeof showroom === 'undefined') {
            thumbnails.append(li);
        } else {

           //thumbnails.append(li); => QUESTO DA RIPRISTINARE IL GIORNO DEL JCROP

            $(".thumb_azienda").attr("src", immagine);
            //var di = $(li);
            // elem_isotope.prepend(di).isotope('reloadItems').isotope({sortBy: 'original-order'});

//            var di = $(li);
            //elem_isotope.isotope('insert', di);
            //elem_isotope.isotope();

            //        $("#button").click(function() {  
//            var di = $(li);
//            iso.isotope('insert', di);
//            iso.isotope();
//        });
//
//        $("#button2").click(function() {
//            iso.isotope('shuffle');
//        });

        }
        //});
        //thumbnails.append(li);
    }

    function setQueryParameter(url, param, paramVal)
    {
        var newAdditionalURL = "";
        var tempArray = url.split("?");
        var baseURL = tempArray[0];
        var additionalURL = tempArray[1];
        var temp = "";
        if (additionalURL)
        {
            var tempArray = additionalURL.split("&");
            var i;
            for (i = 0; i < tempArray.length; i++)
            {
                if (tempArray[i].split('=')[0] != param)
                {
                    newAdditionalURL += temp + tempArray[i];
                    temp = "&";
                }
            }
        }
        var newTxt = temp + "" + param + "=" + encodeURIComponent(paramVal);
        var finalURL = baseURL + "?" + newAdditionalURL + newTxt;
        return finalURL;
    }
}



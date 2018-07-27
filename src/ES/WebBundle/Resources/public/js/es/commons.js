jQuery.fx.interval = 100;
var this_window = window.location;

function distanza(da_lat, da_lon, a_lat, a_lon) {
    return (Math.acos((Math.sin(deg2rad(da_lat)) * Math.sin(deg2rad(a_lat))) + (Math.cos(deg2rad(da_lat)) * Math.cos(deg2rad(a_lat)) * Math.cos(deg2rad(da_lon - a_lon))))) * terra;
}

function deg2rad(radians) {
    return radians * (Math.PI / 180);
}

function km(distanza) {
    if (distanza < 0) {
        return 'n.d.';
    }
    if (distanza < 1) {
        distanza = distanza * 1000;
        if (distanza < 1) {
            return '1m';
        }
        if (distanza < 100) {
            return Math.round(distanza) + 'm';
        }
        return Math.round(distanza, -1) + 'm';
    }
    if (distanza < 10) {
        return Math.round(distanza, 1) + 'km';
    }
    return Math.round(distanza) + 'km';
}

/* MESSAGGI ERRRORE DRAG&DROP FILE */
var fileUploadErrors = {
    maxFileSize: 'Il file caricato è troppo grande.',
    minFileSize: 'Il file caricato è troppo piccolo.',
    acceptFileTypes: 'Tipo di file non accettato.',
    maxNumberOfFiles: 'Max number of files exceeded',
    uploadedBytes: 'Uploaded bytes exceed file size',
    emptyResult: 'Empty file upload result'
};

/* CONTROLLO ERRORE PER I FORM */
function erroreForm(campo, messaggio) {
    campo.closest('div').append('<div class="form-alert">' + messaggio + '</div>');
    campo.addClass('alert-red');
    campo.focus(function() {
        $(this).removeClass('alert-red');
    });
    return false;
}
/* PROTOTYPE PER STRINGHE */
String.prototype.swapcase = function() {
    return this.replace(/([a-z]+)|([A-Z]+)/g, function($0, $1, $2) {
        return ($1) ? $0.toUpperCase() : $0.toLowerCase();
    });
}

function allLowerCase(fields) {
    fields.forEach(function(field) {
        field.change(function() {
            $(this).val($(this).val().toLowerCase());
        });
    });
}
function allUppercase(fields) {
    fields.forEach(function(field) {
        field.change(function() {
            $(this).val($(this).val().toUpperCase());
        });
    });
}
function sanitize(fields) {
    fields.forEach(function(field) {
        field.change(function() {
            $(this).val($(this).val().remove(/[^a-zA-Z0-9 \-\_\.\,\;\:\'\?\!\"àéèìòù]/g));
        });
    });
}
function urlify(text) {
    var urlRegex = /(https?:\/\/[^\s\<]+)/g;
    return text.replace(urlRegex, function(url) {
        return '<a href="' + url + '">' + url + '</a>';
    })
}
function sanitizeCurrency(fields) {
    fields.forEach(function(field) {
        field.change(function() {
            value = $(this).val().replace(",", ".").remove(/[^0-9\.]/g);
            n = 0;
            i = 0;
            nc = 0;
            value.chars(function(c) {
                if (c == '.') {
                    n++;
                }
                if (n == 2) {
                    i = nc;
                    n++;
                }
                nc++;
            });
            if (n > 1) {
                value = value.substring(0, i);
                value = Math.abs(parseFloat(value == '' || value == '.' ? 0 : value));
                $(this).val(value.toFixed(2));
            } else {
                value = Math.abs(parseFloat(value == '' || value == '.' ? 0 : value));
                $(this).val(value.toFixed(2));
            }
        });
    });
}
function sanitizeTelefono(fields) {
    fields.forEach(function(field) {
        field.change(function() {
            $(this).val($(this).val().remove(/[^0-9 \-\.]/g));
        });
    });
}
function sanitizeSkype(fields) {
    fields.forEach(function(field) {
        field.change(function() {
            $(this).val($(this).val().remove(/[^a-zA-Z0-9\-\.]/g));
        });
    });
}
function sanitizeUrl(fields) {
    fields.forEach(function(field) {
        field.change(function() {
            _sanitizeUrl($(this))
        });
    });
}
function _sanitizeUrl(field) {
    if (!field.val().startsWith(/http(s)?:\/\//) && field.val().trim() != '') {
        field.val('http://' + field.val());
    }
}
function sanitizeHtml(fields) {
    fields.forEach(function(field) {
        field.change(function() {
            $(this).val(optimizeHtml($(this).val(), false));
        });
    });
}
function sanitizeHtmlMin(fields) {
    fields.forEach(function(field) {
        field.change(function() {
            $(this).val(optimizeHtml($(this).val(), true));
        });
    });
}
function optimizeHtml(val, min) {
    text = val;
    if (min) {
        text = text.stripTags('a', 'div', 'span', 'ol', 'ul', 'li', 'dl', 'dt', 'dd');
        text = text.removeTags('script', 'img', 'hr');
    } else {
        text = text.stripTags('a', 'div', 'span');
        text = text.removeTags('script', 'img', 'hr');
    }
    text = text.stripTags('table', 'tbody', 'tr', 'th', 'td', 'thead', 'h1', 'h2', 'h3', 'h4', 'h5', 'hr', '');
    text = text.remove(/<p>[ ]*<\/p>/g);
    text = text.remove(/<\/strong><strong>/g);
    text = text.remove(/<\/em><em>/g);
    text = text.replace(/\&nbsp\;/g, ' ');
    text = text.replace(/<\/strong>[ ]*<strong>/g, ' ');
    text = text.replace(/<\/em>[ ]*<em>/g, ' ');
    text = text.replace(/<li><p>/g, '<li>');
    text = text.replace(/<\/p><\/li>/g, '</li>');
    text = text.replace(/\\/g, '');
    return text;
}
function floatField(fields) {
    fields.forEach(function(field) {
        field.change(function() {
            $(this).val($(this).val().remove(/[^0-9\.\,]/g).replace(',', '.'));
        });
    });
}
function capitalize(fields) {
    fields.forEach(function(field) {
        field.change(function() {
            $(this).val($(this).val().capitalize());
        });
    });
}
function capitalizeAll(fields) {
    fields.forEach(function(field) {
        field.change(function() {
            $(this).val($(this).val().titleize());
        });
    });
}
function uppercase(fields) {
    fields.forEach(function(field) {
        field.change(function() {
            $(this).val($(this).val().toUpperCase());
        });
    });
}
function lowercase(fields) {
    fields.forEach(function(field) {
        field.change(function() {
            $(this).val($(this).val().toLowerCase());
        });
    });
}
function sanitize_regex(fields, regex) {
    fields.forEach(function(field) {
        field.change(function() {
            $(this).val($(this).val().remove(regex));
        });
    });
}
function autoCheckEmail(fields) {
    fields.forEach(function(field) {
        field.change(function() {
            $(this).val($(this).val().trim());
            if ($(this).val() != '') {
                if (!checkEmail($(this).val())) {
                    fancyAlert('Email non valida');
                    $(this).val('');
                }
            }
        });
    });
}
function checkEmail(email) {
    re = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (email.match(re)) {
        return true;
    }
    return false;
}
function autoCheckLinkedin(fields) {
    fields.forEach(function(field) {
        field.change(function() {
            $(this).val($(this).val().trim());
            if ($(this).val() != '') {
                if (!checkLinkedin($(this).val())) {
                    fancyAlert('Profilo pubblico di LinkedIn non valida');
                    $(this).val('');
                }
            }
        });
    });
}
function checkLinkedin(url) {
    re = /^http(s)?:\/\/[a-z]{2,3}.linkedin.com\//;
    if (url.match(re)) {
        return true;
    }
    return false;
}
function autoCheckSito(fields) {
    fields.forEach(function(field) {
        field.change(function() {
            $(this).val($(this).val().trim());
            if ($(this).val() != '') {
                if (!checkSito($(this).val())) {
                    fancyAlert('Pagina internet non valida');
                    $(this).val('');
                }
            }
        });
    });
}
function checkSito(url) {
    re = /^http(s)?:\/\/[a-z0-9_\-\.]+(\.)[a-z]{2,4}/;
    if (url.match(re)) {
        return true;
    }
    return false;
}
function autoCheckCF(fields) {
    fields.forEach(function(field) {
        field.change(function() {
            $(this).val($(this).val().trim());
            if ($(this).val() != '') {
                if (!checkCF($(this).val()) && !checkPI($(this).val())) {
                    fancyAlert('Codie Fiscale non valido');
                    $(this).val('');
                }
            }
        });
    });
}
function checkCF(cf) {
    re = /^[a-zA-Z]{6}[0-9a-zA-Z]{2}[a-zA-Z]{1}[0-9a-zA-Z]{2}[a-zA-Z]{1}[0-9a-zA-Z]{3}[a-zA-Z]{1}$/;
    if (cf.match(re)) {
        return true;
    }
    return false;
}
function autoCheckREA(fields) {
    fields.forEach(function(field) {
        field.change(function() {
            $(this).val($(this).val().trim());
            if ($(this).val() != '') {
                if (!checkREA($(this).val())) {
                    fancyAlert('Codice REA non valido');
                    $(this).val('');
                }
            }
        });
    });
}
function checkREA(rea) {
    re = /^[a-zA-Z]{2}( |\-)?[0-9]{1,8}$/;
    if (rea.match(re)) {
        return true;
    }
    return false;
}
function autoCheckPI(fields) {
    fields.forEach(function(field) {
        field.change(function() {
            $(this).val($(this).val().trim());
            if ($(this).val() != '') {
                if (!checkPI($(this).val())) {
                    fancyAlert('Partita IVA non valida');
                    $(this).val('');
                }
            }
        });
    });
}
function checkPI(pi) {
    re = /^[0-9]{11}$/;
    if (pi.match(re)) {
        return true;
    }
    return false;
}

function checkCAP(cap) {
    re = /^[0-9]{5}$/;
    if ((cap + '').match(re)) {
        return true;
    }
    return false;
}

function getMatchRegexp(subject, re) {
    var m = re.exec(subject);
    if (m == null) {
        return false;
    } else {
        return m;
    }
}

function setPlaceholder(field, value) {
    if (field.val() == '')
        field.defaultvalue(value);
}

/* CHECK VARIABILI */
function is_int(input) {
    return typeof(input) == 'number' && parseInt(input) == input;
}
function is_string(input) {
    return typeof(input) == 'string';
}

function testVariabile(variabile) {
    return !(eval('window.' + variabile) === undefined);
}

function testFunction(funzione) {
    fx = eval('window.' + funzione);

    //alert(fx);

    if (fx === undefined) {
        return false;
        //  alert("UND");
    }
    //alert("def");
    return typeof(fx) == 'function';

}

function checkVariabili(exist) {
    if (!(testVariabile('noCheck') && noCheck)) {
        if (typeof exist == 'string') {
            if (eval('window.' + exist) === undefined) {
                alert("Definire '" + exist + "' per continuare");
            }
        }
        if (typeof exist == 'object') {
            exist.forEach(function(variabile) {
                if (eval('window.' + variabile) === undefined) {
                    alert("Definire '" + variabile + "' per continuare");
                }
            });
        }
    }
}

function setCampiObbligatori(check, callback) {
    ok = true;
    check.forEach(function(campo_form) {
        label = $('label[for="' + campo_form.attr('id') + '"]');
        label.addClass('obbligatorio');
        campo_form.change(function() {
            checkCampiObbligatori(check, callback);
        }).keyup(function() {
            checkCampiObbligatori(check, callback);
        });
        ok = ok && campo_form.val().trim() != '';
    });
    checkCampiObbligatori(check, callback)
}

function checkCampiObbligatori(check, callback) {
    ok = true;
    check.forEach(function(campo_form) {
        ok = ok && (campo_form.val().trim() != '');
        //alert(campo_form.attr('id')+' ('+(ok ? 1 : 0)+')');
    });
    eval(callback + '(' + (ok ? 'true' : 'false') + ')');
}

/* GEOLOCALIZZAZIONE */
var attiva_geolocalizzazione = true;

function test(position) {
    alert(position.coords.latitude + ':' + position.coords.longitude);
//  var s = document.querySelector('#status');
//  
//  if (s.className == 'success') {
//    // not sure why we're hitting this twice in FF, I think it's to do with a cached result coming back    
//    return;
//  }
//  
//  s.innerHTML = "found you!";
//  s.className = 'success';
//  
//  var mapcanvas = document.createElement('div');
//  mapcanvas.id = 'mapcanvas';
//  mapcanvas.style.height = '400px';
//  mapcanvas.style.width = '560px';
//    
//  document.querySelector('article').appendChild(mapcanvas);
//  
//  var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
//  var myOptions = {
//    zoom: 15,
//    center: latlng,
//    mapTypeControl: false,
//    navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
//    mapTypeId: google.maps.MapTypeId.ROADMAP
//  };
//  var map = new google.maps.Map(document.getElementById("mapcanvas"), myOptions);
//  
//  var marker = new google.maps.Marker({
//      position: latlng, 
//      map: map, 
//      title:"You are here! (at least within a "+position.coords.accuracy+" meter radius)"
//  });
}

function geoerror() {
    //alert(typeof msg == 'string' ? msg : serialize(msg));      
    if (testVariabile('url_errore_geo')) {
        checkVariabili(['url_errore_geo', 'set_geo_visible']);
        $.ajax({
            url: url_errore_geo,
            type: 'POST',
            success: function(msg) {
                $('#errorGeo').slideDown('slow');
                $("body").append(msg);
                //eval(callback_ajax_provincia+"('"+msg+"')");
                $('#chiudi_geo_alert').click(function() {
                    $('#errorGeo').slideUp('slow');
                    $.post(set_geo_visible, function(data) {
                    });
                });
            }
        });
    }
}

var geo_comune;
var geo_callback;
function getGeoComune(callback) {
    geo_callback = callback;
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(loadComune, geoerror);
    } else {
        geoerror();
    }
}

function loadComune(position, callback) {
    checkVariabili(['url_ajax_comune']);
    $.ajax({
        url: url_ajax_comune,
        data: "latitude=" + position.coords.latitude + "&longitude=" + position.coords.longitude,
        type: 'POST',
        success: function(msg) {
            geo_callback(msg);
        }
    });
    return true;
}
function getGeoProvincia() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(loadProvincia, geoerror);
    } else {
        geoerror();
    }
}

function loadProvincia(position) {
    checkVariabili(['url_ajax_provincia', 'callback_ajax_provincia']);
    $.ajax({
        url: url_ajax_provincia,
        data: "latitude=" + position.coords.latitude + "&longitude=" + position.coords.longitude,
        type: 'POST',
        success: function(msg) {
            eval(callback_ajax_provincia + "('" + msg + "')");
        }
    });
    return true;
}

function getGeoComuneProvincia() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(loadComuneProvincia, geoerror);
    } else {
        geoerror();
    }
}

function loadComuneProvincia(position) {
    checkVariabili(['url_ajax_comune_provincia', 'callback_ajax_comune_provincia']);
    $.ajax({
        url: url_ajax_comune_provincia,
        data: "latitude=" + position.coords.latitude + "&longitude=" + position.coords.longitude,
        type: 'POST',
        success: function(msg) {
            eval(callback_ajax_comune_provincia + "('" + msg + "')");
        }
    });
    return true;
}
function cercaIndirizzoSpinner(indirizzo, comune, callback, field, spinner, preloader_div) {
    if (field) {
        field.closest('div').append('<div id="' + preloader_div + '">&nbsp;</div>');
        spinner.spin();
        $('#' + preloader_div).append(spinner.el);
    }
    checkVariabili(['url_geocode']);
    $.ajax({
        url: url_geocode,
        data:
                (indirizzo ? "indirizzo=" + indirizzo : '') +
                (indirizzo && comune ? '&' : '') +
                (comune ? 'comune=' + comune : ''),
        type: 'POST',
        success: function(msg) {
            eval(callback + "('" + msg + "')");
            if (field) {
                spinner.stop();
                removeObj('#' + preloader_div);
            }
        },
        error: function(msg) {
            fancyAlert('Sei sicuro di aver scritto correttamente il tuo indirizzo?');
            if (field) {
                spinner.stop();
                removeObj('#' + preloader_div);
            }
        }
    });
    return true;
}
function cercaIndirizzo(indirizzo, comune, callback, field) {
    var inner_spinner = new Spinner(opts).spin();
    field.closest('div').append('<div id="preloader">&nbsp;</div>');
    inner_spinner.spin();
    $('#preloader').append(inner_spinner.el);
    checkVariabili(['url_geocode']);
    $.ajax({
        url: url_geocode,
        data: "indirizzo=" + indirizzo + '&comune=' + comune,
        type: 'POST',
        success: function(msg) {
            eval(callback + "('" + msg + "')");
            inner_spinner.stop();
            removeObj('#preloader');
        },
        error: function(msg) {
            fancyAlert('Sei sicuro di aver scritto correttamente il tuo indirizzo?');
            inner_spinner.stop();
            removeObj('#preloader');
        }
    });
    return true;
}
function cercaIndirizzoSmall(indirizzo, comune, callback, field) {
    var inner_spinner = new Spinner(optsbutblack).spin();
    field.closest('div').append('<div id="preloader-button">&nbsp;</div>');
    inner_spinner.spin();
    $('#preloader-button').append(inner_spinner.el);
    checkVariabili(['url_geocode']);
    $.ajax({
        url: url_geocode,
        data: "indirizzo=" + indirizzo + '&comune=' + comune,
        type: 'POST',
        success: function(msg) {
            eval(callback + "('" + msg + "')");
            inner_spinner.stop();
            removeObj('#preloader-button');
        },
        error: function(msg) {
            fancyAlert('Sei sicuro di aver scritto correttamente il tuo indirizzo?');
            inner_spinner.stop();
            removeObj('#preloader-button');
        }
    });
    return true;
}

oldComune = '';
oldComuneId = '';
/**
 * @var geo campo di ricerca comune
 * @var comune campo con id del comune
 * @var indirizzo campo indirizzo
 * @var cap campo cap
 * @var callback nome della funzione di callback per memorizzare i dati di ritorno della funzione cercaIndirizzo
 * @var spinner oggetto spinner
 */
function attiva_ricerca_comune(geo, comune, indirizzo, cap, callback, spinner, preloader_div) {
    checkVariabili(['url_search_ajax_comune']);

    if (indirizzo) {
        // Sanitizzazione delle variabili
        sanitize([indirizzo]);

        // Geolocalizzazione (verifica se si può centralizzare con altre geolocalizzazioni)
        indirizzo.change(function() {
            if (geo.val().trim() != '' && indirizzo.val() != '') {
                cercaIndirizzoSpinner(indirizzo.val(), geo.val(), callback, cap, spinner, preloader_div);
            }
        });
    }
    var comune_selezionato = comune.val() != '';
    var search_results = new Array();
    var blur = false;
    var find = false;

    //Attivazione ricerca comune sede impianto in autocomplete
    geo.autocomplete({
        minLength: 2,
        delay: 500,
        source: function(request, response) {
            geo.closest('div').append('<div id="' + preloader_div + '">&nbsp;</div>');
            spinner.spin();
            $('#' + preloader_div).append(spinner.el);
            $.ajax({
                url: url_search_ajax_comune,
                dataType: "json",
                data: {
                    maxRows: 10,
                    nome: request.term
                },
                success: function(data) {
                    $('ul.ui-autocomplete').css('z-index', 15000); // metto lo zindex per farlo vedere all'interno del FancyBox
                    if (blur) {
                        if (data.length == 0) {
                            alert('Comune non trovato');
                            geo.val(oldComune);
                            comune.val(oldComuneId);
                        } else {
                            c = data.first();
                            comune.val(c.id);
                            geo.val(c.nome + ' (' + c.admin2_code + ')');
                            if (indirizzo) {
                                if (c.nome + ' (' + c.admin2_code + ')' != '' && indirizzo.val() != '') {
                                    cercaIndirizzoSpinner(indirizzo.val(), c.nome + ' (' + c.admin2_code + ')', callback, cap, spinner, preloader_div);
                                }
                                cap.focus();
                            } else {
                                if (callback) {
                                    eval(callback + '()');
                                }
                            }
                            comune_selezionato = true;
                        }
                        blur = false;
                    } else {
                        if (data.length == 0) {
                            alert('Comune non trovato');
                            geo.val(oldComune);
                            comune.val(oldComuneId);
                        } else if (data.length == 1) {
                            c = data.first();
                            comune.val(c.id);
                            geo.val(c.nome + ' (' + c.admin2_code + ')');
                            if (indirizzo) {
                                if (c.nome + ' (' + c.admin2_code + ')' != '' && indirizzo.val() != '') {
                                    cercaIndirizzoSpinner(indirizzo.val(), c.nome + ' (' + c.admin2_code + ')', callback, cap, spinner, preloader_div);
                                }
                                cap.focus();
                            } else {
                                if (callback) {
                                    eval(callback + '()');
                                }
                            }
                            comune_selezionato = true;
                        } else {
                            find = data.first();
                            response($.map(data, function(item) {
                                search_results[item.nome.toLowerCase()] = {
                                    value: item.nome + ' (' + item.admin2_code + ')',
                                    id: item.id
                                };
                                return {
                                    value: item.nome + ' (' + item.admin2_code + ')',
                                    id: item.id
                                }
                            }));
                        }
                    }
                    spinner.stop();
                    removeObj('#' + preloader_div);
                }
            });
        },
        select: function(event, ui) {
            if (ui.item) {
                close = true;
                comune.val(ui.item.id);
                if (indirizzo) {
                    if (ui.item.value != '' && indirizzo.val() != '') {
                        cercaIndirizzoSpinner(indirizzo.val(), ui.item.value, callback, cap, spinner, preloader_div);
                    }
                    cap.focus();
                } else {
                    if (callback) {
                        eval(callback + '()');
                    }
                }
                comune_selezionato = true;
            }
        },
        open: function() {
            $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
        },
        close: function(event) {
            $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
            if (comune.val() == '') {
                geo.val(oldComune);
                comune.val(oldComuneId);
            }
        }
    }).focus(function() {
        blur = false;
        close = false;
        /*
         if(comune_selezionato) {
         comune_selezionato = false;
         oldComune = $(this).val();
         oldComuneId = comune.val();
         $(this).val('')
         comune.val('');
         }
         */
    }).blur(function() {
        blur = true;
        if (find && comune.val() == '') {
            geo.val(find.nome + ' (' + find.admin2_code + ')');
            comune.val(find.id);
            if (indirizzo) {
                if (find.nome != '' && indirizzo.val() != '') {
                    cercaIndirizzoSpinner(indirizzo.val(), find.nome + ' (' + find.admin2_code + ')', callback, cap, spinner, preloader_div);
                }
                cap.focus();
            } else {
                eval(callback + '()');
            }
            comune_selezionato = true;
            find = false;
        }
    });

}

oldProvincia = '';
oldProvinciaId = '';
/**
 * @var geo campo di ricerca provincia
 * @var provincia campo con id della provincia
 * @var next_focus campo he prenderà il focus
 * @var spinner oggetto spinner
 */
function attiva_ricerca_provincia(geo, provincia, next_focus, spinner) {
    checkVariabili(['url_search_ajax_provincia']);
    provincia_selezionata = provincia.val() != '';
    geo.autocomplete({
        minLength: 2,
        delay: 200,
        source: function(request, response) {
            geo.closest('div').append('<div id="preloader">&nbsp;</div>');
            spinner.spin();
            $('#preloader').append(spinner.el);
            $.ajax({
                url: url_search_ajax_provincia,
                dataType: "json",
                data: {
                    maxRows: 10,
                    nome: request.term
                },
                success: function(data) {
                    if (data.length == 0) {
                        fancyAlert('Provincia non trovata');
                        geo.val(oldProvincia);
                        provincia.val(oldProvinciaId);
                    } else if (data.length == 1) {
                        c = data.first();
                        provincia.val(c.id);
                        geo.val(c.nome);
                        provincia_selezionata = true;
                        next_focus.focus();
                    } else {
                        response($.map(data, function(item) {
                            return {
                                value: item.nome,
                                id: item.id
                            }
                        }));
                    }
                    spinner.stop();
                    removeObj('#preloader');
                }
            });
        },
        select: function(event, ui) {
            if (ui.item) {
                provincia.val(ui.item.id);
                provincia_selezionata = true;
                next_focus.focus();
            }
        },
        open: function() {
            $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
        },
        close: function() {
            $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
            if (provincia.val() == '') {
                geo.val(oldProvincia);
                provincia.val(oldProvinciaId);
            }
        }
    }).focus(function() {
        if (provincia_selezionata) {
            provincia_selezionata = false;
            oldProvincia = $(this).val();
            oldProvinciaId = provincia.val();
            $(this).val('')
            provincia.val('');
        }
    }).blur(function() {
        if (provincia.val() == '') {
            provincia_selezionata = true;
            $(this).val(oldProvincia);
            provincia.val(oldProvinciaId);
            oldProvincia = '';
            oldProvinciaId = '';
        }
    });
}

/***********
 * UTILITY *
 ***********/

function serialize(_obj) {
    if (_obj == null)
        return 'null';
    // Let Gecko browsers do this the easy way
    if (typeof _obj.toSource !== 'undefined' && typeof _obj.callee === 'undefined')
    {
        return _obj.toSource();
    }
    // Other browsers must do it the hard way
    switch (typeof _obj)
    {
        // numbers, booleans, and functions are trivial:
        // just return the object itself since its default .toString()
        // gives us exactly what we want
        case 'number':
        case 'boolean':
        case 'function':
            return _obj;
            break;

            // for JSON format, strings need to be wrapped in quotes
        case 'string':
            return '\'' + _obj + '\'';
            break;

        case 'object':
            var str;
            if (_obj.constructor === Array || typeof _obj.callee !== 'undefined')
            {
                str = '[';
                var i, len = _obj.length;
                for (i = 0; i < len - 1; i++) {
                    str += serialize(_obj[i]) + ',';
                }
                str += serialize(_obj[i]) + ']';
            }
            else
            {
                str = '{';
                var key;
                for (key in _obj) {
                    str += key + ':' + serialize(_obj[key]) + ',';
                }
                str = str.replace(/\,$/, '') + '}';
            }
            return str;
            break;

        default:
            return 'UNKNOWN';
            break;
    }
}

function fancyAlert(msg) {
    jQuery.fancybox({
        modal: true,
        padding: 3,
        margin: 0,
        content: "<div class=\"alert\"><h3>" + msg + "</h3><div style=\"text-align:center;margin-top:40px;\"><button class=\"button-orange large\" id=\"fancy-ok\" type=\"button\" onclick=\"jQuery.fancybox.close();\" >OK</button></div></div>"
    });
}

var fancyConfirmResult;
function fancyConfirm(msg, ok_txt, ko_txt, callback) {
    jQuery.fancybox({
        modal: true,
        padding: 3,
        margin: 0,
        content: "<div class=\"alert\"><h3>" + msg + "</h3><div style=\"text-align:right;margin-top:10px;\"><button id=\"fbc_ok\" onClick=\"javascript:fancyConfirmOk()\" class=\"button-orange large\" type=\"button\" value=\"" + ok_txt + "\">" + ok_txt + "</button><button id=\"fbc_ko\" onClick=\"javascript:fancyConfirmKo()\" class=\"button-orange large\" type=\"button\" id=\"fancyConfirm_cancel\" value=\"" + ko_txt + "\">" + ko_txt + "</button></div></div>",
        beforeClose: function() {
            eval(callback + "(" + (fancyConfirmResult ? "true" : "false") + ")");
        }
    });
}
function fancyConfirmOk() {
    fancyConfirmResult = true;
    jQuery.fancybox.close();
}
function fancyConfirmKo() {
    fancyConfirmResult = false;
    jQuery.fancybox.close();
}
function fancyConfirm_text() {
    fancyConfirm("Ceci est un test", "oui", "no", "alert");
}

function removeObj(id) {
    $(id).remove();
}

var historyState = 0;

/**
 * Cambia url senza fare reload e fa l'history
 */
function changeUrl(urlPath, pageTitle, event) {
    document.title = pageTitle;
    if (!is_explorer) {
        window.history.pushState("stato:" + (++historyState), pageTitle, urlPath);
        if (event) {
            window.onpopstate = function(event) {
                window.location = location.pathname;
            };
        }
    }
}

/* Funzione per i tabs */

function switch_tabs(obj, tabContent) {
    $(tabContent).hide();
    obj.closest('ul').children().children('a').removeClass("active");
    var id = obj.attr("rel");

    $('#' + id).show();
    obj.addClass("active");
}

function switch_class_tabs(obj, tabContent) {
    $(tabContent).hide();
    obj.closest('ul').children().children('a').removeClass("active");
    var id = obj.attr("rel");

    $('.' + id).show();
    obj.addClass("active");
}

/* caratteristiche dello spinner js */

/* spinner standard per i campi grandi */

var opts = {
    lines: 62, // The number of lines to draw
    length: 2, // The length of each line
    width: 2, // The line thickness
    radius: 6, // The radius of the inner circle
    color: '#333', // #rgb or #rrggbb
    speed: 2, // Rounds per second
    trail: 20, // Afterglow percentage
    shadow: false, // Whether to render a shadow
    hwaccel: true, // Whether to use hardware acceleration
    opacity: 1 / 32
};



var optsbigdef = {
    lines: 64, // The number of lines to draw
    length: 4, // The length of each line
    width: 3, // The line thickness
    radius: 10, // The radius of the inner circle
    color: '#333', // #rgb or #rrggbb
    speed: 2, // Rounds per second
    trail: 20, // Afterglow percentage
    shadow: false, // Whether to render a shadow
    hwaccel: true, // Whether to use hardware acceleration
    opacity: 1 / 32
};
var optsbig = {
    lines: 64, // The number of lines to draw
    length: 4, // The length of each line
    width: 2, // The line thickness
    radius: 10, // The radius of the inner circle
    color: '#fff', // #rgb or #rrggbb
    speed: 2, // Rounds per second
    trail: 20, // Afterglow percentage
    shadow: false, // Whether to render a shadow
    hwaccel: true, // Whether to use hardware acceleration
    opacity: 1 / 8
};
var optsbigtab = {/* spinner grande per i tabs */
    lines: 64, // The number of lines to draw
    length: 8, // The length of each line
    width: 5, // The line thickness
    radius: 20, // The radius of the inner circle
    color: '#4C80B6', // #rgb or #rrggbb
    speed: 2, // Rounds per second
    trail: 20, // Afterglow percentage
    shadow: false, // Whether to render a shadow
    hwaccel: true, // Whether to use hardware acceleration
    opacity: 1 / 8
};

var optsbar = {
    lines: 64, // The number of lines to draw
    length: 2, // The length of each line
    width: 2, // The line thickness
    radius: 6, // The radius of the inner circle
    color: '#fff', // #rgb or #rrggbb
    speed: 2, // Rounds per second
    trail: 20, // Afterglow percentage
    shadow: false, // Whether to render a shadow
    hwaccel: true, // Whether to use hardware acceleration
    opacity: 1 / 8
};


/* spinner piccolo per i bottoni scuri */

var optsbut = {
    lines: 64, // The number of lines to draw
    length: 1, // The length of each line
    width: 2, // The line thickness
    radius: 4, // The radius of the inner circle
    color: '#fff', // #rgb or #rrggbb
    speed: 2, // Rounds per second
    trail: 20, // Afterglow percentage
    shadow: false, // Whether to render a shadow
    hwaccel: true, // Whether to use hardware acceleration
    opacity: 1 / 8
};

/* spinner piccolo per i bottoni chiari */

var optsbutblack = {
    lines: 64, // The number of lines to draw
    length: 1, // The length of each line
    width: 2, // The line thickness
    radius: 4, // The radius of the inner circle
    color: '#333', // #rgb or #rrggbb
    speed: 2, // Rounds per second
    trail: 20, // Afterglow percentage
    shadow: false, // Whether to render a shadow
    hwaccel: true, // Whether to use hardware acceleration
    opacity: 1 / 32
};

/* definizione variabili spinners */

var spinnerDef = new Spinner(opts).spin(); // grigio medie dimensioni, da abbinare a "preloader" (uso di default)
var spinnerBigDef = new Spinner(optsbigdef).spin(); // grigio grande, da abbinare a "preloader"
var spinnerBig = new Spinner(optsbig).spin(); // bianco grande, da abbinare a "preloader-util"
var spinnerBigTab = new Spinner(optsbigtab).spin(); // bianco molto grande, da abbinare a "preloader-util" (usato per i grandi contenuti come i cambi di tab)
var spinnerBar = new Spinner(optsbar).spin(); // da abbinare a "preloader-bar"
var spinnerBut = new Spinner(optsbut).spin(); // bianco piccolo, da abbinare a "preloader-button"
var spinnerButBlack = new Spinner(optsbutblack).spin(); // nero piccolo, da abbinare a "preloader-button"

/*---------------------------*/

/*   attivazioni varie */

/*---------------------------*/

function showThis(idOfObj) {
    $(idOfObj).fadeIn(250);
    $('html').click(function() {
        $(idOfObj).fadeOut(250);
    });
}

loadContentCache = [];
loadContentPreload = [];
loadContenteReady = true;

function loadContent(url, target, idOfObj, param) {
    if (!param) {
        param = {};
    }
    key = url + target + idOfObj + serialize(param);
    $(idOfObj).fadeIn(250).append('<div id="preloader-icon">&nbsp;</div>');
    if (loadContenteReady) {
        loadContenteReady = false;
        if (!loadContentCache.find(function() { return key })) {
            loadContentCache.add(key);
            spinnerButBlack.spin();
            $('#preloader-icon').append(spinnerButBlack.el);
            $.ajax({
                url: url,
                data: param,
                context: document.body,
                success: function(data) {
                    loadContentPreload[key] = data;
                    $(target).html(data);
                    spinnerButBlack.stop();
                    removeObj('#preloader-icon');

                    $('html').click(function() {
                        $(idOfObj).fadeOut(250);
                    });
                    $(target).click(function(event) {
                        event.stopPropagation();
                    });

                    loadContenteReady = true;
                }
            })
        } else {
            $(target).html(loadContentPreload[key]);
            $('html').click(function() {
                $(idOfObj).fadeOut(250);
            });
            $(target).click(function(event) {
                event.stopPropagation();
            });
            loadContenteReady = true;
        }
    }
}

function setPositionBallon(_baloon, _target) {
    baloon = $('#' + _baloon);
    target = $('#' + _target).offset();
    baloon.offset({
        left: target.left,
        top: target.top + 10
    });
}
function scrollTo(o, s) {
    var d = $(o).offset().top;
    $("html:not(:animated),body:not(:animated)").animate({
        scrollTop: d
    }, s, 'swing');
}


$.widget("custom.suggestcomplete", $.ui.autocomplete, {
    _renderMenu: function(ul, items) {
        var self = this,
                currentCategory = "";
        $.each(items, function(index, item) {
            if (item.category != currentCategory) {
                ul.append("<li class='ui-autocomplete-category'>" + item.category.humanize() + "</li>");
                currentCategory = item.category;
            }
            self._renderItem(ul, item);
        });
    },
    _renderItem: function(ul, item) {
        return $("<li class=\"search-item\"></li>")
                .data("item.autocomplete", item)
                .append(generateSuggestRow(item))
                .appendTo(ul)
    }
});
function generateSuggestRow(item) {
    return "<a class=\"clearfix\"><span class='" + item.category + " left' style='margin-right: 5px;'>" + item.category + "</span><h4>" + item.label + "</h4></a>"
}

is_explorer = navigator.appName == "Microsoft Internet Explorer";

function login_lw_submit() {
    checkVariabili(['security_check']);
    $('#errore').remove();
    $('#form_login_lw .button-orange').html('Attendere...');
    $('#form_login_lw .button-orange').append('<div id="preloader">&nbsp;</div>');
    spinnerBar.spin();
    $('#preloader').append(spinnerBar.el);
    $.post(security_check,
            $('#form_login_lw').serialize(),
            function(data) {
                error = getMatchRegexp(data, /<span[^>]+>[^<]+<\/span>/);
                if (error) {
                    $('#submit_area').append('<div id="errore" class="margin-top-5">' + error + '</div>');
                } else {
                    $("#login_lw_container").html(data.replace(/login/g, '#form_login_lw').replace(/\<(\/)?section[^\>]*\>/g, ''));
                }
                spinnerBar.stop();
                removeObj('#preloader');
            }
    );
}

function login_submit() {
    checkVariabili(['security_check', 'ac_target']);
    $('#baloon_submit').html('Attendere&nbsp;&nbsp;&nbsp;&nbsp;');
    $('#baloon_submit').append('<div id="preloader-button">&nbsp;</div>');
    spinnerBut.spin();
    $('#preloader-button').append(spinnerBut.el);
    $.post(security_check,
            $('#form_login').serialize(),
            function(data) {
                $(ac_target).html(data);
                //alert(data);
                spinnerBut.stop();
                removeObj('#preloader-button');
            }
    );
}

function back_login() {
    $('#container_login').show();
    $('#container_forgot_password').hide();
}

function baloon_recupera() {
    $('#container_login').hide();
    $('#container_forgot_password').show();

//    $.ajax({
//        type: "POST",
//        url: Routing.generate('tipologia_categoria'),
//        data: {id_categoria: n.id.split("-").pop()}, //{ name: "John", location: "Boston" }
//        dataType: "html",
//        beforeSend: function() {
//            $("#" + n.id).removeAttr('onclick');// Non lo faccio piu cliccare
//            beforeElemento(n, '<p class="feedback_form">' + Translator.get('messages:caricamento') + '</p>', 'slow');
//        },
//        success: function(msg) {
//            msg = $.parseJSON(msg);
//            eliminaElemento(".feedback_form", 2000);
//            if (msg.status == "OK") {                
//                $(".feedback_form").html(Translator.get('messages:success'));
//            } else {
//                $(".feedback_form").html(Translator.get('messages:error'));
//            }                        
//        },
//        error: function(msg) {
//            $(".feedback_form").html(Translator.get('messages:error'));
//            eliminaElemento(".feedback_form", 2000);
//        }
//    });


}


/*---------------------------------- 
 Baloon 
 ----------------------------------*/
/**
 * 
 * <p>Apre il Baloon in Base al div</p>
 * 
 * @param {type} div padre che scatena l'evento
 * @param {type} secondi quanto millesecondi deve rimanere a video 
 */
var target_open = "";
var target = "";
var timer_chiusura = null;
function apri_baloon(div, secondi) {
    //alert("OK");

//    target_open = "";
//    timer_chiusura = "";
    $(div).mouseover(function() {
        target = $(this).attr('targetLink');
        if (target_open != target) {
            nascondiElemento($('#' + target_open), 'fast');
//            $(div).fadeOut(250, function() {
//            });
            if (target) {
                target_open = target;
                if ($('#' + target).is(":hidden")) {
                    attivaElemento('#' + target, 0);
                    //$('#' + target).show();                    
                }
            } else {
                nascondiElemento($('#' + target), 'fast');
            }
        } else {


        }
    }).mouseleave(function() {
        if ($('#' + target).is(":visible")) {
            //console.log($('#' + target));

            timer_chiusura = null;
            timer_chiusura = setInterval("chiudiElemento(target,timer_chiusura)", secondi);
            attivaComportamento(target, div, timer_chiusura, secondi);
        }
    });
}

function attivaComportamento(target, div, timer_chiusura, secondi) {
    focus = false;
    running = false;
    //console.log("timer_chiusura "+timer_chiusura);
    $("#" + target).bind('mouseleave', function(e) {
        clearInterval(timer_chiusura);
        if (!focus) {
            //if(timer_chiusura == null)
            timer_chiusura = setInterval("chiudiElemento(target,timer_chiusura)", secondi);
            running = true;
        }
    });

    $(div).bind('mouseover', function(e) {
        clearInterval(timer_chiusura);
    });

    $("#" + target).bind('mouseover', function(e) {
        clearInterval(timer_chiusura);
    });

    $(div).bind('mouseleave', function(e) {
        clearInterval(timer_chiusura);
        if (!focus) {
            if (timer_chiusura == null)
                timer_chiusura = setInterval("chiudiElemento(target,timer_chiusura)", secondi);
            running = true;
        }
    });

    $("#" + target).bind('keypress', function(e) {
        focus = true;
        clearInterval(timer_chiusura);
    });

    $('#' + target).find('input').focus(function() {
        focus = true;
        clearInterval(timer_chiusura);
    });
    $('#' + target).find('input').blur(function() {
        focus = false;
        clearInterval(timer_chiusura);
        if (!focus) {
            if (timer_chiusura == null)
                timer_chiusura = setInterval("chiudiElemento(target,timer_chiusura)", secondi);
            running = true;
        }
    });
}

function chiudiElemento(target, timer_chiusura) {
    target_open = '';
    nascondiElemento($('#' + target), 'fast');
    //$('#' + target).fadeOut('fast');
    clearInterval(timer_chiusura);
    timer_chiusura = null;
}

/*---------------------------------- 
 FINE Baloon 
 ----------------------------------*/

/*---------------------------------
 *
 *  FUNZIONI "ON DOCUMENT READY"
 *  
 ---------------------------------*/

/* funzioni per offset contenuti */

var headerHeight = 0;
var marginTop = 0;
var headerListingTopHeight = 0;
var columnContentHeight = 0;
var diff = 10;

var bodyHeight = 0
var columnHeight = 0
        

var bodyWidth = 0
var mdrWidth = 0
var columnWidth = 0




$(document).ready(function() {

    apri_baloon('.toggle-content', 1000);

    //$('.toggle-content').balloon({ position: "bottom" });


    /* funzione placeholder per i browser che non lo supportano nativamente */

    if (!Modernizr.input.placeholder) {
        $('[placeholder]').focus(function() {
            var input = $(this);
            if (input.val() == input.attr('placeholder')) {
                input.val('');
                input.removeClass('placeholder');
            }
        }).blur(function() {
            var input = $(this);
            if (input.val() == '' || input.val() == input.attr('placeholder')) {
                input.addClass('placeholder');
                input.val(input.attr('placeholder'));
            }
        }).blur();
        $('[placeholder]').parents('form').submit(function() {
            $(this).find('[placeholder]').each(function() {
                var input = $(this);
                if (input.val() == input.attr('placeholder')) {
                    input.val('');
                }
            })
        });

    }

    switch_tabs($('.defaulttab'));

    /* chiamata alla registrazione fancybox */
    $("a.businessPlusNew").fancybox({
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


    /* chiamata alla registrazione fancybox */
    $("a.register").fancybox({
        helpers: {
            overlay: {
                css: {
                    'background-color': 'rgba(0,0,0,.85)'
                },
                speedIn: 500,
                opacity: 0.5
            }
        },
        title: false,
        type: 'ajax',
        padding: 8,
        margin: 0,
        beforeShow: function() {
            $(".fancybox-skin").css("backgroundColor", "rgba(255,255,255,.5)");
            $(".fancybox-inner").css("backgroundColor", "rgba(255,255,255,1)");
        }
    });

    $("a.logger").fancybox({
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
        margin: 0,
        beforeShow: function() {
            $('#accesso').trigger('click');
        }

    });

    /* chiamata alla registrazione fancybox */
    $("a.fancymap").fancybox({
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


    attivatiptip();

    $().UItoTop({
        min: 50,
        easingType: 'easeOutQuart'
    });


    $(function() {
        positionFooter();
        function positionFooter() {
            if ($(document).height() < $(window).height()) {
                $("footer").css({
                    position: "absolute",
                    top: ($(window).scrollTop() + $(window).height() - $("footer").height()) + "px"
                })
            }
        }

        $(window).scroll(positionFooter).resize(positionFooter);
    });

    var slideHeight = ($('.slide-out-div').outerHeight());

    $('.slide-out-div').css('bottom', '-' + slideHeight + 'px')

    $("#handle_debug").on('click', function() {
        if ($(this).hasClass('selected')) {
            $('.slide-out-div').animate({
                'bottom': '-' + slideHeight + 'px'
            }, 'slow');
            $(this).removeClass("selected");
        }
        else {
            $('.slide-out-div').animate({
                'bottom': '0px'
            }, 'slow');
            $(this).addClass("selected");
        }
        return false;
    });
    $('.slide-out-div').show();

    function contentHeight(){
        bodyWidth = $(window).width(); // calcolo l'altezza della finestra visibile
        bodyHeight = $(window).height(); // calcolo l'altezza della finestra visibile
        headerHeight = parseInt($('header').css('height').remove('px')); // calcolo l'altezza dell'header
        columnHeight = (bodyHeight - headerHeight) + "px"; //utile soprattutto per la cermap
        
        $("#content_large").css("height", columnHeight);
    }

    contentHeight();

    setTopMargin(headerHeight, '#content_large'); // setto il margine del corpo dall'header

    $(window).resize(function() {
        contentHeight();
        if (bodyWidth > 960) {
            console.log(headerHeight);
            setTopMargin(headerHeight, '#content_large');
        } else {
            console.log('0')
            setTopMargin('', '#content_large');
        }
    });
});



/* marker voci menu principale */

function marker_pos(elem) {
    console.log("element: " + elem);
    if (!elem) {
        $('#menu-marker').hide();
    } else {
        var elem_width_half = ($(elem).outerWidth()) / 2;
        var elem_pos = $(elem).offset();
        var elem_left = elem_pos.left;
        var mid_marker = ($('#menu-marker').outerWidth()) / 2;

        var result = elem_left + elem_width_half - mid_marker;
        //console.log("result: " + result);
        $('#menu-marker').css({left: result + 'px'}).fadeIn();

        $(window).resize(function() {
            var elem_width_half = ($(elem).outerWidth()) / 2;
            var elem_pos = $(elem).offset();
            var elem_left = elem_pos.left;
            var mid_marker = ($('#menu-marker').outerWidth()) / 2;

            var result = elem_left + elem_width_half - mid_marker;
            $('#menu-marker').css({left: result + 'px'}).fadeIn();
        });
    }
}

function attivatiptip() {
    $(".tiptip").tipTip({
        keepAlive: false,
        delay: 0
    });
    $(".tiptip_large").tipTip({
        maxWidth: "600px",
        keepAlive: false,
        defaultPosition: 'top',
        delay: 0
    });
}

function setTopMargin(source, dest) {
    if (source == '') {
        $(dest).css('top', '0px');
    } else if (typeof(source) == 'number') {
        $(dest).css('top', source + 'px');
    } else {
        $(dest).css('top', $(source).css('height'));
    }
}

function setFixedFloat(source, target) {
    $(window).scroll(function(event) {
        var y = $(this).scrollTop() + (source);
        var top = $(target).offset().top - parseFloat($(target).css('margin-top').replace(/auto/, 0));

        if (y >= top) {
            $(target).addClass('fixed').css('top', (source) + 'px');
        } else {
            $(target).removeClass('fixed');
        }
    });
}

function check_skype(userSkype, obj) {
    obj.html('<a href="skype:' + userSkype + '?call" ><span class="skype status_0">skype</span></a>');
    $(document).ready(function() {
        $.post('/skype.php', {
            username: userSkype
        }, function(status_img) {
            if (status_img != '') {
                obj.html('<a href="skype:' + userSkype + '?call" class="on-disable"><span class="skype status_' + status_img + '">skype</span></a>');
            } else {
                obj.html('<a href="skype:' + userSkype + '?call" class="on-disable"><span class="skype status_0">skype</span></a>');
            }
        }).error(function() {
            obj.html('<a href="skype:' + userSkype + '?call" class="on-disable"><span class="skype status_0">skype</span></a>');
        });
    });
}


function removeFile(url) {
    $.ajax({
        url: url,
        type: 'DELETE',
        success: function(msg) {
            //alert(msg);
        }
    });
}



/*---------------------------------- 
 Effetti Su Elementi
 ----------------------------------*/

/*---------------------------------- 
 cancellazione / disabilitazione  elementi
 ----------------------------------*/

/**
 * <p>Disabilita un elemento, aggiungendo attr disabled e </p>
 * 
 * <p>Utile nei Form</p>
 * 
 * @param {div} selettore 
 */
function disabilitaElemento(selettore) {
    $(selettore).prop("disabled", true);
}

/**
 * <p>Cancella il contenuto del div selezionato</p>
 * <p>ma non il div contenitore</p>
 * 
 * @param {div} selettore
 */
function eliminaFigli(selettore) {
    $(selettore).empty();
}

/**
 * <p>Nasconde un elemento</p>
 * 
 * @param {div} selettore
 * @param {metodo} string fast,normal,slow
 */
function nascondiElemento(selettore, metodo) {
    $(selettore).fadeOut(metodo);
}

/**
 * <p>Elmina un elemento dal Dom</p>
 * 
 * @param {string} selettore
 * @param {number} delay millisendi prima di effettuare la cancellazione, utile per un feedback
 */
function eliminaElemento(selettore, delay) {
    $(selettore).delay(delay).fadeOut('normal', function() {
        $(selettore).remove();
    });
}

/*---------------------------------- 
 creazione / attivazione elementi
 ----------------------------------*/


/** 
 * <p>Riabilita l'elemento togliendo il disabled</p>
 * <p>Ho usato prop</p>
 * <p>Questo è utile per riabilitare TUTTI i pulsanti di un form che erano disabilitati</p>
 * 
 * @param {oggetto} selettore
 */
function abilitaElemento(selettore) {
    $(selettore).prop("disabled", false);
}

/**
 * <p>Attiva un elemento, rimuovendo il disabled</p>
 * <p>e riportando l'opacità a 1</p>
 * <p>.fadeTo( duration, opacity [, complete ] )</p>
 * @param {div} selettore 
 * @param {number} ritardo ritardo prima del di far apparire
 */
function attivaElemento(selettore, ritardo) {
    $(selettore).delay(ritardo).fadeTo('fast', 1, "linear", function() {
        $(selettore).attr("disabled", false);
    });
}




/**
 * <p>Aggiunge un Elemento nel dom</p>
 * 
 * @param {div} padre selettore dove attaccare l'elemento nuovo es:#barno
 * @param {html} elem html da attaccare
 * @param {number} durata millisecondi di fadeIn
 */
function creaElemento(padre, elem, durata) {
    $(elem).hide().appendTo(padre).fadeIn(durata);
}

/**
 * <p>Aggiunge un Elemento prima di un determinato elemento</p>
 * 
 * @param {div} padre selettore dove attaccare prima l'elemento nuovo es:#barno
 * @param {html} elem html da attaccare
 * @param {string} durata slow,normal,fast
 */
function beforeElemento(padre, elem, durata) {
    $(padre).before(elem).prev().hide().fadeIn(durata);

}
/**
 * <p>Aggiunge un Elemento dopo di un determinato elemento</p>
 * 
 * @param {div} padre selettore dove attaccare prima l'elemento nuovo es:#barno
 * @param {html} elem html da attaccare
 * @param {string} durata slow,normal,fast
 */
function afterElemento(padre, elem, durata) {
    $(padre).after(elem).prev().hide().fadeIn(durata);

}

/**
 * <p>Crea Messaggio Flash, che appare e scompare</p>
 * 
 * @param {div} padre dove attaccare l'elemento
 * @param {html} elem html da attaccare 
 * @param {number} durataIn millisencodi secondi per far apparire elem
 * @param {number} durata millisencodi dell'elemento a video
 * @param {number} durataOut millisencodi per far sparire l'elem
 */
function creaMessaggioFlash(padre, elem, durataIn, durata, durataOut) {
    $(elem).hide().appendTo(padre).fadeIn(durataIn).delay(durata).fadeOut(durataOut);
}

/**
 * <p>Mostra un elemento</p>
 * 
 * @param {div} selettore
 * @param {metodo} string fast,normal,slow
 */
function mostraElemento(selettore, metodo) {
    $(selettore).fadeIn(metodo);
}


/**
 * <p>Passando l'id del div che contiene campi form, questo azzerra completamente i campi</p>
 * 
 * @param {id class} element
 * 
 */
function clearChildren(element) {
    for (var i = 0; i < element.childNodes.length; i++) {
        var e = element.childNodes[i];
        if (e.tagName)
            switch (e.tagName.toLowerCase()) {
                case 'input':
                    switch (e.type) {
                        case "radio":
                        case "checkbox":
                            e.checked = false;
                            break;
                        case "button":
                        case "submit":
                        case "image":
                            break;
                        default:
                            e.value = '';
                            break;
                    }
                    break;
                case 'select':
                    e.selectedIndex = 0;
                    break;
                case 'textarea':
                    e.innerHTML = '';
                    break;
                default:
                    clearChildren(e);
            }
    }
}

/*---------------------------------- 
 FINE Effetti Su Elementi
 ----------------------------------*/

/*$(document).bind("contextmenu", function(e) {
 return false;
 });*/

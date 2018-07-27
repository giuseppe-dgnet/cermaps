var ajax_autocomplete = true;
var slider = null;
var usa_geocode = false;
var terra = 6372.795477598;
var cer = [];
var GeoMarker = null;
var tags = [];
var markerClusterer = null;

//var trip = new Trip([
//   { 
//       sel : $('#cermap_geo_luogo_container'),
//       content : 'This is element 1'
//   },
//   {
//       sel : $('#cermap_geo_dist'),
//       content : 'This is element 2'
//   }
//], options); // details about options are listed below
if ( ! window.console ) console = { log: function(){} };
$('document').ready(function() {
    if (trip_completo) {
        mostraElemento('#repeat_trip', 'normal');
    }
    $('#tutor').fadeIn();
    $('#overlay_tutor').fadeIn();

    //http://eragonj.github.io/Trip.js
    var trip = new Trip([
        {
            sel: $('#cosa_cerca'),
            position: 'e',
            content: '<span class="numbered-list gradient-blue">1</span><h4 class="margin-bottom-10">Inserisci la descrizione del tuo rifiuto<br>o il codice cer corrispondente</h4><p class="margin-bottom-10 text-small">CerMaps<sup><small>®</small></sup> ti suggerisce il codice in tempo reale.</p><img src="/bundles/escermap/images/tour/step1.jpg" class="rounded-3" style="width: 357px; height:113px;">',
            delay: -1,
            showNavigation: true,
            expose : true
        },
        {
            sel: $('#cermap_geo_luogo_container'),
            content: '<span class="numbered-list gradient-blue">2</span><h4 class="margin-bottom-10">Inserisci la località<br>in cui vuoi trovare gli operatori più indicati</h4><p class="margin-bottom-10 text-small">Il cerchio nella mappa ti indica il raggio selezionato.</p><img src="/bundles/escermap/images/tour/step2.jpg" class="rounded-3" style="width: 357px; height:113px;">',
            position: 'e',
            delay: -1,
            showNavigation: true,
            expose : true
        },
        {
            sel: $('.form-cermap'),
            content: '<span class="numbered-list gradient-blue">3</span><h4 class="margin-bottom-10">Cerca nelle vicinanze indicando<br>la distanza da dove si trova il rifiuto</h4><p class="margin-bottom-10 text-small">Oppure visita lo showroom dell\'operatore</p><img src="/bundles/escermap/images/tour/step3.jpg" class="rounded-3" style="width: 357px; height:113px;">',
            position: 'e',
            delay: -1,
            showNavigation: true,
            expose : true
        },
        {
            sel: $('#filtri_impianti'),
            content: '<span class="numbered-list gradient-blue">4</span><h4>Visualizza sulla mappa<br>solo il tipo di operatore che ti interessa</h4>',
            position: 'e',
            delay: -1,
            showNavigation: true,
            expose : true
        },
        {
            sel: $('#trip_centrale'),
            content: '<span class="numbered-list gradient-blue">5</span><h4 class="margin-bottom-10">Clicca sull\'icona e richiedi una quotazione di smaltimento,<br>oppure telefona al Centro Smaltimento Rifiuti</h4><img src="/bundles/escermap/images/tour/step5.jpg" class="rounded-3" style="width: 522px; height:330px;">',
            position: 's',
            delay: -1,
            showNavigation: true,
            expose : true
        }
    ], {
        onTripStart: function() {
            $('#tutor').fadeOut();
            $('#overlay_tutor').fadeOut();
        },
        onTripEnd: function() {
            mostraElemento('#repeat_trip', 'normal');
            imposta_cookie_trip()
        },
        onTripStop: function() {
            mostraElemento('#repeat_trip', 'normal');
        },
        tripTheme: 'black',
        backToTopWhenEnded: true,
        delay: 2000,
        overlayZindex: 99999
    });
    $('.stop_trip').click(function() {
        $('#tutor').fadeOut();
        $('#overlay_tutor').fadeOut();
        mostraElemento('#repeat_trip', 'normal');
        
        if ($('#trip_ck').prop('checked')) {
            imposta_cookie_trip();
        }
    });

    $(".start_trip").click(function() {
        trip.start();
    });

    $("#repeat_trip").click(function() {
        trip.start();
        nascondiElemento("#repeat_trip");
    });


    $('.map_detail').drags({
        handle: '.drag-pan'
    });

    $('.close-pan').click(function() {
        $('.map_detail').remove();
    });

    marker_pos('#menu_cermap');

    var scheda = false;
    var dd_width = $('#drag-data').width();
    /*$('#drag-data').css('left', "-" + dd_width + "px");*/
    $("#filtro").mCustomScrollbar({
        horizontalScroll: false,
        theme: "dark",
        scrollButtons: {
            enable: true
        },
        advanced: {
            updateOnContentResize: true
        }
    });
    $("#filtro").mCustomScrollbar("update");

    $('.open-close-tab').click(function() {
        if (scheda) {
            $('#drag-data').animate({
                left: '+=' + dd_width
            }, 200, 'swing', function() {
                $("#filtro").mCustomScrollbar("update");
                mapWidth();
                resizeMap();
            });
            //$(this).removeClass('selected');
            scheda = false;
        } else {
            $('#drag-data').animate({
                left: '-=' + dd_width
            }, 200, 'swing', function() {
                $("#filtro").mCustomScrollbar("update");
                mapWidth();
                resizeMap();
            });
            //$(this).addClass('selected');
            scheda = true;
        }

    });
    $("a.rdoRequestCer").fancybox({
        helpers: {
            overlay: {
                css: {
                    'background-color': 'rgba(0,0,0,.85)'
                },
                speedIn: 500,
                opacity: 0.5
            }
        },
        type: 'ajax',
        fitToView: false,
        autoSize: true,
        autoScale: true,
        openEffect: 'fade',
        closeEffect: 'fade',
        scrolling: false,
        padding: 8,
        margin: 0,
        beforeShow: function() {
            $(".fancybox-skin").css("backgroundColor", "rgba(255,255,255,.5)");
            $(".fancybox-inner").css("backgroundColor", "rgba(255,255,255,1)");
        },
        afterClose: function() {
            $('.box-rdo').hide();
            $('#console-rdo').show();
            //resetta_richieste("cer");
        }
    });

    $("#drag-data").show();





    var q_dist = $('#cermap_geo_dist');
    var cermap_geo = $('#cermap_geo_luogo');
    var q_lat = $('#cermap_geo_lat');
    var q_lon = $('#cermap_geo_lon');
    var q_comune = $('#cermap_geo_comune');

    attiva_ricerca_geo(cermap_geo, q_comune, q_lat, q_lon, spinnerButBlack, 'preloader-button', '#cermap_geo_luogo_container', 'aggiornaCermapGeo');

    // Invio tramite tastiera
    $(".form-cermap input").bind("keydown", function(event) {
        // track enter key
        var keycode = (event.keyCode ? event.keyCode : (event.which ? event.which : event.charCode));
        if (keycode == 13) {
            document.getElementById('submit_search_cermap').click();
            return false;
        } else {
            return true;
        }
    });

    q_dist.val(dist);
    q_lat.val(lat);
    q_lon.val(lon);
    cermap_geo.val(luogo);

    slider_id = 'slider_range';
    range_km_min = 10;
    range_km_max = 1000;
    slider = $("<div id='" + slider_id + "' style='width: 210px;  margin: 8px 0 0 10px;'></div>").slider({
        animate: true,
        range: "min",
        value: dist,
        min: range_km_min,
        max: range_km_max,
        step: 5,
        slide: function(event, ui) {
            circle.setRadius(ui.value * 1000);
            dist = ui.value;
            q_dist.val(dist);
            $('#range_km').text(dist + ' km');
        },
        stop: function(event, ui) {
            aggiornaCermapDist();
        }
    });
    $('#slider').append(slider);
    q_dist.change(function() {
        slider.slider("value", $(this).val());
        circle.setRadius($(this).val() * 1000);
        dist = $(this).val();
        $('#range_km').text(dist + ' km');
        aggiornaCermapDist();
    });

    $('.cb').click(function() {
        $(this).attr('checked', !$(this).attr('checked'));
        aggiornaCermapCb();
    });
    $("#nuvola_cermap_cosa").nuvolaInput(cerca_tag, {
        theme: 'facebook-cermap',
        preventDuplicates: true,
        minChars: 2,
        tokenLimit: 5,
        zindex: 1003,
        hintText: 'Descrivi il tuo rifiuto o il codice CER e premi invio',
        prePopulate: pre_populate,
        onDelete: function(item) {
            tags.remove(item.id);
            aggiornaCermapTag();
            $('.map_detail').remove();
        },
        onAdd: function(item) {
            tags.add(item.id);
            var html = $("#hidden_cer").html().assign(item);
            $("body").after(html);
            aggiornaCermapTag();

            //            $("#nuvola-input-cer").blur();
        }
    });
    /*
     pie();
     */


    $('body').addClass('has-js');
    $('.label_check, .label_radio').click(function() {
        setupLabel();
    });
    setupLabel();
    /*
     GeoMarker = new GeolocationMarker();
     GeoMarker.setCircleOptions({fillColor: '#808080'});
     
     google.maps.event.addListenerOnce(GeoMarker, 'position_changed', function() {
     map.setCenter(this.getPosition());
     map.fitBounds(this.getBounds());
     });
     
     google.maps.event.addListener(GeoMarker, 'geolocation_error', function(e) {
     alert('There was an error obtaining your position. Message: ' + e.message);
     });
     
     GeoMarker.setMap(map);
     */
    // setto le variabili per l'altezza degli elementi della pagina
    function contentHeightMap() {
        columnContentHeight = (bodyHeight - headerHeight) + "px"; //utile soprattutto per la cermap
        console.log(columnContentHeight);
        $("#drag-data").css("height", columnHeight);
        $("#canvasmap").css("height", columnHeight);
        $("#filtro").css("height", columnContentHeight);
        $("#filtro").mCustomScrollbar("update");
    }


    function mapWidth() {
        bodyWidth = $(window).width(); // calcolo la larghezza della finestra visibile
        mdrWidth = $('#drag-data').width(); // calcolo la larghezza del pannello filtri cermap
        mdrMarginLeft = parseInt($('#drag-data').css('left').remove('px'));
        columnWidth = (bodyWidth - mdrWidth - mdrMarginLeft) + "px";
        console.log("bodyWidth: " + bodyWidth);
        console.log("mdrWidth: " + mdrWidth);
        console.log("mdrMarginLeft: " + mdrMarginLeft);

        $("#canvasmap").css("width", columnWidth);
    }

    contentHeightMap();
    mapWidth();
    $(window).resize(function() {
        contentHeightMap();
        mapWidth();
    });
});


function setupLabel() {
    if ($('.label_check input').length) {
        $('.label_check').each(function() {
            $(this).removeClass('c_on');
        });
        $('.label_check input:checked').each(function() {
            $(this).parent('label').addClass('c_on');
        });
    }
    ;
    if ($('.label_radio input').length) {
        $('.label_radio').each(function() {
            $(this).removeClass('r_on');
        });
        $('.label_radio input:checked').each(function() {
            $(this).parent('label').addClass('r_on');
        });
    }
    ;
}
;


function imposta_cookie_trip() {
    $.ajax({
        type: "POST",
        url: Routing.generate('imposta_cookies_trip'),
        dataType: "html",
        success: function(msg) {
            msg = $.parseJSON(msg);
        },
        error: function(msg) {
        }
    });

}


var markers = [];
var infowindows = {};

var active_iw = null;
var active_mk = null;

function addMarker(row) {
    mdist = row.distanza;
    var marker = new google.maps.Marker({
        "map": map,
        "position": new google.maps.LatLng(row.latitudine, row.longitudine, true),
        "clickable": true,
        "visible": true,
        "title": row.ragione_sociale,
        "flat": true,
        "icon": "/bundles/escermap/images/marker/" + row.attivita_principale + ".png"
    });
    htmlwindow = $('#template_empty_balloon').html();

    var infowindow = new InfoBubble({
        content: htmlwindow.assign(row),
        maxWidth: 550,
        padding: 0,
        disableAnimation: false,
        borderRadius: 5
    });

//    var infowindow = new google.maps.InfoWindow({
//        "maxWidth": 100,
//        "pixelOffset": new google.maps.Size(0, 35, "px", "px"),
//        "content": htmlwindow.assign(row),
//        "disableAutoPan": false,
//        "zIndex": 10
//    });
    var event = google.maps.event.addListener(marker, "click", function() {
        for (var id in infowindows) {
            infowindows[id].close();
        }
        active_iw = infowindow;
        active_mk = marker;
        id = getMatchRegexp(infowindow.getContent(), /[0-9]+/);
        loadInfo(id.shift());
    });
    markers.add(marker);
    infowindows[row.id] = infowindow;

    resizeMap();
}

function resizeMap()
{
    google.maps.event.trigger(map, 'resize');
    map.setZoom(map.getZoom());
    map.setCenter(marker.getPosition());
}

function clearClusters(e) {
    e.preventDefault();
    e.stopPropagation();
    markerClusterer.clearMarkers();
}

function search_cermap() {
    ajax_autocomplete = false;
    if (is_explorer) {
        if ($('#cermap_geo_luogo').val() === 'Località') {
            $('#cermap_geo_luogo').val('');
        }
    }
    if ($('#cermap_geo_luogo').val().trim() === '') {
        $('#cermap_geo_comune').val('');
        $('#cermap_geo_lat').val(41.87194);
        $('#cermap_geo_lon').val(12.56738);
        $('#cermap_geo_dist').val(100);
        lat = 41.87194;
        lon = 12.56738;
        dist = 100;
        luogo = 'Italia';
        usa_geocode = false;
        aggiornaCermap();
    }
    if (usa_geocode) {
        cercaIndirizzoSpinner($('#cermap_geo_luogo').val(), false, 'setGeoCermap', false, spinnerButBlack, 'preloader');
    } else {
        aggiornaCermap();
    }
    return true;
}

function setGeoCermap(json) {
    response = JSON.parse(json);
    $('#cermap_geo_lat').val(response.lat);
    $('#cermap_geo_lon').val(response.lon);
    $('#cermap_geo_dist').val(100);
    lat = response.lat;
    lon = response.lon;
    aggiornaCermap();
}

function aggiornaCermap() {
    for (var id in infowindows) {
        infowindows[id].close();
    }
    marker.setPosition(new google.maps.LatLng(lat, lon, true));
    circle.setCenter(new google.maps.LatLng(lat, lon, true));
    map.panTo(new google.maps.LatLng(lat, lon, true));
    markers.each(function(_marker) {
        _marker.setMap(null);
        if (_marker.getMap() === null) {
            markers.remove(_marker);
        }
    });
//    markers = [];
}

function setData() {
    /*$('#load').slideUp('slow');
     $('#filtro').slideDown('slow');
     */
    $.post(url_data, {}, function(json) {
        if (json == 'end') {
            $('#load').slideUp('slow');
            $('#filtro').slideDown('slow');
            notifiche = true;
        } else {
            data.merge(json);
            setData();
            $('#count').html(data.keys().length + '/' + load_totale);
            pie();
        }
    })

}

var sem_geo = true;
var usa_geocode = false;
var ajax_autocomplete = true;
var ac_comune = true;
var old_geo = '';

/**
 * @var geo campo di ricerca comune
 * @var comune campo con id del comune
 * @var cap campo cap
 * @var callback nome della funzione di callback per memorizzare i dati di ritorno della funzione cercaIndirizzo
 * @var spinner oggetto spinner
 */
function attiva_ricerca_geo(geo, comune, latitudine, longitudine, spinner, preloader_div, element, callback) {
    s = spinner;
    pd = preloader_div;
    var search_results = new Array();
    checkVariabili(['url_search_ajax_comune']);
    // Geolocalizzazione (verifica se si può centralizzare con altre geolocalizzazioni)
    geo.focus(function() {
        old_geo = $(this).val();
        $(this).val('');
    }).blur(function() {
        if ($(this).val().trim() === '') {
            $(this).val(old_geo);
        }
    });
    geo.keypress(function() {
        comune.val('');
        latitudine.val('');
        longitudine.val('');
        if ($(this).val() === '') {
            usa_geocode = false;
            geo.autocomplete("enable");
        } else {
            usa_geocode = true;
        }
    });
    var min_char = 2;
    //Attivazione ricerca comune sede impianto in autocomplete
    geo.autocomplete({
        minLength: min_char,
        delay: 750,
        source: function(request, response) {
            geo.closest(element).append('<div id="' + preloader_div + '">&nbsp;</div>');
            spinner.spin();
            $('#' + preloader_div).append(spinner.el);
            if (sem_geo && ajax_autocomplete) {
                sem_geo = false;
                $.ajax({
                    url: url_search_ajax_comune,
                    dataType: "json",
                    data: {
                        maxRows: 10,
                        nazione: nazione,
                        nome: request.term
                    },
                    success: function(data) {
                        $('ul.ui-autocomplete').css('z-index', 1500000);
                        if (ac_comune) {
                            if (data.length === 0) {
                                spinner.stop();
                                removeObj('#' + preloader_div);
                            } else if (data.length === 1) {
                                c = data.first();
                                comune.val(c.id);
                                if (c.latitude) {
                                    usa_geocode = false;
                                    latitudine.val(c.latitude);
                                    longitudine.val(c.longitude);

                                    if (testVariabile("lat")) {
                                        lat = parseFloat(c.latitude);
                                        lon = parseFloat(c.longitude);
                                        dist = 100;
                                    }
                                    if (callback) {
                                        eval(callback + '();');
                                    }
                                }
                                geo.val(c.nome + ' (' + c.admin2_code + ')');
                                if (c.nome + ' (' + c.admin2_code + ')' !== '') {
                                    luogo = c.nome + ' (' + c.admin2_code + ')';
                                }
                                geo.autocomplete("disable");
                                geo.focus();
                            } else {
                                if (comune.val() === '') {
                                    response($.map(data, function(item) {
                                        search_results[item.nome.toLowerCase()] = {
                                            value: item.nome + ' (' + item.admin2_code + ')',
                                            lat: item.latitude,
                                            lon: item.longitude,
                                            id: item.id
                                        };
                                        return {
                                            value: item.nome + ' (' + item.admin2_code + ')',
                                            lat: item.latitude,
                                            lon: item.longitude,
                                            id: item.id
                                        };
                                    }));
                                }
                            }
                        }
                        spinner.stop();
                        removeObj('#' + preloader_div);
                        sem_geo = true;
                    }
                });
            }
        },
        select: function(event, ui) {
            if (ui.item) {
                comune.val(ui.item.id);
                if (ui.item.lat) {
                    usa_geocode = false;
                    latitudine.val(ui.item.lat);
                    longitudine.val(ui.item.lon);
                    if (testVariabile("lat")) {
                        lat = parseFloat(ui.item.lat);
                        lon = parseFloat(ui.item.lon);
                        dist = 100;
                    }
                    if (callback) {
                        eval(callback + '();');
                    }
                }
                luogo = ui.item.value;
                geo.autocomplete("disable");
                geo.focus();
            }
        },
        open: function() {
            $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
        },
        close: function() {
            $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
        }
    }).focus(function() {
        ac_comune = true;
    }).blur(function() {
        if (search_results[$(this).val().toLowerCase()]) {
            find = search_results[$(this).val().toLowerCase()];
            $(this).val(find.value);
            comune.val(find.id);
            usa_geocode = false;
            latitudine.val(find.lat);
            longitudine.val(find.lon);

            if (testVariabile("lat")) {
                lat = find.lat;
                lon = find.lon;
                dist = 100;
            }
            if (callback) {
                eval(callback + '();');
            }
        } else {
            ac_comune = false;
        }
        spinner.stop();
        removeObj('#' + preloader_div);
    });
}

var a_cer = [];

function sendRequest() {
    a_cer = tags;
    $('a.rdoRequestCer').trigger('click');
}

function resetta_richieste() {
}
var noc = '';
mcOptions = {
    maxZoom: 14,
    gridSize: 30,
    styles: [{
        textColor: '#ffffff',
        textSize: 17,
        height: 53,
        url: "https://github.com/googlemaps/js-marker-clusterer/blob/gh-pages/images/m1.png?raw=true",
        width: 53
    },
    {
        textColor: '#000000',
        height: 56,
        url: "https://github.com/googlemaps/js-marker-clusterer/blob/gh-pages/images/m2.png?raw=true",
        width: 56
    },
    {
        textColor: '#ffffff',
        height: 66,
        url: "https://github.com/googlemaps/js-marker-clusterer/blob/gh-pages/images/m3.png?raw=true",
        width: 66
    },
    {
        textColor: '#ffffff',
        height: 78,
        url: "https://github.com/googlemaps/js-marker-clusterer/blob/gh-pages/images/m4.png?raw=true",
        width: 78
    },
    {
        textColor: '#ffffff',
        height: 90,
        url: "https://github.com/googlemaps/js-marker-clusterer/blob/gh-pages/images/m5.png?raw=true",
        width: 90
    }]
};

$('document').ready(function() {
    aggiornaCermapAll();

    socketCerMap.on('update_map', function(rows) {
        console.log('update_map');
        if (markerClusterer) {
            markerClusterer.clearMarkers();
        }
        rows.each(function(row) {
            addMarker(row);
        });
        markerClusterer = new MarkerClusterer(map, markers, mcOptions);
    });

    socketCerMap.on('reset_map', function() {
        console.log('reset_map');
        aggiornaCermapAll();
    });

    socketCerMap.on('aggiorna_nome_localita', function(data) {
        $('#cermap_geo_luogo').val(data.comune + ' (' + data.provincia + ') a ' + km(data.distanza) + ' da ' + data.localita).effect("highlight", {}, 1000);
        spinnerButBlack.stop();
        removeObj('#preloader-button');
    });

    socketCerMap.on('aggiorna_detail', function(data) {
        console.log(data);
        html = $('#template_balloon').html();
        attivita = '';
        var as = ['impianto', 'discarica', 'raccoglitore', 'trasportatore', 'servizi', 'laboratorio', 'demolizioni', 'spurghi', 'rottamazione', 'bonifiche', 'raee', 'olio_minerale', 'olio_vegetale'];
        var nas = 0;
        as.remove(attivitaPrincipale(data.attivita_principale));
        as.each(function(att) {
            if (eval('data.' + att)) {
                attivita += $('#attivita_' + att).html();
                nas++;
            }
        });
        data['elenco_attivita'] = attivita;
        data['h_attivita_principale'] = data.attivita_principale.humanize();
        if (!data.descrizione_attivita) {
            data['descrizione_attivita'] = data.attivita_principale.spacify().capitalize();
        }
        if (!data.logo) {
            data['logo'] = '/bundles/esweb/images/azienda_placeholder.jpg';
        }
        data['telefono'] = $('#bt_telefono_' + data.telefono).html().assign(data);
        //data['telefono'] = '';
        data['rdo'] = $('#bt_rdo_1').html().replace(/slug/, '{slug}').assign(data);
//        data['rdo'] = $('#bt_rdo_' + (data.cer > 0 ? 1 : 0)).html().replace(/slug/, '{slug}').assign(data);
        data['slug'] = $('#bt_showroom').html().replace(/slug/, '{slug}').assign(data);
        data['distanza'] = km(data.distanza);

        var content = html.assign(data);

        $('#tmp').html(content);
        if (nas === 0) {
            $('.as').remove();
        }
        $('.test').each(function() {
            if ($(this).text() === '0') {
                $(this).closest('tr').remove();
            }
        });
        content = $('#tmp').html();
        $('#tmp').html('');
        active_iw.setContent(content);
        setTimeout(function() {
            active_iw.open(map, active_mk);
        }, 100);
    });

    function attivitaPrincipale(ap) {
        if (ap === 'servizi_ambientali')
            return 'servizi';
        if (ap === 'laboratori')
            return 'laboratorio';
        return ap;
    }

    google.maps.event.addListener(
            marker,
            'dragend',
            function() {
                $('#cermap_geo_luogo_container').append('<div id="preloader-button">&nbsp;</div>');
                spinnerButBlack.spin();
                $('#preloader-button').append(spinnerButBlack.el);
                lat = marker.position.lat();
                lon = marker.position.lng();
                $('#cermap_geo_luogo').val(lat + 'x' + lon);
                aggiornaCermap();
                socketCerMap.emit('cambia_geo', lat, lon);
                socketCerMap.emit('info_geo', lat, lon);
            }
    );

    socketCerMap.on('aggiorna_utenti', function(n) {
        $('#utenti').html(n);
    });

    window.setInterval(function() {
        socketCerMap.emit('utenti_online');
    }, 10000);

    socketCerMap.emit('utenti_online');
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
    spinnerButBlack.spin();
    $('#preloader').append(spinnerButBlack.el);

    $.ajax({
        type: "POST",
        url: Routing.generate('mostra_informazioni_showroom'),
        data: {informazione_richiesta: target.attr('attr').split("-").shift(), id_showroom: target.attr('attr').split("-").pop()}, //{ name: "John", location: "Boston" }
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
                //eliminaElemento(target,500);
                afterElemento(target, '<div class="absolute" style="top: -40px;"><div class=" rounded-5 padding-10 shadow gradient-black text-white">(+39) ' + msg.result + '</div><div class="absolute" style="bottom: -5px; left: 10px; border-top: solid 5px #666; border-left: 5px solid transparent; border-right: 5px solid transparent"></div></div>', 'slow');
                spinnerButBlack.stop();
                removeObj('#preloader');
            } else {
                //$(".feedback_form").html(Translator.get('messages:error'));
                spinnerButBlack.stop();
                removeObj('#preloader-balloon');
            }
            //creaElemento();
            //$("#contenitore_cer_" + n.id.split("-").pop()).html(msg);
        },
        error: function(msg) {
            //$(".feedback_form").html(Translator.get('messages:error'));
            eliminaElemento(".feedback_form", 2000);
            spinnerButBlack.stop();
            removeObj('#preloader');
        }
    });
}

function aggiornaCermapCb() {
    cb = [];
    $('.cb').each(function() {
        if ($(this).attr('checked')) {
            cb.add($(this).val());
        }
    });
    aggiornaCermap();
    socketCerMap.emit('cambia_tipo', cb);
}

function aggiornaCermapDist() {
    aggiornaCermap();
    socketCerMap.emit('cambia_dist', dist);
}

function aggiornaCermapTag() {
    aggiornaCermap();
    socketCerMap.emit('cambia_key', tags);
}

function aggiornaCermapGeo() {
    aggiornaCermap();
    socketCerMap.emit('cambia_geo', lat, lon);
}

function aggiornaCermapAll() {
    update_map = true;
    cb = [];
    $('.cb').each(function() {
        if ($(this).attr('checked')) {
            cb.add($(this).val());
        }
    });
    aggiornaCermap();
    socketCerMap.emit('cambia_all', lat, lon, dist, tags, cb);
}

function loadInfo(id) {
    socketCerMap.emit('dettagli', id);
}
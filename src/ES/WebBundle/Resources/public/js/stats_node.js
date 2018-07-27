var markers = [];
var n_marker = 10;
$('document').ready(function() {
    socketCerMap.on('aggiorna_localita', function(rows) {
        markers.each(function(marker) {
            marker.setMap(null);
        });
        markers = [];
        rows.each(function(row) {
            addMarker(row);
        });
    });

    socketCerMap.on('aggiorna_utenti', function(n) {
        $('#utenti').html(n);
    });
    if (n_marker > 0) {
        window.setInterval(function() {
            socketCerMap.emit('ultime_localita', n_marker);
        }, 10000);
        socketCerMap.emit('ultime_localita', n_marker);
    }

    window.setInterval(function() {
        socketCerMap.emit('utenti_online');
    }, 10000);
    socketCerMap.emit('utenti_online');
});

function addMarker(row) {
    var marker = new google.maps.Marker({
        "map": map,
        "position": new google.maps.LatLng(row.latd, row.lond, true),
        "clickable": false,
        "visible": true,
        "title": 'Visitatore',
//        "icon": "/bundles/escermap/images/marker/" + row.attivita_principale + ".png",
        "flat": true
    });
    markers.add(marker);
}

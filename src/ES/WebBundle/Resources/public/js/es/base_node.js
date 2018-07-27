$('document').ready(function() {
    cb = [];
    $('.cb').each(function() {
        if ($(this).attr('checked')) {
            cb.add($(this).val());
        }
    });
    socketCerMap.on('connect', function() {
        // La connessione si apre su una chatroom predefinita
        socketCerMap.emit('adduser', user, lat, lon, dist, tags, cb);
    });
    
    socketCerMap.on('get_user', function() {
        // La connessione si apre su una chatroom predefinita
        socketCerMap.emit('set_user', user);
    });

});

var guid = function() {
    var dataHex = Date.create('now').getTime().toString(16);
    return dataHex.to(8) + '-' + 'M' + dataHex.from(8) + '-xxxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = Math.random() * 16 | 0, v = c === 'x' ? r : r & 0x3 | 0x8;
        return v.toString(16);
    });
};

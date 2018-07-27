var express = require('express')
        , app = express()
        , server = require('http').createServer(app)
        , io = require('socket.io').listen(server)
        , sugar = require('sugar')
        , fs = require('fs')
        , mysql = require('mysql')
        ;

sugar.extend();

var terra = 6372.795477598;
var cermap_port = 0;
var server_number = 0;
var database_host = '127.0.0.1';
var database_port = 3306;
var database_name = 'node_db';
var database_user = 'root';
var database_password = null;
var db_pool = null;
var localita = [];

var parameters_file = '/var/www/dev.cermaps.com/app/config/parameters.yml';

fs.readFile(parameters_file, 'utf8', function(err, data) {
    if (err) {
        return console.log(err);
    }
    // Abilito il listener della chat
    chat_port = readParam(data, 'node.cermap.port:', cermap_port);
    console.log('Try to enable CerMap on port ' + chat_port);
    server.listen(chat_port, function () {
        console.log('CerMap enabled on port ' + chat_port);
    });

    server_number = readParam(data, 'server_number:', server_number);
    console.log('Server number ' + server_number);

    database_host = readParam(data, 'database_host:', database_host);
    database_port = readParam(data, 'database_port:', database_port);
    database_name = readParam(data, 'database_name:', database_name);
    database_user = readParam(data, 'database_user:', database_user);
    database_password = readParam(data, 'database_password:', database_password);

    db_pool = mysql.createPool({
        database: database_name,
        port: database_port,
        host: database_host,
        user: database_user,
        password: database_password
    });
    console.log('Connected to db');
});

var readParam = function(parameters, parameter, default_value) {
    out = '';
    start = parameters.search(parameter);
    if (start > 0) {
        start += parameter.length;
        out = parameters.substr(start);
        end = out.search('\n');
        if (end > 0) {
            out = out.substr(0, end).trim();
        } else {
            out = out.trim();
        }
    }
    if (out.search(/^null$/i) >= 0 || out === '') {
        out = default_value;
    } else if (out.search(/^(true|yes)$/i) >= 0) {
        out = true;
    } else if (out.search(/^(false|no)$/i) >= 0) {
        out = false;
    }
    return out;
};

var queryFormat = function(query, values) {
    if (!values)
        return query;
    return query.replace(/\:(\w+)/g, function(txt, key) {
        if (values.hasOwnProperty(key)) {
            return this.escape(values[key]);
        }
        return txt;
    }.bind(this));
};

var array_dc2type = function(data) {
    var inner = '';
    i = 0;
    data.forEach(function(elem) {
        inner += 'i:' + i + ';s:' + elem.length + ':"' + elem + '";';
        i++;
    });
    return 'a:' + data.length + ':{' + inner + '}';
};

var dc2type_array = function(data) {
    data = data.replace(/(a|s|i):[0-9]+(:|;)/g, '').replace(/\{/g, '[').replace(/[;]?\}/g, ']').replace(/;/g, ',');
    return eval(data);
};

var guid = function() {
    var dataHex = Date.create('now').getTime().toString(16);
    return dataHex.to(8) + '-' + server_number + dataHex.from(8) + '-xxxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = Math.random() * 16 | 0, v = c === 'x' ? r : r & 0x3 | 0x8;
        return v.toString(16);
    });
};

var deg2rad = function(deg) {
    return deg * Math.PI / 180;
};

var rad2deg = function(rad) {
    return rad * 180 / Math.PI;
};

// routing di express ??
app.get('/', function(req, res) {
    res.sendfile(__dirname + '/example.html');
});

// username degli utenti che sono in chat
var users = {};
var notify = {};

io.sockets.on('connection', function(socket) {

    /**
     * emits 'adduser'
     * 
     * Aggiunge un utente in chat e lo fa entrare nella stanza default
     * @var username string username adottato in chat
     */
    socket.on('adduser', function(user, lat, lon, dist, key, tipi) {
        // memorizzo l'username nella sessione del socket associata al client
        socket.username = user;
        // memorizzo il raggio di ricerca
        socket.dist = dist;
        // memorizzo il raggio di ricerca
        socket.key = key;
        // memorizzo il raggio di ricerca
        socket.tipo = tipi;
        // memorizzo le coordinare dell'utente
        socket.geo = {latd: lat, lond: lon, latr: deg2rad(lat), lonr: deg2rad(lon)};
        //Memorizzo l'utente
        users[user] = user;
        // memorizzo la località di ricerca
        localita.add(socket.geo);
        if(localita.length > 100) {
            localita.shift();
        }
        //Effettuo la ricerca prliminare
//        cerca(socket, function(rows) {
//            socket.emit('update_map', rows);
//        });
    });

    /**
     * emits 'cambia_geo'
     * 
     * Riceve le nuove coordiante
     * @var lat float latitudine
     * @var lon float longitudine
     */
    socket.on('cambia_all', function(lat, lon, dist, key, tipi) {
        // memorizzo la stanza di default nella sessione del socket associata al client
        socket.geo = {latd: lat, lond: lon, latr: deg2rad(lat), lonr: deg2rad(lon)};
        // memorizzo la località di ricerca
        localita.add(socket.geo);
        if(localita.length > 100) {
            localita.shift();
        }
        socket.dist = dist;
        socket.key = key;
        socket.tipo = tipi;
        cerca(socket, function(rows) {
            socket.emit('update_map', rows);
        });
    });

    /**
     * emits 'cambia_geo'
     * 
     * Riceve le nuove coordiante
     * @var lat float latitudine
     * @var lon float longitudine
     */
    socket.on('cambia_geo', function(lat, lon) {
        // memorizzo la stanza di default nella sessione del socket associata al client
        socket.geo = {latd: lat, lond: lon, latr: deg2rad(lat), lonr: deg2rad(lon)};
        // memorizzo la località di ricerca
        localita.add(socket.geo);
        if(localita.length > 100) {
            localita.shift();
        }
        cerca(socket, function(rows) {
            socket.emit('update_map', rows);
        });
    });
    
    /**
     * emits 'info_geo'
     * 
     * Riceve le nuove coordiante
     * @var lat float latitudine
     * @var lon float longitudine
     */
    socket.on('info_geo', function(lat, lon) {
        nomeLocalita(lat, lon, function(row) {
            socket.emit('aggiorna_nome_localita', row);
        });
    });

    /**
     * emits 'cambia_tipo'
     * 
     * Riceve un array con le nuove tipologie di risultati cercati
     * @var tipi array elenco delle tipologie ricercate
     */
    socket.on('cambia_tipo', function(tipi) {
        // memorizzo la stanza di default nella sessione del socket associata al client
        socket.tipo = tipi;
        cerca(socket, function(rows) {
            socket.emit('update_map', rows);
        });
    });

    /**
     * emits 'cambia_dist'
     * 
     * Riceve il nuovo raggio di ricerca espresso in km
     * @var dist integer raggio in km
     */
    socket.on('cambia_dist', function(dist) {
        // memorizzo la stanza di default nella sessione del socket associata al client
        socket.dist = dist;
        cerca(socket, function(rows) {
            socket.emit('update_map', rows);
        });
    });

    /**
     * emits 'cambia_key'
     * 
     * Riceve la stringa da cercare
     * @var key string testo da cercare
     */
    socket.on('cambia_key', function(key) {
        // memorizzo la stanza di default nella sessione del socket associata al client
        socket.key = key;
        cerca(socket, function(rows) {
            socket.emit('update_map', rows);
        });
    });

    /**
     * emits 'cambia_key'
     * 
     * Riceve la stringa da cercare
     * @var key string testo da cercare
     */
    socket.on('set_user', function(user) {
        // memorizzo la stanza di default nella sessione del socket associata al client
        socket.username = user;
        //Memorizzo l'utente
        users[user] = user;
    });

    /**
     * emits 'dettagli'
     * 
     * Restituisce i dettagli di un'azienda
     * @var id integer id dell'azienda
     */
    socket.on('dettagli', function(id) {
        // memorizzo la stanza di default nella sessione del socket associata al client
        dettagli(socket, id, function(row) {
            socket.emit('aggiorna_detail', row);
        });
    });

    
    /**
     * emits 'dettagli'
     * 
     * Restituisce i dettagli di un'azienda
     * @var id integer id dell'azienda
     */
    socket.on('ultime_localita', function(n) {
        // memorizzo la stanza di default nella sessione del socket associata al client
        socket.emit('aggiorna_localita', localita.last(n));
    });
    
    /**
     * emits 'dettagli'
     * 
     * Restituisce i dettagli di un'azienda
     * @var id integer id dell'azienda
     */
    socket.on('utenti_online', function(n) {
        // memorizzo la stanza di default nella sessione del socket associata al client
        var c = new Date('2013-05-24');
        var d = new Date();
        var n = 0;
        var m = 1;
        var g = 1 + Math.min(Math.log(Math.sqrt(c.daysAgo(d) + 1))/Math.log(10), 3);
        switch(d.getDay()) {
            case 0:
            case 7:
                n = 1 + Math.random() * 2;
                break;
            case 5:
                if(d.getHours() < 13) {
                    n = 8 + Math.random() * 2;
                } else {
                    n = 5 + Math.random() * 3;
                }
                break;
            case 6:
                if(d.getHours() < 13) {
                    n = 3 + Math.random() * 2;
                } else {
                    n = 1 + Math.random() * 2;
                }
                break;
            default:
                n = 8 + Math.random() * 3;
                break;
        }
        switch(d.getHours()) {
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
                m = 0;
            case 0:
            case 6:
            case 23:
                m = 0.25;
                break;
                break;
                m = 0.25;
                break;
            case 7:
            case 20:
            case 21:
            case 22:
                m = 0.5;
                break;
            case 8:
            case 13:
            case 14:
            case 19:
                m = 0.75;
                break;
            case 9:
            case 15:
            case 18:
                m = 1;
                break;
            case 10:
            case 11:
            case 12:
            case 16:
            case 17:
                m = 1.25;
                break;
            default:
                m = 1;
                break;
        }
        socket.emit('aggiorna_utenti', Object.keys(users).length + Math.round(n * m * g));
    });

    /**
     * emits 'disconnect'
     * 
     * Disconnessione dell'utene
     */
    socket.on('disconnect', function() {
        // remuovo l'utente dalla chat
        delete users[socket.username];
    });
});

var cerca = function(socket, callback) {
    // memorizzo la chiave di ricerca
    console.info('CERCA');
    if (!socket.username) {
        socket.emit('get_user');
    }
    if (!socket.geo) {
        socket.emit('reset_map');
        return false;
    }
    // memorizzo il raggio
    if (!socket.dist) {
        socket.emit('reset_map');
        return false;
    }
    // memorizzo la chiave di ricerca
    if (!socket.key) {
        socket.emit('reset_map');
        return false;
    }
    // memorizzo la ricerca tipo
    if (!socket.tipo) {
        socket.emit('reset_map');
        return false;
    }
    db_pool.getConnection(function(error, connection) {
        if (error) {
            return console.log("cerca: Connection error: "+error);
        }
        var left_join = '';
        var where_key = '';
        var where_tipo = '';

        // Filtro tipologie
        if (socket.tipo.length > 0) {
            where_tipo += '    AND (';
            socket.tipo.forEach(function(t) {
                where_tipo += (where_tipo === '    AND (' ? ' ' : ' OR ') + 's.' + t + ' = ' + connection.escape(1);
            });
            where_tipo += ')';
        }
        
        // Filtro e calcolo distanze
        var _km_lat = 1 / terra;
        var _km_lon = _km_lat / Math.cos(socket.geo.latr);
        var km_lat = socket.dist * _km_lat;
        var km_lon = socket.dist * _km_lon;
        var where_geo = 's.latitudinerad BETWEEN ' + connection.escape(socket.geo.latr - km_lat) + ' AND ' + connection.escape(socket.geo.latr + km_lat) +
                '    AND s.longitudinerad BETWEEN ' + connection.escape(socket.geo.lonr - km_lon) + ' AND ' + connection.escape(socket.geo.lonr + km_lon);
        var having_geo = ' distanza <= ' + connection.escape(socket.dist);

        if (socket.key.length > 0) {
            left_join = '   LEFT JOIN op_showroom_tags t ON t.showroom_id = s.id ';
            where_key = ' AND t.tag_id IN (';
            socket.key.forEach(function(t) {
                where_key += connection.escape(t) + ', ';
            });
            where_key += '0)';
        }

        var query = ' SELECT DISTINCT(s.id), ' +
                '        s.latitudine, ' +
                '        s.longitudine, ' +
                '        s.attivita_principale, ' +
                '        ( ( ACOS( ( SIN(' + connection.escape(socket.geo.latr) + ') * SIN(s.latitudinerad) ) + ( COS(' + connection.escape(socket.geo.latr) + ') * COS(s.latitudinerad) * COS(' + connection.escape(socket.geo.lonr) + ' - s.longitudinerad) ) )         ) * ' + connection.escape(terra) + ' ) as distanza ' +
                '   FROM op_showroom s ' +
                left_join +
//                '  WHERE (s.user_id IS NOT NULL OR s.has_cer = 1)' +
                "  WHERE (s.user_id IS NOT NULL OR ((s.has_cer = 1 OR s.has_cer_trattati = 1) AND s.attivita_principale != 'anga'))" +
                '    AND ' + where_geo +
                where_tipo +
                where_key +
                ' HAVING ' + having_geo;
        connection.query(query, function(err, rows) {
            if (err) {
                console.log(query);
                return console.log("cerca: " + err);
            }
            connection.release();
            callback(rows);
        });
    });

};

var dettagli = function(socket, id, callback) {
    db_pool.getConnection(function(error, connection) {
        if (error) {
            return console.log("dettagli: Connection error");
        }

        var query = 
                ' SELECT s.id, ' +
                '        s.ragione_sociale, ' +
                '        s.attivita_principale, ' +
                '        s.descrizione_attivita, ' +
                '        s.impianto, ' +
                '        s.discarica, ' +
                '        s.raccoglitore, ' +
                '        s.trasportatore, ' +
                '        s.servizi, ' +
                '        s.laboratorio, ' +
                '        s.demolizioni, ' +
                '        s.spurghi, ' +
                '        s.bonifiche, ' +
                '        s.rottamazione, ' +
                '        s.raee, ' +
                '        s.olio_minerale, ' +
                '        s.olio_vegetale, ' +
                '        s.comune_testuale, ' +
                '        s.slug, ' +
                '        s.indirizzo, ' +
                '        (SELECT COUNT(*) FROM op_showroom_cer sc WHERE sc.showroom_id = s.id) as cer, ' +
                '        (SELECT COUNT(*) FROM op_showroom_cer_cp sc WHERE sc.showroom_id = s.id) as cer_cp, ' +
                '        (SELECT COUNT(*) FROM op_showroom_cer_trattati sc WHERE sc.showroom_id = s.id) as cer_trattati, ' +
                '        (SELECT COUNT(*) FROM op_showroom_tipologie st WHERE st.showroom_id = s.id) as tipologie, ' +
                '        (SELECT COUNT(*) FROM op_showroom_mps sm WHERE sm.showroom_id = s.id) as mps, ' +
                '        (SELECT COUNT(*) FROM op_showroom_servizi sm WHERE sm.showroom_id = s.id) as servizi_sr, ' +
                "        s.logo, " +
                '        IF(s.telefono IS NULL, 0, 1) as telefono, ' +
                '        IF(s.sito IS NULL, 0, 1) as sito, ' +
                '        ( ( ACOS( ( SIN(' + connection.escape(socket.geo.latr) + ') * SIN(s.latitudinerad) ) + ( COS(' + connection.escape(socket.geo.latr) + ') * COS(s.latitudinerad) * COS(' + connection.escape(socket.geo.lonr) + ' - s.longitudinerad) ) )         ) * ' + connection.escape(terra) + ' ) as distanza ' +
                '   FROM op_showroom s ' +
                '  WHERE s.id = ' + connection.escape(id);
        connection.query(query, function(err, rows) {
            if (err) {
                return console.log("dettagli: " + err);
            }
            connection.release();
            callback(rows.shift());
        });
    });

};

var nomeLocalita = function(lat, lon, callback, dist) {
    if(!dist) {
        dist = 0.025;
    }
    db_pool.getConnection(function(error, connection) {
        if (error) {
            return console.log("dettagli: Connection error");
        }
        var latr = deg2rad(lat);
        var query = 
" SELECT geo.asciiname as localita, geo.admin2_code as provincia, " +
"        (SELECT g2.asciiname FROM geo_names g2 WHERE g2.feature_code = 'ADM3' AND g2.country_code = geo.country_code AND g2.admin3_code = geo.admin3_code ) AS comune, " +
"        (DEGREES(ACOS((SIN(" + connection.escape(latr) + ") * SIN(RADIANS(geo.latitude))) + (COS(" + connection.escape(latr) + ") * COS(RADIANS(geo.latitude)) * COS(RADIANS(" + connection.escape(lon) + " - geo.longitude))))) * 111.18957696) as distanza " +
"  FROM geo_names geo " +
" WHERE geo.admin3_code != '' " +
"   AND geo.longitude BETWEEN (" + connection.escape(lon - dist) + ") AND (" + connection.escape(lon + dist) + ") " +
"   AND geo.latitude BETWEEN (" + connection.escape(lat - dist) + ") AND (" + connection.escape(lat + dist) + ") " +
" ORDER BY distanza ";
        connection.query(query, function(err, rows) {
            if (err) {
                return console.log("dettagli: " + err);
            }
            connection.release();
            if(rows.length === 0) {
                nomeLocalita(lat, lon, callback, dist*10);
            } else {
                callback(rows.shift());
            }
        });
    });

};

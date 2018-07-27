<?php

include 'cron.fx.php';
include 'cron.config.php';
$log = $cylex['log']['find'];

$d = new DateTime();

if (checkSemaforo($cylex['semafori']['find'], 3600)) {
    try {
        set_time_limit(3600);
        creaSemaforo($cylex['semafori']['find']);
        writeLog("INIZIO IMPORTAZIONE " . $d->format('d-m-Y H:i:s') . "\n", true);
        if (!checkError($log)) {
            writeError("LOG ERRORI\n", true);
        }
        if (!checkHistory($log)) {
            writeHistory("STORICO\n", true);
        }
        $ch = curl_init();
        $output = callCurl(generateUrl($prod, $cylex['prod']['find'], array('n' => $cylex['params']['numero_find'])));
        curl_close($ch);
        writeHistory($output);
        cancellaSemaforo($gm['semafori']['grab']);
    } catch (\Exception $e) {
        writeError($e->getMessage());
        writeLog("FINE INDICIZZAZIONE CON ERRORE");
        cancellaSemaforo($cylex['semafori']['find']);
    }
}


if (isset($_REQUEST['index'])) {
    include 'index.php';
}

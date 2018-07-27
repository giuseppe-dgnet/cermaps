<?php

include 'cron.fx.php';
include 'cron.config.php';
$log = $sr_tag['log']['firts'];

$d = new DateTime();
if (checkSemaforo($sr_tag['semafori']['firts'])) {
    try {
        creaSemaforo($sr_tag['semafori']['firts']);

        writeLog("INIZIO IMPORTAZIONE " . $d->format('d-m-Y H:i:s') . "\n", true);
        if (!checkError($log)) {
            writeError("LOG ERRORI\n", true);
        }
        if (!checkHistory($log)) {
            writeHistory("STORICO\n", true);
        }
        $ch = curl_init();
        $output = callCurl(generateUrl($prod, $sr_tag['prod']['firts'], array()));
        curl_close($ch);

        writeHistory(json_encode($output));
        cancellaSemaforo($sr_tag['semafori']['firts']);
    } catch (\Exception $e) {
        writeError($e->getMessage());
        writeLog("FINE INDICIZZAZIONE CON ERRORE");
        cancellaSemaforo($sr_tag['semafori']['firts']);
    }
}

if (isset($_REQUEST['index'])) {
    include 'index.php';
}

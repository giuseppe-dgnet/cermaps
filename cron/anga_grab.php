<?php

include 'cron.fx.php';
include 'cron.config.php';
$log = $cm['log']['grab'];

$d = new DateTime();

if (checkSemaforo($cm['semafori']['grab'], 3600)) {
    try {
        set_time_limit(3600);
        creaSemaforo($cm['semafori']['grab']);
        writeLog("INIZIO IMPORTAZIONE " . $d->format('d-m-Y H:i:s') . "\n", true);
        if (!checkError($log)) {
            writeError("LOG ERRORI\n", true);
        }
        if (!checkHistory($log)) {
            writeHistory("STORICO\n", true);
        }
        $ch = curl_init();
        $output = callCurl(generateUrl($prod, $cm['prod']['grab'], array('n' => $cm['params']['numero_grab'])));
        curl_close($ch);

        writeHistory($output);
        cancellaSemaforo($cm['semafori']['grab']);
    } catch (\Exception $e) {
        writeError($e->getMessage());
        writeLog("FINE INDICIZZAZIONE CON ERRORE");
        cancellaSemaforo($cm['semafori']['grab']);
    }
}
if (isset($_REQUEST['index'])) {
    include 'index.php';
}

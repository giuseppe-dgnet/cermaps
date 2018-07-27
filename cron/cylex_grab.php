<?php

include 'cron.fx.php';
include 'cron.config.php';
$log = $cylex['log']['grab'];

$d = new DateTime();

if (checkSemaforo($cylex['semafori']['grab'], 3600)) {
    try {
        set_time_limit(3600);
        creaSemaforo($cylex['semafori']['grab']);
        writeLog("INIZIO IMPORTAZIONE " . $d->format('d-m-Y H:i:s') . "\n", true);
        if (!checkError($log)) {
            writeError("LOG ERRORI\n", true);
        }
        if (!checkHistory($log)) {
            writeHistory("STORICO\n", true);
        }
        $ch = curl_init();
        $output = callCurl(generateUrl($prod, $cylex['prod']['grab'], array('n' => $cylex['params']['numero_grab'])));
        curl_close($ch);

        writeHistory($output);
        cancellaSemaforo($cylex['semafori']['grab']);
    } catch (\Exception $e) {
        writeError($e->getMessage());
        writeLog("FINE INDICIZZAZIONE CON ERRORE");
        cancellaSemaforo($gm['semafori']['grab']);
    }
}

if (isset($_REQUEST['index'])) {
    include 'index.php';
}

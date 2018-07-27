<?php

include 'cron.fx.php';
include 'cron.config.php';
$log = $anga['log']['next'];

$d = new DateTime();
if (checkSemaforo($anga['semafori']['next'])) {
    try {
        creaSemaforo($anga['semafori']['next']);

        writeLog("INIZIO IMPORTAZIONE " . $d->format('d-m-Y H:i:s') . "\n", true);
        if (!checkError($log)) {
            writeError("LOG ERRORI\n", true);
        }
        if (!checkHistory($log)) {
            writeHistory("STORICO\n", true);
        }
        $ch = curl_init();
        $output = callCurl(generateUrl($prod, $anga['prod']['next'], array()));
        curl_close($ch);

        writeHistory(json_encode($output));
        cancellaSemaforo($anga['semafori']['next']);
    } catch (\Exception $e) {
        writeError($e->getMessage());
        writeLog("FINE INDICIZZAZIONE CON ERRORE");
        cancellaSemaforo($anga['semafori']['next']);
    }
}

if (isset($_REQUEST['index'])) {
    include 'index.php';
}

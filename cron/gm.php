<?php

include 'cron.fx.php';
include 'cron.config.php';
$log = $gm['log']['grab'];

$d = new DateTime();

if (checkSemaforo($gm['semafori']['grab'], 3600)) {
    try {
        set_time_limit(3600*24*7);
        creaSemaforo($gm['semafori']['grab']);
        writeLog("INIZIO IMPORTAZIONE " . $d->format('d-m-Y H:i:s') . "\n", true);
        if (!checkError($log)) {
            writeError("LOG ERRORI\n", true);
        }
        if (!checkHistory($log)) {
            writeHistory("STORICO\n", true);
        }
        $ch = curl_init();
        $n = callCurl(generateUrl($prod, $gm['prod']['list'], array()));
        
        for($i = 0; $i < intval($n); $i++) {
            $output = callCurl(generateUrl($prod, $gm['prod']['grab'], array()));
        }
            
        curl_close($ch);

        writeHistory($output);
        cancellaSemaforo($gm['semafori']['grab']);
    } catch (\Exception $e) {
        writeError($e->getMessage());
        writeLog("FINE INDICIZZAZIONE CON ERRORE");
        cancellaSemaforo($gm['semafori']['grab']);
    }
}
if (isset($_REQUEST['index'])) {
    include 'index.php';
}

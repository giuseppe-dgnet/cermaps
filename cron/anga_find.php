<?php

include 'cron.fx.php';
include 'cron.config.php';
$log = $cm['log']['find'];

$d = new DateTime();
$history = array();
if (checkSemaforo($cm['semafori']['find'])) {
    try {
        creaSemaforo($cm['semafori']['find']);

        writeLog("INIZIO IMPORTAZIONE " . $d->format('d-m-Y H:i:s') . "\n", true);
        if (!checkError($log)) {
            writeError("LOG ERRORI\n", true);
        }
        if (!checkHistory($log)) {
            writeHistory("STORICO\n", true);
        }
        $ch = curl_init();
        $output = callCurl(generateUrl($prod, $cm['prod']['count'], array()));
        $aziende_regioni = json_decode($output);

        foreach ($aziende_regioni as $aziende_regione) {
            if (intval($aziende_regione->reg) >= 1) {
                for ($pagina = 1; $pagina <= $aziende_regione->max; $pagina++) {
                    set_time_limit(3600);
                    $output = callCurl(generateUrl($prod, $cm['prod']['find'], array('regione' => $aziende_regione->reg, 'pagina' => $pagina)), false, true);
                    $out = json_decode($output);
                    foreach ($out as $k => $v) {
                        if ($k != 'errors') {
                            if (!isset($history[$k])) {
                                $history[$k] = 0;
                            }
                            $history[$k] += $v;
                        }
                    }
                }
            }
        }

        curl_close($ch);

        writeHistory(json_encode($history));
        cancellaSemaforo($cm['semafori']['find']);
    } catch (\Exception $e) {
        writeError($e->getMessage());
        writeLog("FINE INDICIZZAZIONE CON ERRORE");
        cancellaSemaforo($cm['semafori']['find']);
    }
}

if (isset($_REQUEST['index'])) {
    include 'index.php';
}

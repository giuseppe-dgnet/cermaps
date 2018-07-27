<?php

include 'cron.fx.php';
include 'cron.config.php';
$log = $sitemap_csr['log']['sitemap_csr'];

$d = new DateTime();
if (checkSemaforo($sitemap_csr['semafori']['sitemap_csr'])) {
    try {
        creaSemaforo($sitemap_csr['semafori']['sitemap_csr']);

        writeLog("INIZIO CREAZIONE LOG " . $d->format('d-m-Y H:i:s') . "\n", true);
        if (!checkError($log)) {
            writeError("LOG ERRORI\n", true);
        }
        if (!checkHistory($log)) {
            writeHistory("STORICO\n", true);
        }

        $index = fopen(__DIR__.'/../web/sitemaps/csr/sitemap.index.xml', "w+");
        fwrite($index, '<sitemapindex xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');

        $ch = curl_init();
        $output = callCurl(generateUrl($csr, $sitemap_csr['csr']['first'], array()));
        $handle = fopen(__DIR__.'/../web/sitemaps/csr/sitemap.cer.xml', "w+");
        fwrite($handle, $output);
        fclose($handle);
        curl_close($ch);

        fwrite($index, '<sitemap><loc>'.$csr.'/sitemaps/csr/sitemap.cer.xml</loc><lastmod>2013-06-07T14:07:19+00:00</lastmod></sitemap>');
        
        for($i = 0; $i < 8094; $i++) {
            set_time_limit(600);

            $ch = curl_init();
            $output = callCurl(generateUrl($csr, $sitemap_csr['csr']['other'], array('n' => $i)));
            $handle = fopen(__DIR__.'/../web/sitemaps/csr/sitemap.comune_'.str_repeat('0', 4-strlen(''.$i)).$i.'.xml', "w+");
            fwrite($handle, $output);
            fclose($handle);
            curl_close($ch);

            fwrite($index, '<sitemap><loc>'.$csr.'/sitemaps/csr/sitemap.comune_'.str_repeat('0', 4-strlen(''.$i)).$i.'.xml</loc><lastmod>2013-06-07T14:07:19+00:00</lastmod></sitemap>');
        }
        fwrite($index, '</sitemapindex>');
        fclose($index);

        writeHistory(json_encode($output));
        cancellaSemaforo($sitemap_csr['semafori']['sitemap_csr']);
    } catch (\Exception $e) {
        writeError($e->getMessage());
        writeLog("FINE INDICIZZAZIONE CON ERRORE");
        cancellaSemaforo($sitemap_csr['semafori']['sitemap_csr']);
    }
}

if (isset($_REQUEST['index'])) {
    include 'index.php';
}

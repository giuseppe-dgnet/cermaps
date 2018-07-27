<?php
$cron = 'http://dev.cermaps.com';
$prod = 'http://dev.cermaps.com';
$csr  = 'http://www.centrosmaltimentorifiuti.it';

$sitemap_csr = array(
    'csr' => array(
        'first'                       => '/sitemap.coordinamento.xml',
        'other'                       => '/sitemap.coordinamento.xml?n={n}',
    ),
    
    'semafori' => array(
        'sitemap_csr' => 'sitemap_csr',
    ),
        
    'log' => array(
        'sitemap_csr' => 'sitemap_csr',
    ),
);

$sr_tag = array(
    'prod' => array(
        'firts'                       => '/cron/operatori/tag-first',
    ),
    
    'semafori' => array(
        'firts' => 'sr_tag_first',
    ),
        
    'log' => array(
        'firts' => 'sr_tag_first',
    ),
);

$anga = array(
    'prod' => array(
        'next'                       => '/cron/operatori/next',
    ),
    
    'semafori' => array(
        'next' => 'anga_next',
    ),
        
    'log' => array(
        'next' => 'anga_next',
    ),
);


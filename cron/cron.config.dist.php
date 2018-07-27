<?php
$cron = 'http://www.bringout.cron';
$prod = 'http://www.bringout.cron';

$cylex = array(
    'prod' => array(
        'find'                       => '/cron/grab/cylex/find/{n}',
        'grab'                       => '/cron/grab/cylex/grab/{n}',
    ),
    
    'params' => array(
        'numero_find'            => 100,
        'numero_grab'            => 20,
    ),
    
    'semafori' => array(
        'find' => 'cylex_find',
        'grab' => 'cylex_grab',
    ),
        
    'log' => array(
        'find' => 'cylex_find',
        'grab' => 'cylex_grab',
    ),
);

$cm = array(
    'prod' => array(
        'count'                      => '/cron/grab/anga/aziende',
        'find'                       => '/cron/grab/anga/aziende/{regione}/{pagina}',
        'grab'                       => '/cron/grab/anga/azienda/{n}',
    ),
    
    'params' => array(
        'numero_grab'            => 1000,
    ),
    
    'semafori' => array(
        'find' => 'anga_find',
        'grab' => 'anga_grab',
    ),
        
    'log' => array(
        'find' => 'anga_find',
        'grab' => 'anga_grab',
    ),
);

$gm = array(
    'prod' => array(
        'list'                       => '/cron/grab/gm/list2012',
        'grab'                       => '/cron/grab/gm/import2012',
    ),
    
    'semafori' => array(
        'grab' => 'gm_grab',
    ),
        
    'log' => array(
        'grab' => 'gm_grab',
    ),
);

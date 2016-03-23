<?php
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/3/23
 * Time: 上午12:21
 */
PtPHP\Logger::init(array(
    'level' => 'INFO', // none/off|(LEVEL)
    'files' => array( // ALL|(LEVEL)
        'ALL'	=> PATH_PRO.'/logs/'.date("Y-m-d").'.log',
    ),
));
<?php
global $config;

date_default_timezone_set('Europe/Paris');

// MYSQL connector
// APPS
$config['db']['apps']['db_ip'] = '127.0.0.1';
$config['db']['apps']['username'] = 'dev';
$config['db']['apps']['password'] = 'Hptb6SuBt4E6HPUz';
$config['db']['apps']['db_name'] = 'test';
$config['db']['apps-ro']['db_ip'] = '127.0.0.1';
$config['db']['apps-ro']['username'] = 'dev';
$config['db']['apps-ro']['password'] = 'Hptb6SuBt4E6HPUz';
$config['db']['apps-ro']['db_name'] = 'test';
$config['db']['apps-rw']['db_ip'] = '127.0.0.1';
$config['db']['apps-rw']['username'] = 'dev';
$config['db']['apps-rw']['password'] = 'Hptb6SuBt4E6HPUz';
$config['db']['apps-rw']['db_name'] = 'test';

define('FIELDTYPE_NAMING_OBJECT', 0);
define('FIELDTYPE_NAMING_SQL', 1);
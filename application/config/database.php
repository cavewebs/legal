<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
	'dsn'	=> '',
	// 'hostname' => '34.73.198.36',
	// 'username' => 'efaboss',
	// 'password' => 'ttsmuj',
	// 'database' => 'efaboss',
	// 'hostname' => 'localhost',
	// 'username' => 'botguy_bota',
	// 'password' => '8888@@@@Bota',
	// 'database' => 'botguy_main',
	// 'hostname' => '66.85.47.62',
	// 'username' => 'legalshop_main',
	// 'password' => 'Main@l3gal',
	// 'database' => 'legalshop_main',
	'hostname' => 'localhost',
	'username' => 'root',
	'password' => '',
	'database' => 'legitshop',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

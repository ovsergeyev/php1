<?php
define('SITE_ROOT', "../");
define('WWW_ROOT', SITE_ROOT . '/public');

/* DB config */
define('HOST', 'localhost:3306');
define('USER', 'root');
define('PASS', 'pass@word1');
define('DB', 'trialdb');

define('DATA_DIR', SITE_ROOT . '/data');
define('LIB_DIR', SITE_ROOT . '/engine');
define('TPL_DIR', SITE_ROOT . '/templates');
define('GOODS_DIR', SITE_ROOT . 'img/goods_img');

define('SITE_TITLE', 'Урок 4');

require_once(LIB_DIR . '/functions.php');
require_once(LIB_DIR . '/db.php');
?>

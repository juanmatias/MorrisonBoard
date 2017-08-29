<?php
/**
 * Config file | config/config.php
 *
 * @package RESTful WebService Model
 * @author Juan Matias de la Camara Beovide <juanmatias@gmail.com>
 *
 */

define('ROOT_DIR',getcwd().'/');
define('DB_HOST','localhost');
define('DB_NAME','database');
define('DB_USR','database');
define('DB_PWD','database');

// set autoload for classes (and its order)
$classesDir = array (
    ROOT_DIR.'core/',
    ROOT_DIR.'vendor/',
);





?>

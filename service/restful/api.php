<?php
/**
 * Main file | /api.php
 *
 * @package RESTful WebService Model
 * @author Juan Matias de la Camara Beovide <juanmatias@gmail.com>
 *
 */

 /**
  * Main code
  *
  * This code is called when a request arrives.
  * Be sure of modify rewrites in .htaccess file as follows.
  *<IfModule mod_rewrite.c>
  *  RewriteEngine On
  *  RewriteCond %{REQUEST_FILENAME} !-f
  *  RewriteCond %{REQUEST_FILENAME} !-d
  *  RewriteRule ^(.*)$ ./api.php?request=$1 [QSA,NC,L]
  *</IfModule>
  *
  *
  */

include('./config/config.php');

// Requests from the same server don't have a HTTP_ORIGIN header
if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
    $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
}

try {
    $API = new securecall($_REQUEST['request'], $_SERVER['HTTP_ORIGIN']);
    echo $API->processAPI();
} catch (Exception $e) {
    echo json_encode(Array('error' => $e->getMessage()));
}
?>

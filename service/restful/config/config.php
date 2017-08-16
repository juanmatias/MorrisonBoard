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
define('DB_NAME','pentatistics');
define('DB_USR','pentatistics');
define('DB_PWD','$penta.290%');

$errors = array(
  1 => 'Invalid parameters',
  2 => 'Invalid token',
  3 => 'Unknown error',
  4 => 'Unknown sub action',
  5 => 'Only accepts GET requests',
  6 => 'No verb supplied',
);

// set autoload for classes (and its order)
$classesDir = array (
    ROOT_DIR.'core/',
    ROOT_DIR.'vendor/vendorname/',
    ROOT_DIR.'core/Modules/',
);

/**
 * Look for a class file in given directories
 *
 * @property string $class_name Class name with full namespace
 *
 */
spl_autoload_register(function ($class_name)
{
  global $classesDir;

  $class_name = ltrim($class_name, '\\');
  $classPath  = '';
  $namespace = '';
  if ($lastNsPos = strrpos($class_name, '\\')) {
      $namespace = substr($class_name, 0, $lastNsPos);
      $class_name = substr($class_name, $lastNsPos + 1);
      $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $namespace).DIRECTORY_SEPARATOR;
  }
  $fileName = $classPath.$class_name.'.php';

  foreach ($classesDir as $directory) {
      if (file_exists($directory . $fileName)) {
          require_once ($directory . $fileName);
          return;
      }
  }

});



?>

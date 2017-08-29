<?php
/**
 * Autoloader file | core/autoloader/autoloader.php
 *
 * @package RESTful WebService Model
 * @author Juan Matias de la Camara Beovide <juanmatias@gmail.com>
 *
 */
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

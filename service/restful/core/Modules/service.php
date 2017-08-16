<?php
/**
  * Abstract Class service | vendor/Modules/service.php
  *
  * @package RESTful WebService Model
  * @author Juan Matias de la Camara Beovide <juanmatias@gmail.com>
  *
*/

namespace Modules;

/**
  * Abstract Class service - Abstract service
  *
  * This class is intended to abstract service.
  * It expose available services (methods) as valid actions
  *
  * Actions (methods exposed to be called) must receive two arrays: arguments and request (with all its values)
  *
  * @property array $valid_actions Set of valid actions object supports
  *
*/
abstract class service
{

  protected $valid_actions = array();
  protected $errors = array();

  function __construct()
  {
    global $errors;
    $this->errors = $errors;

  }
  /**
  * Expose valid actions for this object
  * @return array of valid actions
  */
  public function valid_actions()
  {
    return $this->valid_actions;
  }

  /**
  * Add a set of valid actions to existing array
  * @param array $myactions Array with strings of valid action names
  */
  protected function add_actions(array $myactions = array())
  {
    $this->valid_actions = array_merge($this->valid_actions, $myactions);
  }
}
?>

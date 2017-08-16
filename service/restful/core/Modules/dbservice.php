<?php
/**
 * Class dbservice | vendor/Modules/dbservice.php
 *
 * @package RESTful WebService Model
 * @author Juan Matias de la Camara Beovide <juanmatias@gmail.com>
 *
 */

 namespace Modules;

 /**
  * Class dbservice - Service to query database
  *
  * This class is intended to abstract service to query database.
  * It requires a DB_connect to do the queries
  *
  * @property DB_connect $db Instance of database connection
  * @property DB_results $r Results of database query
  *
  */

abstract class dbservice extends service
{

  protected $db = null;
  protected $r = null;

  /**
  * Creates a DB_connect instance for the object
  */
  function __construct()
  {
    parent::__construct();
    $this->db = \DB_connect::getInstance();
  }

  /**
  * Execute query and if needed bind params
  */
  protected function exec_query($sql, $query_params)
  {
    $this->db->queryPrep($sql);
    if(sizeof($query_params) > 0)
    {
      $this->db->bindParams($query_params);
    }
    if(!$this->r = $this->db->queryExe())
    {
      return false;
    }else
    {
      $this->r = $this->db->getResults();
      return true;
    }
  }

  /**
  * Abstraction to fetch_assoc call
  */
  protected function fetch_assoc()
  {
    return $this->r->fetch_assoc();
  }
}
?>

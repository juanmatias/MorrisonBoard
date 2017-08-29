<?php
/**
 * Class APIKey | vendorname/APIKey.php
 *
 * @package RESTful WebService Model
 * @author Juan Matias de la Camara Beovide <juanmatias@gmail.com>
 *
 */

 /**
  * Class APIKey - Security for Bee Rest
  *
  * This class check APIKey and origin of the call
  *
  *
  */

class APIKey
{
  protected $db = null;

  /**
  * Creates a DB_connect instance for the object
  */
  function __construct()
  {
    $this->db = \DB_connect::getInstance();
  }
  /**
   * Check APIKey and origin
   */
  public function verifyKey($apiKey, $origin) {
    //Do whatever you need to check them both
    $this->db->queryPrep("SELECT apiKey, origin FROM pm_apikeys WHERE apiKey = ?");
    $this->db->bindParams(array(array('s',$apiKey)));
    if(!$r = $this->db->queryExe())
    {
     return false;
    }
    else
    {
     $r = $this->db->getResults();
     if($r->num_rows > 0)
     {
       $row = $r->fetch_assoc();
       if($row['origin'] == '*' || $row['origin'] == $origin)
       {
         return true;
       }
     }
     return false;

    }
  }

 }
 ?>

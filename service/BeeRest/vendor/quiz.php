<?php
/**
 * Class securecall | lib/securecall.php
 *
 * @package RESTful WebService Model
 * @author Juan Matias de la Camara Beovide <juanmatias@gmail.com>
 *
 */

 /**
  * Class securecall - Secyure implementation of API class for get info from modules
  *
  * This class gets information from modules in vendor/samplevendor/Modules and handles security
  *
  * In this Implementation :
  *
  *       endpoint = sourcecall method
  *       verb = module name
  *       arg0 = module activity
  *
  */

class quiz extends securecall
{
    /**
    * class constructor, verifies pair (apikey,origin) to allow access
    * @param request $request The request
    * @param string $origin The request origin
    */
    public function __construct($request, $origin) {
        parent::__construct($request, $origin);

    }

    /**
     *
     */
     public function retrieve() {
       $r = $this->get_service();

       return $r;
     }
     /**
      *
      */
      public function save() {
        $r = $this->post_service();

        return $r;
      }

      public function choose() {
        $r = $this->post_service();

        return $r;
      }
 }
 ?>

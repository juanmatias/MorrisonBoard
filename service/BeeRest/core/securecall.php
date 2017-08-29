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

class securecall extends API
{
    /**
    * class constructor, verifies pair (apikey,origin) to allow access
    * @param request $request The request
    * @param string $origin The request origin
    */
    public function __construct($request, $origin) {
        parent::__construct($request);

        // Add security and ACL to verb/actions

        // Abstracted out for example
        $APIKey = new APIKey();
        // $User = new Models\User();
        //
        if (!array_key_exists('apiKey', $this->request)) {
            throw new Exception('No API Key provided');
        } else if (!$APIKey->verifyKey($this->request['apiKey'], $origin)) {
            throw new Exception('Invalid API Key');
        }
        //

    }

    /**
     * get_service endpoint that accept only GET requests and calls to modules if they exist
     */
     protected function get_service() {
        if ($this->method == 'GET') {
          return $this->processRequest();
        } else {
          return array('code' => 5, 'error' => $this->errors[5]);
        }
     }

     /**
      * post_service endpoint that accept only POST requests and calls to modules if they exist
      */
      protected function post_service() {
         if ($this->method == 'POST') {
           return $this->processRequest();
         } else {
           return array('code' => 8, 'error' => $this->errors[8]);
         }
      }
 }
 ?>

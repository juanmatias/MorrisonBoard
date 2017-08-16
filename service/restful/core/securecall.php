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
        // $APIKey = new APIKey();
        // $User = new Models\User();
        //
        // if (!array_key_exists('apiKey', $this->request)) {
        //     throw new Exception('No API Key provided');
        // } else if (!$APIKey->verifyKey($this->request['apiKey'], $origin)) {
        //     throw new Exception('Invalid API Key');
        // } else if (array_key_exists('token', $this->request) &&
        //      !$User->get('token', $this->request['token'])) {
        //
        //     throw new Exception('Invalid User Token');
        // }
        //

    }

    /**
     * board endpoint handles all requests and calls to modules if they exist
     */
     protected function board() {
        if ($this->method == 'GET') {
            try
            {
              // In the verb I have the module name, i.e. "getdestinos"
              if(!isset($this->verb) || $this->verb == '')
              {
                return array('code' => 6, 'error' => $this->errors[6]);
              }
              $service = "\\Modules\\".$this->verb;
              $service = new $service();
              // First argument is the action to call, so let's check if it exists
              $action = '';
              if(isset($this->args[0]))
              {
                $action = array_shift($this->args);
              }
              if(in_array($action,$service->valid_actions())){
                  $r = $service->$action($this->args,$this->request);

                  return array('code' => 0, 'response'=> $r);

              }else{

                return array('code' => 4, 'error' => $this->errors[4]);
              }
            } catch (Exception $e) {

              return array('code' => 3, 'error' => $this->errors[3]);
            }
        } else {
            return array('code' => 5, 'error' => $this->errors[5]);
        }
     }
 }
 ?>

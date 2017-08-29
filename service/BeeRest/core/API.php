<?php
/**
 * Abstract Class API | lib/API.php
 *
 * @package RESTful WebService Model
 * @author Juan Matias de la Camara Beovide <juanmatias@gmail.com>
 *
 */

 /**
  * Abstract Class API - Abstract class for API handlers
  *
  * This class is intended to abstract the common API handlers functions.
  * It handles requests in the form /<endpoint>/<verb>/<arg0>/<arg1>
  * or /<endpoint>/<arg0>... API handles these argunments as well arguments like "?arg=value"
  *
  * For example in this request:
  *   http://localhost/tablero/board/getdestinos/activity/23?caratula=1
  *
  *       endpoint = tablero
  *       verb = getdestinos
  *       arg0 = activity
  *       arg1 = 23
  *       caratula will be in the $this->request array
  *
  *
  * Code based on http://coreymaynard.com/blog/creating-a-restful-api-with-php/
  *
  *
  * @property string $method The HTTP method this request was made in, either GET, POST, PUT or DELETE
  * @property string $endpoint The Model requested in the URI. eg: /files
  * @property string $verb An optional additional descriptor about the endpoint, used for things that can not be handled by the basic methods. eg: /files/process
  * @property array $args Any additional URI components after the endpoint and verb have been removed, in our case, an integer ID for the resource. eg: /<endpoint>/<verb>/<arg0>/<arg1> or /<endpoint>/<arg0>
  * @property string $file
  *
  */

abstract class API
{
  protected $method = '';
  protected $endpoint = '';
  protected $verb = '';
  protected $args = Array();
  protected $file = Null;
  protected $errors = array();

  protected $version = "0.0";

  /**
   * Constructor: __construct
   * Allow for CORS, assemble and pre-process the data
   */
  public function __construct($request)
  {
      header("Access-Control-Allow-Orgin: *");
      header("Access-Control-Allow-Methods: *");
      header("Content-Type: application/json");

      global $errors;
      $this->errors = $errors;

      $this->args = explode('/', rtrim($request, '/'));
      $this->endpoint = array_shift($this->args);
      if (array_key_exists(0, $this->args) && !is_numeric($this->args[0])) {
          $this->verb = array_shift($this->args);
      }

      $this->method = $_SERVER['REQUEST_METHOD'];
      if ($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
          if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
              $this->method = 'DELETE';
          } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
              $this->method = 'PUT';
          } else {
              throw new Exception("Unexpected Header");
          }
      }

      switch($this->method) {
      case 'DELETE':
      case 'POST':
          $this->request = $this->_cleanInputs($_POST);
          break;
      case 'GET':
          $this->request = $this->_cleanInputs($_GET);
          break;
      case 'PUT':
          $this->request = $this->_cleanInputs($_GET);
          $this->file = file_get_contents("php://input");
          break;
      default:
          $this->_response('Invalid Method', 405);
          break;
      }
  }

  /**
  * Process the request and get the proper endpoint
  */
  public function processAPI()
  {
     if (method_exists($this, $this->endpoint)) {
         return $this->_response($this->{$this->endpoint}($this->args));
     }
     return $this->_response("No Endpoint: $this->endpoint", 404);
   }
   /**
   * Generates the response
   */
   private function _response($data, $status = 200)
   {
       header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
       return json_encode($data);
   }
   /**
   * Cleans the inputs
   */
   private function _cleanInputs($data)
   {
       $clean_input = Array();
       if (is_array($data)) {
           foreach ($data as $k => $v) {
               $clean_input[$k] = $this->_cleanInputs($v);
           }
       } else {
           $clean_input = trim(strip_tags($data));
       }
       return $clean_input;
   }
   /**
   * Requires and checks status code
   */
   private function _requestStatus($code)
   {
       $status = array(
           200 => 'OK',
           404 => 'Not Found',
           405 => 'Method Not Allowed',
           500 => 'Internal Server Error',
       );
       return ($status[$code])?$status[$code]:$status[500];
   }

   /**
   * General process for requests
   */
   protected function processRequest() {
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
   }

   /**
   * Returns version number
   */
   public function version()
   {
     return $this->version;
   }
}
?>

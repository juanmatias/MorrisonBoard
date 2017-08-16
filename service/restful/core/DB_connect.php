<?php
/**
 * Class DB_connect | lib/DB_connect.php
 *
 * @package RESTful WebService Model
 * @author Juan Matias de la Camara Beovide <juanmatias@gmail.com>
 *
 */

 /**
  * Class DB_connect - DB Connector - MySQL
  *
  * This class is intended to abstract database connection.
  * This version connects to MySQL through mysqli.
  *
  * @property DB_connect $_instance Instance for singleton pattern.
  * @property mysqli $datab Database connection object
  * @property mysqli_statement $prepared Prepared query
  * @property mysqli_resultset $results Resultset from the prepared query execution
  *
  */
class DB_connect {
    public $datab;
    public $prepared;
    public $results;
    private static $_instance;

    /**
    * Constructor
    * @param string $host Hostname
    * @param string $user Username
    * @param string $pass Password
    * @param string $db DB Name
    */
    public function __construct($host, $user, $pass, $db)
    {
        $this->datab=new mysqli($host, $user, $pass, $db);
           if ($this->datab->connect_error){
             die('connect_error : '.$this->datab->connect_error." Error Number : ".$this->datab->connect_errno);
           }
      }

    /**
    * Get an instance of DB_connect object using singleton
    * @return mysqli_instance
    */
    public static function getInstance()
    {
  		if(is_null(self::$_instance)){
  			self::$_instance = new self(DB_HOST,DB_USR,DB_PWD,DB_NAME);
  		}
  		return self::$_instance;
  	}

    /**
    * Get a single row
    * @return mysqli_executed_query
    */
    public function getRow($tableName, $condition = '')
    {
      if($condition != ''){
        $condition = ' WHERE '.$condition;
      }
      $this->prepareQuery('SELECT * FROM '.$tableName.' '.$condition);
      return $this->executeQuery();
    }

    /**
    * Get a rows counter
    * @return mysqli_executed_query
    */
    public function countRows($tableName, $condition = '')
    {
      if($condition != ''){
        $condition = ' WHERE '.$condition;
      }
      $this->prepareQuery('SELECT count(*) as qty FROM '.$tableName.' '.$condition);
      return $this->executeQuery();
    }

    /**
    * Prepare the query
    */
    public function queryPrep($sql)
    {
      $this->prepareQuery($sql);
    }

    /**
    * Executes the prepared query
    * @return mysqli_executed_query
    */
    public function queryExe()
    {
      return $this->executeQuery();
    }

    /**
    * Close DB connection
    */
    public function close(){
      $this->datab->close();
    }

    /**
    * Bind parameters to query
    */
    public function bindParams(array $p = array())
    {
      $param_types = '';
      $params = array();
      $params_2_send = array();
      foreach ($p as $key => $value)
      {
        //first param is Types: s = string, i = integer, d = double, b = blob
        $param_types .= $value[0];
        $params[] = $value[1];
      }
      $params_2_send[] = & $param_types;
      for($f=0;$f<count($params);$f++)
      {
        $params_2_send[] = & $params[$f];
      }
      if($this->prepared!=false)
      {
        /* use call_user_func_array, as $stmt->bind_param('s', $param); does not accept params array */
        call_user_func_array(array($this->prepared, 'bind_param'), $params_2_send);

      }else {
        echo 'ERROR';
      }

    }

    /**
    * Get last inserted ID
    * @return int
    */
    public function getLastId()
    {
      return $this->datab->insert_id;
    }

    /**
    * Get the results
    * @return mysqli_resultset
    */
    public function getResults(){
      return $this->prepared->get_result();
    }

    /**
    * Private prepare the query
    * @param string $sql The query
    */
    private function prepareQuery($sql)
    {
      $this->prepared = $this->datab->prepare($sql);
    }

    /**
    * Private execute the query
    * @return mysqli_executed_query
    */
    private function executeQuery()
    {
      return $this->results = $this->prepared->execute();
    }

}

?>

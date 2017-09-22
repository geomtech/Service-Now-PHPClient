<?php
/* ServiceNow Client Class
 * author: Alexy DA CRUZ (GeomTech): dacruzalexy@gmail.com
*/

class ServiceNowClient
{
    /* Variables */
    private $instance = NULL;
    private $username = NULL;
    private $password = NULL;

    /* Constructor of the class
     * Required to get credentials to use authentification on Service-Now.
    */
    public function __construct($instance, $username, $password){
        $this->instance = $instance;
        $this->username = $username;
        $this->password = $password;
    }

    /* Test authentification on Service-Now by getting incident table with a limit of 10 incidents.
     * If return false, authentification failed.
    */
    public function Authenticated(){
        if(!$this->GETFromTable("incident", "", true)){
          echo "ServiceNow: Error Auth Login";
          return false;
        } else {
          return true;
        }
    }

    /* Get the table from Service-Now
     * $parameters: parameters from Service-Now like "?sysparm_limit=10", "?sysparm_query=number=INC000001" , etc.. (nothing by default)
     * $auth: Useful to do a query for testing authentification (false by default)
     *
     * Return false if authentification failed.
     * If authentification works, it return a json object.
    */
    public function GETFromTable($table, $parameters = "", $auth = false) {
      if(empty($table)){
        throw new ErrorException('$table is empty in the function \'GETFromTable().\'');
      }

      if($auth){
        $RESTReq = 'https://'.$this->username.':'.$this->password.'@'.$this->instance.'.service-now.com/api/now/table/'.$table.'?sysparm_limit=10';
      } else {
        if(!$parameters == ""){
          $RESTReq = 'https://'.$this->username.':'.$this->password.'@'.$this->instance.'.service-now.com/api/now/table/'.$table.'?'.$parameters;
        } else {
          $RESTReq = 'https://'.$this->username.':'.$this->password.'@'.$this->instance.'.service-now.com/api/now/table/'.$table;
        }
      }

      $get = @file_get_contents($RESTReq);
      if($get == '{"error":{"detail":"Required to provide Auth information","message":"User Not Authenticated"},"status":"failure"}'){
        return false;
      } else {
        $json = json_decode($get);
        return $json;
      }
    }
}

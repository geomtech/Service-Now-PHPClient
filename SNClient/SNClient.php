<?php
/* ServiceNow Client Class
 * Copyright (c) 2017 Alexy DA CRUZ <dacruzalexy@gmail.com>
 * All rights reserved.
 * Available on Github: https://github.com/geomtech/Service-Now-PHPClient
*/

class ServiceNowClient
{
    /* Variables */
    private $instance = NULL;
    private $username = NULL;
    private $password = NULL;
    private $proxy = NULL;

    /* Constructor of the class
     * Required to get credentials to use authentification on Service-Now.
     * Example: $SNClient = new ServiceNowClient();
    */
    public function __construct($instance, $username, $password, $proxy){
        $this->instance = $instance;
        $this->username = $username;
        $this->password = $password;
        $this->proxy = $proxy;
    }

    /* Test authentification on Service-Now by getting incident table with a limit of 1 incident.
     * If return false, authentification failed.
    */
    public function Authenticated(){
      $RESTReq = 'https://'.$this->instance.'.service-now.com/api/now/table/incident?sysparm_limit=1';

      $ch = curl_init($RESTReq);
      curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
      if(!empty($this->proxy)){
        curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
      }
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Accept: application/json',
        'Content-Type: application/json',
      ));
      $get = curl_exec($ch);
      $ResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

      if($ResponseCode == 200){
        return true;
      } else {
        return false;
      }
    }

    /* Get the table from Service-Now
     * $table: Name of the table
     * $parameters: parameters from Service-Now like "?sysparm_limit=10", "?sysparm_query=number=INC000001" , etc.. (nothing by default)
     *
     * Return false if authentification failed.
     * If authentification works, it return a json object.
    */
    public function RetrieveAllRecords($table, $parameters = "") {
      if(!$parameters == ""){
        $RESTReq = 'https://'.$this->instance.'.service-now.com/api/now/table/'.$table.'?'.$parameters;
      } else {
        $RESTReq = 'https://'.$this->instance.'.service-now.com/api/now/table/'.$table;
      }

      $ch = curl_init($RESTReq);
      curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
      if(!empty($this->proxy)){
        curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
      }
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Accept: application/json',
        'Content-Type: application/json',
      ));

      $get = curl_exec($ch);

      $ResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

      if($ResponseCode == 200){
        $json = json_decode($get);
        return $json;
      } else {
        return false;
      }
    }

    /* Create a new record on Service-Now
     * $table: Name of the table
     * $data: Array with all fields you want to insert
     *
     * Return false if the request have an error
     * Return true if the request completed successfully
    */
    public function CreateRecord($table, $data)
    {
      $data_string = json_encode($data);
      $url = 'https://'.$this->instance.'.service-now.com/api/now/table/'.$table;

      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
      if(!empty($this->proxy)){
        curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
      }
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Accept: application/json',
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string))
      );

      $result = curl_exec($ch);
      $ResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

      if($ResponseCode == 201){
        return true;
      } else {
        return false;
      }
    }

    /* Get a record from a Table
     * $table: Name of the table
     * $ID: ID of the record (incident, task)
     *
     * Return false if authentification failed.
     * If authentification works, it return a json object.
    */
    public function RetrieveRecord($table, $ID) {
      $RESTReq = 'https://'.$this->instance.'.service-now.com/api/now/table/'.$table.'/'.$ID;

      if(empty($ID)){
        return false;
      }

      $ch = curl_init($RESTReq);
      curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
      if(!empty($this->proxy)){
        curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
      }
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Accept: application/json',
        'Content-Type: application/json',
      ));

      $get = curl_exec($ch);

      $ResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

      if($ResponseCode == 200){
        $json = json_decode($get);
        return $json;
      } else {
        return false;
      }
    }

    /* Modify record on Service-Now
     * $table: Name of the table
     * $sys_id: Sys_ID of the record to modify
     * $data: Array with all fields you want to insert
     *
     * Return false if the request have an error
     * Return true if the request completed successfully
    */
    public function ModifyRecord($table, $sys_id, $data)
    {
      $data_string = json_encode($data);
      $url = 'https://'.$this->instance.'.service-now.com/api/now/table/'.$table.'/'.$sys_id;

      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
      if(!empty($this->proxy)){
        curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
      }
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Accept: application/json',
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string))
      );

      $result = curl_exec($ch);
      $ResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

      if($ResponseCode == 201){
        return true;
      } else {
        return false;
      }
    }

    /* Update record on Service-Now
     * $sys_id: Sys_ID of the record to update
     * $table: Name of the table
     * $data: Array with all fields you want to insert
     *
     * Return false if the request have an error
     * Return true if the request completed successfully
    */
    public function UpdateRecord($table, $sys_id, $data)
    {
      $data_string = json_encode($data);
      $url = 'https://'.$this->instance.'.service-now.com/api/now/table/'.$table.'/'.$sys_id;

      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
      if(!empty($this->proxy)){
        curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
      }
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Accept: application/json',
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string))
      );

      $result = curl_exec($ch);
      $ResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

      if($ResponseCode == 200){
        return true;
      } else {
        return false;
      }
    }

    /* Delete record on Service-Now
     * $sys_id: Sys_ID of the record to delete
     * $table: Name of the table
     *
     * Return false if the request have an error
     * Return true if the request completed successfully
    */
    public function DeleteRecord($table, $sys_id)
    {
      $url = 'https://'.$this->instance.'.service-now.com/api/now/table/'.$table.'/'.$sys_id;

      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
      if(!empty($this->proxy)){
        curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
      }
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Accept: application/json'
      ));

      $result = curl_exec($ch);
      $ResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

      if($ResponseCode == 204){
        return true;
      } else {
        return false;
      }
    }
}

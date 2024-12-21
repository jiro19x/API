<?php
class CommonMeThods{

###### Getting-User-Name-Header ######                                
public function getHeaderValue($headerName) {
$headers = getallheaders(); // Fetch all headers
return isset($headers[$headerName]) ? $headers[$headerName] : null;
} 

######## LOGGER METHOD #########
protected function logger($user, $method, $action){//post , patch , delete
    //datetime, user, method, message, action -> text file .log
    $filename = date("Y-m-d") . ".log";//generate new file
    $datetime = date("Y-m-d H:i:s");
    $logMessage = "$datetime,$method,$user,$action" . PHP_EOL;//.PHP_EOL si to \n
  
    //saving data or rather put contents
    file_put_contents("./history-logs/$filename", $logMessage, FILE_APPEND | LOCK_EX);
    //error_log($logMessage, 3, "./history-logs/$filename");
    
}
########pulling records#########
protected function getDataByTable($tableName, $condition, \PDO $pdo){

        $data = array();
        $errmsg = "";
        $code = 0;

        // Prepare the SQL query
        $sqlString = "SELECT * FROM $tableName WHERE $condition";

        try{  
          if($result = $pdo->query($sqlString)->fetchAll()){ //fetch record from db
            // Check if records exist
              foreach($result as $record){
              array_push($data, $record);
                                         }
         $result = null;
         $code = 200;
         $message = "The requested records have been retrieved from the database. The operation has been completed without any issues.";
         return array("code"=>$code , "data"=>$data, "message"=>$message);
         
                                                                }
          
          else{ // if there is no record
              $errmsg = "No records matching the specified criteria were found.";
              $code = 404;
              }
  
           }
  
         catch(\PDOException $e){  // if there is a error
      $errmsg = $e ->GetMessage();
      $code = 403;
        
                               }
      return array("code"=>$code, "errmsg"=>$errmsg);
}

//sending response & payload
protected function sendResponse($data, $message, $remarks, $statusCode){
    $status = array(
      "remark" => $remarks,
      "message" => $message
  );
  
  http_response_code($statusCode);
  
  return array(
      "prepared_by" => "ADMIN JIRO",
      "date_generated" => date_create(),
      "status" => $status,
      "payload" => $data
    
  );
  }
  
//????????????
  protected function getDataBySQLString($sqlString, $condition, \PDO $pdo){

    $data = array();
    $errmsg = "";
    $code = 0;
  
    
    try{ // 
      if($result = $pdo->query($sqlString)->fetchAll()){ //fetch record from db
  
          foreach($result as $record){
          array_push($data, $record);
                                     }
  
     $result = null;
     $code = 200;
     return array("code"=>$code , "data"=>$data);
                                                            }
      
      else{ // if there is no record
          $errmsg = "No Data Found";
          $code = 404;
          }
  
       }
  
     catch(\PDOException $e){  // if there is a error
  $errmsg = $e ->GetMessage();
  $code = 403;
    
                           }
  return array("code"=>$code, "errmsg"=>$errmsg);
  }
########inserting records#########
private function generateInsertString($tablename, $body){
  $keys = array_keys($body);//capture all keys at body
  $fields = implode(",", $keys);//count by ","
  $parameter_array = [];//getting the values ????
  for($i = 0; $i < count($keys); $i++){
      $parameter_array[$i] = "?";
  }
  $parameters = implode(',', $parameter_array);
  $sql = "INSERT INTO $tablename($fields) VALUES ($parameters)"; //call here
  return $sql;
}

public function postData($tableName, $body, \PDO $pdo){
  $values = [];
  $errmsg = "";
  $code = 0;


  foreach($body as $value){
      array_push($values, $value);
  }
  
  try{
      $sqlString = $this->generateInsertString($tableName, $body);//insert into function
      $sql = $pdo->prepare($sqlString);
      $sql->execute($values);

      $code = 200;
      $data = null;

      return array("data"=>$data, "code"=>$code);
  }
  catch(\PDOException $e){
      $errmsg = $e->getMessage();
      $code = 400;
  }

  
  return array("errmsg"=>$errmsg, "code"=>$code);

}
################################
}
?>
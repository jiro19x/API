<?php

include_once"Common.php";

class Patch extends CommonMeThods{

protected $pdo;
public function __construct(\PDO $pdo){
$this->pdo =$pdo; //initialize
                                      }
    public function patchUser_tbl($body, $id){ 

                      ###### Getting-User-Name ######
    $authUser = $this->getHeaderValue("X-Auth-User"); // Get "X-Auth-User" value

     $values = []; 
     $errsmg = "";
     $code = 0;

    foreach($body as $value){
    array_push($values, $value);
    }
    array_push($values, $id); 

    try{
         $sqlString = "UPDATE user_tbl SET role=?, name=?, email=? WHERE id = ?";
         
    $sql = $this->pdo->prepare($sqlString); 
    $sql->execute($values);

    $code = 200;
    $data = null;
    $message = " record updated.";
   

    
            ###### logging data's #####
    $this->logger($authUser, "PATCH", "data record is PATCH from ['user_tbl']");

    return array("data"=>$data,"message"=>$message, "code"=>$code);

    }catch(\PDOException $e){
        $errsmg = $e->getMessage();
        $code = 400;

    }
                ###### logging data's #####
    $this->logger($authUser, "PATCH", $errsmg);
    return array("errmsg"=>$errsmg, "code"=>$code);

    }

    public function patchEvent_tbl($body, $id){ 

        ###### Getting-User-Name ######
$authUser = $this->getHeaderValue("X-Auth-User"); // Get "X-Auth-User" value

$values = []; 
$errsmg = "";
$code = 0;

foreach($body as $value){
array_push($values, $value);
}
array_push($values, $id); 

try{
$sqlString = "UPDATE events_tbl SET ticket_price=?, event_title=?, event_venue=?, event_date=?, event_time=? WHERE event_code = ?";

$sql = $this->pdo->prepare($sqlString); 
$sql->execute($values);

$code = 200;
$data = null;
$message = " record updated.";



###### logging data's #####
$this->logger($authUser, "PATCH", "data record is PATCH from ['event_tbl']");

return array("data"=>$data,"message"=>$message, "code"=>$code);

}catch(\PDOException $e){
$errsmg = $e->getMessage();
$code = 400;

}
  ###### logging data's #####
$this->logger($authUser, "PATCH", $errsmg);
return array("errmsg"=>$errsmg, "code"=>$code);

    }   
    ############ ARCHIVE DATA RECORDS ############
    public function archiveUser_tbl($id){
                  ###### Getting-User-Name ######
    $authUser = $this->getHeaderValue("X-Auth-User"); // Get "X-Auth-User" value
        $code = 0;

       try{
                      // UPDATING DATA RECORD FROM DB
            $sqlString = "UPDATE user_tbl SET isdeleted=1 WHERE id = ?";
            
       $sql = $this->pdo->prepare($sqlString); //prepare is the one way to configuring and protect from sql injection
       $sql->execute([$id]);//pass $id in array
   

       $code = 200;
       $data = null;
      $message = "The record has been successfully archived.";
      $recovery_info = "Please contact support for assistance with retrieving this record.";
                    ###### logging data's #####
        $this->logger($authUser, "DELETE", "data record is ARCHIVE from ['user_tbl']");
   
        return array("data"=>$data, "code"=>$code, "message"=>$message, "recovery_info"=>$recovery_info);
   
       }catch(\PDOException $e){
           $errsmg = $e->getMessage();
           $code = 400;
       }
                    ###### logging data's #####
       $this->logger($authUser, "DELETE", $errsmg);
   
       return array("errmsg"=>$errsmg, "code"=>$code);
   
    }

}//patch
?>
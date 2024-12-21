<?php

include_once"Common.php";
//inheretance
class Get extends CommonMeThods{
    protected $pdo;
    public function __construct(\PDO $pdo){
    $this->pdo =$pdo; //initialize
                                          }
####################  LOGS RECORDS  #########################
    //     ### GET LOGS V1 ###
    // public function getlogs($data = "2024-12-11"){
    // $filename = $data . ".log"; //date("Y-m-d")
    // $file = file_get_contents("./history-logs/$filename");
    // $logs = explode(PHP_EOL,"\r\n, $file");
    // return $this->sendResponse(array("logs"=>$logs), "Here's the log history for today's data.", "hisroty logs retrieved successfuly", 200);
    // }//getlogs


           ### GET LOGS V2 ###
public function getlogs($data = "2024-12-11") {
    try {
        $filename = $data . ".log"; // Use the provided date or default to today's date
        $filePath = "./history-logs/$filename"; // Path to the log file

        // Check if the file exists before attempting to read it
    if (!file_exists($filePath)) {
            //No Records
            throw new Exception("Log file for the given date does not exist.");
    }

        // Get the contents of the log file
        $file = file_get_contents($filePath);
        
        // Split the file content into an array of logs
        $logs = explode(PHP_EOL, $file);

        // Return a successful response
        return $this->sendResponse(array("logs" => $logs), "Here's the log history for today's data.", "History logs retrieved successfully", 200);
    } catch (Exception $e) {
        // Handle the error and return response
        return $this->sendResponse(null, "Failed to retrieve logs.", $e->getMessage(), 500);
    }
}//getlogs

####################  RETRIEVE RECORDS  #########################

public function GetUser_tbl($id = null){
        
    $condition = "isdeleted = 0";
    if($id != null){
    $condition .= " AND id = $id";
    }

    $result = $this->getDataByTable("user_tbl", $condition, $this->pdo);

    if($result['code'] == 200){
    return $this->sendResponse($result['data'], "The requested records have been retrieved from the database. The operation has been completed without any issues.", "success", $result['code']);
    }

    return $this->sendResponse(null, $result['errmsg'], "failed", $result['code']);
    
} //getchefs


public function GetAccounts_tbl($id = null){
    //$sqlString = "SELECT * FROM accounts_tbl";//remove ['password']
    $sqlString = "SELECT event_id, username, token FROM accounts_tbl";


 if($id!= null){
$sqlString .= " WHERE event_id=" . $id; 
 }
  // initialize variable
  $data = array();
  $errmsg = "";
  $code = 0;

  //try catch is to handle exemptions
  try{ // if record is recognize
    if($result = $this->pdo->query($sqlString)->fetchAll()){ //fetch record from db

        foreach($result as $record){
        array_push($data, $record);
                                   }

   $result = null;
   $code = 200;
   //return array("code"=>$code , "data"=>$data);
   return array("status" => "success", "code" => $code, "message" => "The requested records have been successfully retrieved.", "data" => $data, "details" => "Your request was processed without any issues.");
                                                          }
    
    else{ // if there is no record
        $errmsg = "No records were found matching your search criteria.";
        $code = 404;
        }

     }

   catch(\PDOException $e){  // if there is a error
$errmsg = $e ->GetMessage();
$code = 403;
return array("status" => "error", "code" => $code, "message" => "An error occurred while processing your request. Please try again later.", "details" => "Our team has been notified of the issue. If the problem persists, please contact support .");
  
                         }
return array("code"=>$code, "errmsg"=>$errmsg);
} //GetAccounts_tbl


public function GetEvents_tbl($id = null){
        
    //$sqlString = "SELECT * FROM accounts_tbl";//remove ['password']
    $sqlString = "SELECT * FROM events_tbl";


 if($id!= null){
$sqlString .= " WHERE event_code=" . $id; 
 }
  // initialize variable
  $data = array();
  $errmsg = "";
  $code = 0;

  //try catch is to handle exemptions
  try{ // if record is recognize
    if($result = $this->pdo->query($sqlString)->fetchAll()){ //fetch record from db

        foreach($result as $record){
        array_push($data, $record);
                                   }

   $result = null;
   $code = 200;
   //return array("code"=>$code , "data"=>$data);
   return array("status" => "success", "code" => $code, "message" => "The requested records have been successfully retrieved.", "data" => $data, "details" => "Your request was processed without any issues.");
                                                          }
    
    else{ // if there is no record
        $errmsg = "No records were found matching your search criteria.";
        $code = 404;
        }

     }

   catch(\PDOException $e){  // if there is a error
$errmsg = $e ->GetMessage();
$code = 403;
return array("status" => "error", "code" => $code, "message" => "An error occurred while processing your request. Please try again later.", "details" => "Our team has been notified of the issue. If the problem persists, please contact support .");
  
                         }
return array("code"=>$code, "errmsg"=>$errmsg);
    
} //GetEvents_tbl

}
?>
<?php
include_once "Common.php";

class Post extends CommonMeThods{
//jiro dichos
    protected $pdo;
    public function __construct(\PDO $pdo){ //pdo object
    $this->pdo =$pdo; //initialize
                                          }

    public function PostUser_tbl($body){
        $result = $this->postData("user_tbl", $body, $this->pdo);
        
           ###### Getting-User-Name ######
        $authUser = $this->getHeaderValue("X-Auth-User"); // Get "X-Auth-User" value

        if($result['code'] == 200){

            ###### logging data's #####
         $this->logger($authUser, "POST", "Created a new record from ['user_tbl']");
         return $this->sendResponse($result['data'], "success", "Successfully created a new record.", $result['code']);
       }
            ###### logging data's #####
        $this->logger($authUser, "POST", $result['errmsg']);
       return $this->sendResponse(null, "(failed) An error occurred while creating the record ", $result['errmsg'], $result['code']);
         
    }

    public function PostEvent_tbl($body){
        $result = $this->postData("events_tbl", $body, $this->pdo);
        
           ###### Getting-User-Name ######
        $authUser = $this->getHeaderValue("X-Auth-User"); // Get "X-Auth-User" value

        if($result['code'] == 200){

            ###### logging data's #####
         $this->logger($authUser, "POST", "Created a new record from ['events_tbl']");
         return $this->sendResponse($result['data'], "success", "Successfully created a new record.", $result['code']);
       }
            ###### logging data's #####
        $this->logger($authUser, "POST", $result['errmsg']);
       return $this->sendResponse(null, "(failed) An error occurred while creating the record ", $result['errmsg'], $result['code']);
         
    }

    public function PostTicket_tbl($body) {
        $result = $this->postData("ticket_tbl", $body, $this->pdo);
    
        ###### Getting-User-Name ######
        $authUser = $this->getHeaderValue("X-Auth-User"); // Get "X-Auth-User" value
    
        if ($result['code'] == 200) {
            ###### Logging data ######
            $this->logger($authUser, "POST", "Created a new record from ['ticket_tbl']");
            return $this->sendResponse($result['data'], "success", "Successfully created a new record.", $result['code']);
        }
    
        ###### Logging data ######
        $this->logger($authUser, "POST", $result['errmsg']);
        return $this->sendResponse(null, "(failed) An error occurred while creating the record", $result['errmsg'], $result['code']);
    }
    
}//post
?>
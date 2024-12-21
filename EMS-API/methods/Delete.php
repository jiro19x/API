<?php
//jiro dichos
include_once "Common.php";
class Delete extends CommonMeThods{


    protected $pdo;
    public function __construct(\PDO $pdo){ //pdo object
    $this->pdo =$pdo; //initialize
                                          }

    public function deleteUser($id){ //permanent delete

               ###### Getting-User-Name ######
    $authUser = $this->getHeaderValue("X-Auth-User"); // Get "X-Auth-User" value
    $code = 0;

       try{
                      // UPDATING DATA RECORD FROM DB
            $sqlString = "DELETE FROM user_tbl WHERE id = ?";
            
       $sql = $this->pdo->prepare($sqlString); //prepare is the one way to configuring and protect from sql injection
       $sql->execute([$id]);//pass $id in array
   
       $code = 200;
       $data = null;
       $message = "The record has been permanently deleted from the database.";
       $remarks = "success";

               ###### logging data's #####
        $this->logger($authUser, "DELETE", "data record is deleted from ['user_tbl']");

       return array("data"=>$data, "code"=>$code,"remarks"=>$remarks , "message"=>$message);
   
       }catch(\PDOException $e){
           $errsmg = $e->getMessage();
           $code = 400;
       }
               ###### logging data's #####
        $this->logger($authUser, "DELETE", $errsmg);
       return array("errmsg"=>$errsmg, "code"=>$code);
   
    }

    public function deleteTicket($id){ //permanent delete

        ###### Getting-User-Name ######
    $authUser = $this->getHeaderValue("X-Auth-User"); // Get "X-Auth-User" value
    $code = 0;

    try{
                   // UPDATING DATA RECORD FROM DB
        $sqlString = "DELETE FROM ticket_tbl WHERE id = ?";
     
    $sql = $this->pdo->prepare($sqlString); //prepare is the one way to configuring and protect from sql injection
    $sql->execute([$id]);//pass $id in array

    $code = 200;
    $data = null;
    $message = "The record has been permanently deleted from the database.";
    $remarks = "success";

            ###### logging data's #####
    $this->logger($authUser, "DELETE", "data record is deleted from ['ticket_tbl']");

    return array("data"=>$data, "code"=>$code,"remarks"=>$remarks , "message"=>$message);

    }catch(\PDOException $e){
        $errsmg = $e->getMessage();
        $code = 400;
    }
            ###### logging data's #####
    $this->logger($authUser, "DELETE", $errsmg);

    return array("errmsg"=>$errsmg, "code"=>$code);

    }

    public function deleteEvent($id){ //permanent delete

              ###### Getting-User-Name ######
    $authUser = $this->getHeaderValue("X-Auth-User"); // Get "X-Auth-User" value
    $code = 0;

    try{
               // UPDATING DATA RECORD FROM DB
     $sqlString = "DELETE FROM events_tbl WHERE id = ?";
     
    $sql = $this->pdo->prepare($sqlString); //prepare is the one way to configuring and protect from sql injection
    $sql->execute([$id]);//pass $id in array

    $code = 200;
    $data = null;
    $message = "The record has been permanently deleted from the database.";
    $remarks = "success";

            ###### logging data's #####
    $this->logger($authUser, "DELETE", "data record is deleted from ['events_tbl']");

    return array("data"=>$data, "code"=>$code,"remarks"=>$remarks , "message"=>$message);

    }catch(\PDOException $e){
        $errsmg = $e->getMessage();
        $code = 400;
    }
            ###### logging data's #####
    $this->logger($authUser, "DELETE", $errsmg);

    return array("errmsg"=>$errsmg, "code"=>$code);

    }

}
?>
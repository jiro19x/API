<?php
//jiro dichos

require_once "./config/database.php";
require_once "./methods/Get.php";
require_once "./methods/Post.php";
require_once "./methods/Patch.php";
require_once "./methods/Delete.php";
require_once "./methods/Auth.php";

//config
$pdo = (new Connection())->connect();

//instantiates
$post = new Post($pdo);
$get = new Get($pdo);
$patch = new Patch($pdo);
$delete = new Delete($pdo);
$auth = new Authentication($pdo);


//retrieved and endpoints and split
if(isset($_REQUEST['request'])){
    $request = explode("/", $_REQUEST['request']);
}else{
    echo "URL does not exist please try again";
    echo json_encode(["message" => "Invalid URL. Please provide a valid endpoint."]);
    http_response_code(400);
    exit();
}


switch($_SERVER['REQUEST_METHOD']){

    case "GET":

        switch($request[0]){ 

            case "user": //for chefs_tbl db
            if ($auth->isAuthorized()) {

            if (count($request) > 1){
                    echo json_encode($get->GetUser_tbl($request[1]));
                }else{
                echo json_encode($get->GetUser_tbl());
                     }      

            } else {
                echo json_encode([
                    "status" => "error",
                    "code" => 403 ]);
            http_response_code(403);
        }
            break;

            case "account": //for accounts_tbl db
            if ($auth->isAuthorized()) {

            if (count($request) > 1){
                    echo json_encode($get->GetAccounts_tbl($request[1]));
                }else{
                    echo json_encode($get->GetAccounts_tbl());
                    }     

            } else {
                echo json_encode([
                    "status" => "error",
                    "code" => 403 ]);
            http_response_code(403);
            }
                    break;

            case "history": // For getting/retrieving logs
            if ($auth->isAuthorized()) {

                //echo json_encode($get->getlogs($request[1])); // Fetching and displaying the logs
                echo json_encode($get->getLogs($request[1] ?? date("Y-m-d")));    
        
            } else {
                echo json_encode([
                    "status" => "error",
                    "code" => 403 ]);
            http_response_code(403);
                }
                    break;
                 
            case "event": //for events_tbl db
                if (count($request) > 1){
                    echo json_encode($get->GetEvents_tbl($request[1]));
                }else{
                    echo json_encode($get->GetEvents_tbl());
                    }         
            break;

            default:
                http_response_code(404);
                    echo json_encode([
             "status" => "error",
             "code" => 404,
             "message" => "The requested endpoint could not be found.",
             "details" => "Please check the URL or refer to the API documentation for valid endpoints."
             ]);
            
        }//get

        break;//case break
###################
case "POST":
    $body = json_decode(file_get_contents("php://input"), true);

    // Check if the first segment of the URL is 'ticket'
    if ($request[1] === "ticket") {
        // Handle the 'ticket' POST request directly, ignoring index 0
        echo json_encode($post->PostTicket_tbl($body));
    } else {
        // Process the other endpoints
        switch($request[0]){

            case "event": // event_tbl
            if ($auth->isAuthorized()) {

                echo json_encode($post->PostEvent_tbl($body));
            
            } else {
                echo json_encode([
                    "status" => "error",
                    "code" => 403 ]);
            http_response_code(403);
                }    
            break;    

            case "user": // user_tbl 
                echo json_encode($post->PostUser_tbl($body));
            break;
                
            case "add": // adding user accounts
                echo json_encode($auth->addAccount($body));
            break;

            case "login": // LOG IN (METHOD)
                echo json_encode($auth->login($body));
            break;
            
            default:
                http_response_code(404);
                echo json_encode([
                    "status" => "error",
                    "code" => 404,
                    "message" => "The requested endpoint could not be found.",
                    "details" => "Please check the URL or refer to the API documentation for valid endpoints."
                ]);
        } // switch for other endpoints
    } // if ticket
    break;

####################              
    case "PATCH":

        $body = json_decode(file_get_contents("php://input"));
        switch($request[0]){ 
    
            case "user":
              echo json_encode($patch->patchUser_tbl($body,$request[1]));
              http_response_code(200);
              echo json_encode($get->GetUser_tbl($request[1]));
            break;

            case "event":

            if ($auth->isAuthorized()) {

              echo json_encode($patch->patchEvent_tbl($body,$request[1]));
              http_response_code(200);
              echo json_encode($get->GetEvents_tbl($request[1]));

            } else {
                echo json_encode([
                    "status" => "error",
                    "code" => 403 ]);
                http_response_code(403);
            }  
            break;
    
            default:
                http_response_code(404);
                    echo json_encode([
             "status" => "error",
             "code" => 404,
             "message" => "The requested endpoint could not be found.",
             "details" => "Please check the URL or refer to the API documentation for valid endpoints."
             ]);
            
        }//patch
        break;//case break 
####################              
    case "DELETE":

        switch($request[0]){ 
    
            case "user"://archive data record 
                echo json_encode($patch->archiveUser_tbl($request[1]));       
            break;
            
            case "DELuser": //Permanet Delete USER DATA_record from ("USER")
    
            if ($auth->isAuthorized()) {
                try{
                echo json_encode($delete->deleteUser($request[1])); 
                }catch(\PDOException $e){
                        $errsmg = $e->getMessage();
                        $code = 400;
                    return array("errmsg"=>$errsmg, "code"=>$code);}

            } else {
                echo json_encode([
                    "status" => "error",
                    "code" => 403 ]);
                http_response_code(403);
            }
            break;

            case "DELticket": //Permanet Delete USER DATA_record from ("TICKET")
    
            if ($auth->isAuthorized()) {
                try{
                echo json_encode($delete->deleteTicket($request[1])); 
                }catch(\PDOException $e){
                        $errsmg = $e->getMessage();
                        $code = 400;
                     return array("errmsg"=>$errsmg, "code"=>$code);}
    
            } else {
                echo json_encode([
                    "status" => "error",
                    "code" => 403 ]);
                http_response_code(403);
            }
            break;

            case "DELevent": //Permanet Delete USER DATA_record from ("EVENT")
    
            if ($auth->isAuthorized()) {
                try{
                echo json_encode($delete->deleteEvent($request[1])); 
                }catch(\PDOException $e){
                        $errsmg = $e->getMessage();
                        $code = 400;
                     return array("errmsg"=>$errsmg, "code"=>$code);}
    
            } else {
                echo json_encode([
                    "status" => "error",
                    "code" => 403 ]);
                http_response_code(403);
            }
            break;

            
            
            default:
                http_response_code(404);
                    echo json_encode([
             "status" => "error",
             "code" => 404,
             "message" => "The requested endpoint could not be found.",
             "details" => "Please check the URL or refer to the API documentation for valid endpoints."
             ]);
            
        }//del
        break;//case break 
####################

    default:
    http_response_code(404);
    echo json_encode(["message" => "Endpoint not found. Please check the URL or Method request and try again."]);
    exit();

}//switch

?>
<?php

include_once "Common.php";
class Authentication extends CommonMeThods{
//jiro dichos
    protected $pdo;
    public function __construct(\PDO $pdo){ //pdo object
    $this->pdo =$pdo; //initialize
                                          }
    ############ Validate Access #############
    ##### isAuthorized V2
    public function isAuthorized() {
        $headers = getallheaders();

        // Validate Authorization Header
        if (!isset($headers['Authorization']) || empty($headers['Authorization'])) {//NO VALUE
            http_response_code(400);

            echo json_encode([
                "status" => "error",
                "message" => "Authorization header is missing. Please provide a valid token."
            ]);
            exit();
        }
        // Validate Token
        if ($headers['Authorization'] !== $this->getUserToken()) {//INVALID VALUE
            http_response_code(403);
            echo json_encode([
                "status" => "error",
                "message" => "Access denied. The provided token is invalid.",
                "code" => 403,
                "previlage" => "Access Denied",
                "details" => "Access Denied: You are not authorized to access this resource.If you believe this is an error, please contact support or ensure your account has the necessary permissions."
            ]);
            exit();
        }
        return true;
    }

    ###### getUserToken v2
    private function getUserToken() {
    $headers = getallheaders();

        // Validate X-Auth-User Header
    if (!isset($headers['X-Auth-User']) || empty($headers['X-Auth-User'])) {//NO VALUE
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Username header is missing. Please provide the 'X-Auth-User' header."
    ]);
    exit();
        }

        $sqlString = "SELECT token FROM accounts_tbl WHERE username = ?";
        $stmt = $this->pdo->prepare($sqlString);
        $stmt->execute([$headers['X-Auth-User']]);
        $res = $stmt->fetch();
    
    // Validate User Token
    if (!$res) {
        http_response_code(401);
        echo json_encode([
        "status" => "error",
        "message" => "Unauthorized User. No user found with the provided username.",
        "code" => 401,
        "previlage" => "Access Denied",
        "details" => "Access Denied: You are not authorized to access this resource.If you believe this is an error, please contact support or ensure your account has the necessary permissions."
            
    ]);
        exit();
    }
    
        return $res['token'];
    }
    
#############################################################################################################
    //  ### Validate Access v1 ##
    ##### isAuthorized V1                                       
    // public function isAuthorized(){
    //     $headers = getallheaders();
    //     return $headers['Authorization'] === $this->getUserToken();
    //     }     
    // ###### getUserToken v1
    // private function getUserToken(){
    //     $headers = getallheaders();
    //     $sqlString = "SELECT token FROM accounts_tbl WHERE username=?";
    //     $stmt = $this->pdo->prepare($sqlString);
    //     $stmt->execute([$headers['X-Auth-User']]);
    //     $res = $stmt->fetchAll()[0];
    //     return $res['token'];
    // }
    

    ############ JWT Generation #############
    private function generateHeader(){
    $header = [
    "alg" => "HS256",
    "typ" => "JWT",
    "app" => "EMS-API",
    "dev" => "Dichos Jiro"
              ]; 
    return base64_encode(json_encode($header));//base64 will return base64format               
    }
    private function generatePayload($id, $username){
    $payload = [
        "id" => $id,
        "uname" => $username,
        "by" => "Jiro_The_Programmer",
        "email" => "dichos.jiro@gordoncollege.edu.ph",
        "date" => date_create(),
        "exp" => date("Y-m-d H:i:s")
              ];

    return base64_encode(json_encode($payload));//base64UrlEncode(header) is from jwt VERIFY SIGNATURE
    }
    //generatePayload & generateHeader will be concatinatedm here
    private function generateToken($id,$username){
            $header = $this->generateHeader();
            $payload = $this->generatePayload($id, $username);              //define token key
            $signature = hash_hmac("sha256", "$header.$payload", TOKEN_KEY);
            return "$header.$payload." . base64_encode($signature);
    }
    //updateToken
    public function updateToken($token,$username){
            $code = 0;
    
           try{
                          // UPDATING DATA RECORD FROM DB
                $sqlString = "UPDATE accounts_tbl SET token=? WHERE username = ?";
                
           $sql = $this->pdo->prepare($sqlString);
           $sql->execute([$token,$username]);
       
           $code = 200;
           $data = null;
       
           return array("data"=>$data, "code"=>$code);
       
           }catch(\PDOException $e){
               $errsmg = $e->getMessage();
               $code = 400;
           }
       
           return array("errmsg"=>$errsmg, "code"=>$code);
       
    }
    
    ########### encrypting/hashing password ############
    public function encryptPassword($password){ 
        $hashFormat = "$2y$10$";
        $saltLength = 22;
        $salt = $this->generateSalt($saltLength);
        return crypt($password, $hashFormat . $salt);
    }
    public function generateSalt($length){
        $urs = md5(uniqid(mt_rand(), true)) ;  //md5 for ramdom generator of hash
        $base64String = base64_encode($urs); 
        $mb64String = str_replace("+",".", $base64String);
        return substr($mb64String, 0, $length);
    }

    ################### Login ######################                             
//    public function isSamePassword($existingHash, $inputPassword){
//         $hash = crypt($inputPassword, $existingHash);
//          return $hash === $existingHash;}//v2

//     public function isSamePassword($truepassword, $inputpassword){
//         return $truepassword === $inputpassword; }//v1

    public function isSamePassword($storedHash, $inputPassword) {
        // Validate the input password against the stored hashed password
        return crypt($inputPassword, $storedHash) === $storedHash;
    }//isSamePassword
    
    public function login($body){
    // $username = $body->username;
    // $password = $body->password;

            ###### Getting-User-Name ######
    $authUser = $this->getHeaderValue("X-Auth-User"); // Get "X-Auth-User" value

    $username = $body['username'];
    $password = $body['password'];  
    
    $code = 0;
    $payload = "";
    $remarks = "";
    $message = "";

    try{
        $sql = "SELECT event_id, username, password, token FROM accounts_tbl WHERE username =? ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$username]);

    if($stmt->rowCount() > 0){//user found
    $result = $stmt->fetchAll()[0];
    //return array($result['password']);

            ######validate password######
    if($this->isSamePassword($result['password'], $password)){
        $code = 200;
        $remarks = "success";
        $message = "Login successful! Welcome back, {$result['username']}! Your session is now active.";
        
           ##################### Generate Token #####################
        $token = $this->generateToken($result['event_id'], $result['username']);
        $token_arr = explode(".",$token);
        $this->updateToken($token_arr[2],$result['username']);

            ###### logging data's #####
        $this->logger($authUser, "POST", "user {$result['username']} is ['LOGIN']");

        $payload = array("id"=>$result['event_id'], "username"=>$result['username'],"token"=> $token_arr[2]);//$result['token']
        //return "Same Password";
        
    }else{
        
        $code = 401;
        $remarks = "failed";
        $message = "⚠️ Oops! The password you entered is incorrect. Please double-check and try again.";
        $payload = null;
       // return "Incorrect Password";
    }
    }else{ //user not found
        $code = 400;
        $remarks = "failed";
        $message = "X User not found. Make sure you've registered or contact the admin for assistance.";
        $payload = null;
    }
    }catch(\PDOException $e){
        $errsmg = $e->getMessage();
        $code = 500;
        $remarks = "error";
        $message = " An error occurred while trying to log you in. Please try again later.";
        $payload = null;
    
               ###### logging data's #####
    $this->logger($authUser, "POST", $errsmg);
    }   
    //success execute 
    return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
    }//login


    ########## adding account from accounts_tbl ############
    public function addAccount($body){

           ###### Getting-User-Name ######
    $authUser = $this->getHeaderValue("X-Auth-User"); // Get "X-Auth-User" value

     $values = []; 
     $errsmg = "";
     $code = 0;

    ##################PASSWORD HASHING#########################
    //$body['password'] = $this->encryptPassword($body->password);
    $body['password'] = $this->encryptPassword($body['password']);
    
    foreach($body as $value){ //call the values of the json txt variables
    array_push($values, $value);// array_push is to add value
    }

    try{
                   //   INSERTING to data base
         $sqlString = "INSERT INTO accounts_tbl(event_id, username, password) VALUES (?,?,?)";//binding variable
    $sql = $this->pdo->prepare($sqlString); //prepare is the one way to configuring and protect from sql injection
    $sql->execute($values);

    $code = 200;
    $data = "inserted"; 
    $message = "Account successfully created! 🎉 Welcome aboard. Administrators can now manage this account through the accounts_tbl.";

                    ###### logging data's #####
    $this->logger($authUser, "POST", "you have added a new data record from ['accounts_tbl']");

    return array("data"=>$data, "code"=>$code, "message"=>$message);

    }catch(\PDOException $e){
        $errsmg = $e->getMessage();
        $code = 500;
        $message = "Failed to add the account.  Something went wrong while saving to the database. Error: ";
    }
               ###### logging data's #####
    $this->logger($authUser, "POST", $errsmg);

    return array("code"=>$code,"message"=>$message, "errmsg"=>$errsmg);

    }//addAccount

}
?>
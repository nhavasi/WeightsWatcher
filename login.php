<script>
   //prevent form resubmission on refresh or back
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>

<?php
   //Some comments are print statements that were originally
   //used for debugging but not good for practical use.
   //These print statements remain as comments for 
   //accessability reasons as well as readability/clarity reasons.


   //create exercises database (validate = line 18)

   //set database connection variables
   $servername = "localhost";
   $dbusername = "root";
   $dbpassword = "";
   $database = "exercises";
   $table = "users";

   //create a connection and validate it
   $dbconnect = new mysqli($servername,$dbusername,$dbpassword);
   if($dbconnect->connect_error) {
      die("Connection failed:". $connect->connect_error);
   };   

   $create = "CREATE DATABASE IF NOT EXISTS `exercises`";
   //validate db creation
   if ($dbconnect->query($create) === TRUE) {
      //echo ("Database 'exercises' created successfully.");
   } else {
      echo ("Error creating database 'exercises'.");
   };

   $use = "USE `exercises`";
   if ($dbconnect->query($use) === TRUE){
      //echo ("Database 'exercises' connected successfully.");
   } else {
      echo ("Error connecting to database 'exercises'.");
   };

   //create users table
   $sqlcreate =
      "CREATE TABLE IF NOT EXISTS `users` (
      `username` varchar(16) NOT NULL,
      `password` varchar(16) NOT NULL,
      `userId` int(11) NOT NULL AUTO_INCREMENT,
      PRIMARY KEY (`userId`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
   //validate creation
   if ($dbconnect->query($sqlcreate) === TRUE) {
      //echo("Table 'users' created successfully. ");
   } else {
      echo("Error creating table 'users'. ");
   };


   //EMPTY FORM
   if (!IsSet($_POST['user']) or !IsSet($_POST['password'])) { 
      heading(); //send the beginning HTML
      print ('<p><a href = "login.html">Please login.</a></p>');

   //FORM FILLED OUT
   } else {  
   
      //retrieve user and password from form
	   $userName = $_POST['user'];
	   $userPW =   $_POST['password'];




      //NEW USER
      if (IsSet($_POST['newUser']) && $_POST['newUser'] == 'yes') {
         
         // check if name available - search in file
		   $nameTaken = findUserInDb($userName, $userPW);

         //LOGIN RESULT 1
         //LOGIN FAIL
         //new user, but name taken
         if ($nameTaken == true) {
            heading();
            print ("<p>This name has already been taken.</p>
                 	<p><a href='login.html'>Try Again</a>");
         
         //LOGIN RESULT 2
         //LOGIN SUCCESS
         //new user, name not taken
         //add new user record
         } else if ( $nameTaken == false ) {
         
            //new user insert query
            $sql = "INSERT INTO users (username,password) VALUES (?,?)";

            //prep and bind query
            $stmt = $dbconnect->prepare($sql);
            $stmt->bind_param("ss",$newName,$newPw);

            //clean user input from form
            $newName = filter_var($userName, FILTER_SANITIZE_STRING);
            $newPw = filter_var($userPW, FILTER_SANITIZE_STRING);
            $stmt->execute();

            //succesful new user
            echo ("New user created successfully! ");
            
            //get id cookie
            $id = $GLOBALS["dbconnect"]->insert_id;

            idCookie($id);
            writeCookies($userName); //user name
            heading(); //send the beginning HTML
            print ("<p>Your information has been processed.</p>
                 	<p><a href='home.html'>Start Logging</a></p>");

         }; // end process new user data

      

      
      //NOT NEW USER
      } else {

         //default
         $authenticated = false;

         //look for existing username with password
		   $userFound = findRegisteredUser($userName, $userPW);
         
         //authenticate if found or not
         if ($userFound == true){
            $authenticated = true;
            writeCookies($userName); 

            //get userId cookie
            $array = [];
            $stmt = $GLOBALS["dbconnect"]->prepare(
            "SELECT userId from `users`
            where `username`=?");
            $stmt->bind_param('s',$userName);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows === 0) exit();
            while($row = $result->fetch_assoc()) {
               $array[] = $row['userId'];
               $id = $array[0];
            }
            idCookie($id);

            // idCookie($userId);//user name
		   } else {
		      $authenticated = false;
            heading(); //send the beginning HTML
         };

         //LOGIN RESULT 3
         //LOGIN SUCCESS
         //proceed login if username and password have existing match
         if ($authenticated == true) {
            heading();
            print ("<p>Thank you for returning, $userName!</p>
            	<p><a href='home.html'>Continue</a></p>");
         
         //LOGIN RESULT 4 & 5
         //LOGIN FAIL
         //either wrong password, or user not registered
         } else if ($authenticated == false) {
            print ("<p>You are not a registered user.</p>
                  <p><a href='login.html'>Register</a></p>");
         };
      }; // end for existing user
   }; // end form eval

   print ("</body>\n</html>\n");


// function to output header
function heading() {
   // output header
   // output XML declaration and DOCTYPE
   header("Content-type:text/html");
   print ("<?xml version = '1.0'?>
        <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0
        Transitional//EN' 'http://www.w3.org/TR/xhtml1
        /DTD/xhtml1-transitional.dtd'>");

   // output html element and some of its contents
   print ('
   <link rel="stylesheet" href="global.css" />
   <html xmlns = "http://www.w3.org/1999/xhtml">
        <head>
         <br/>
            <title>Weights Watcher</title>
            <style>
               body  {text-align:center;font-family:sans-serif}
               p     {font-size:16pt}
            </style>
        </head>
        <body style="background-color:#4a943c;">
        <br/><h1>Weights Watcher</h1><br/>');
} // end header


//NEW USER search for name in db
function findUserInDb($user, $pass) {
   //clean user input
   $username = filter_var($user, FILTER_SANITIZE_STRING);
   $password = filter_var($pass, FILTER_SANITIZE_STRING);

   //find count of existing users with this username
   $sql = "SELECT COUNT(*) FROM `users`
      WHERE `username`=?";
   
   //check if username exists
   $stmt = $GLOBALS["dbconnect"]->prepare($sql);
   $stmt->bind_param('s',$username);
   $stmt->execute();
   $stmt->bind_result($users);
   
   //print result as int
   while ($stmt->fetch()){
      $users;
   }

   //if no users exist with that name
   if($users == 0){
      //echo ("Username available. ");  
      //$nametaken = false    
      return false;

   //user already exists with that name
   } else if ($users != 0){
      echo("Username unavailable. ");
      //$nametaken = true
      return true;
   };
};

//RETURNING USER search for name and password in db
function findRegisteredUser($user,$pass) {
   //clean user input
   $username = filter_var($user, FILTER_SANITIZE_STRING);
   $password = filter_var($pass, FILTER_SANITIZE_STRING);

   //find count of existing users with this username
   $sql = "SELECT COUNT(*) FROM `users`
   WHERE `username`=?";

   //check if username exists
   $stmt = $GLOBALS["dbconnect"]->prepare($sql);
   $stmt->bind_param('s',$username);
   $stmt->execute();
   $stmt->bind_result($users);
   
   //fetch result as int
   while ($stmt->fetch()){
      $users;
   }

   //if user exists
   if($users == 1){
      //echo ("User found");

      //query matching username to find its password
      $sql = "SELECT username, `password` FROM `users`
         WHERE `username`=? AND `password`=?";

      //execute query
      $stmt = $GLOBALS["dbconnect"]->prepare($sql);
      $stmt->bind_param('ss',$username,$password);
      $stmt->execute();

      //username,password output bound to $userR and $passR
      $stmt->bind_result($userR,$passR);
      
      //fetch db username and password
      while ($stmt->fetch()){
         $userR; 
         $passR;
      }

      //if password output matches user's password input
      if ($passR == $pass){
         echo "User logged-in successfully. ";     
         //$userfound = true
         return true;
      
      //if password output does not match user's password input
      } else if ($passR != $password) {
         echo ("Incorrect password for this user. ");

         //User found but incorrect password
         return false;
      };
   //User does not exist in database
   } else {
      echo "Error finding user profile. ";
      
      //$userfound = false
      return false;
   };
};

function idCookie($id){
   $cookieIdSent = setcookie("id", $id, time()+60*60*24);
};

// function to write name cookie
function writeCookies($userName) {
	$cookieNameSent = setcookie("name", $userName, time()+60*60*24, "/");
}; // end writeCookies
	 
?>
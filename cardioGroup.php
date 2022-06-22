<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<link rel="stylesheet" href="global.css" />
<link rel="stylesheet" href="logSets.css" />

<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>

<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-type" content="text/html/css" charset="UTF-8" />
    <title>WW Log Sets</title>
  </head>

  <body>
    <header>LOG SETS</header>

    <div class="group-container">
      CHOOSE A GROUP:
      <a href="chestGroup.php" style="color:white;text-decoration:none;">
        <div class="group-item">CHEST</div>
      </a>
      <a href="armsGroup.php" style="color:white;text-decoration:none;">
        <div class="group-item">ARMS</div>
      </a>
      <a href="backGroup.php" style="color:white;text-decoration:none;">
        <div class="group-item">BACK</div>
      </a>
      <a href="coreGroup.php" style="color:white;text-decoration:none;">
        <div class="group-item">CORE</div>
      </a>
      <a href="legsGroup.php" style="color:white;text-decoration:none;">
        <div class="group-item">LEGS</div>
      </a>
      <a href="cardioGroup.php" style="color:white;text-decoration:none;">
        <div class="group-item">CARDIO</div>
      </a>
    </div>

    <div class="exr-container">
      <span class="exrHeader">EXERCISE:</span>
      <input type="text" class="search" placeholder=" Search..." />
      <form method="POST" action="cardioGroup.php" style="grid-column: 1 / span 2">
        <input type="text" name="typeNew" class="typeNew" placeholder="+" />
        <input type="submit" name="enterNew" style="display: none" />
      </form>
 
<?php 
    //set database connection variables
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $database = "exercises";

    //create a connection and validate it
    $dbconnect = new mysqli($servername,$dbusername,$dbpassword,$database);
    if($dbconnect->connect_error) {
        die("Connection failed:". $connect->connect_error);
    }; 

    //create cardiogroup 
    $sqlcreate =
        "CREATE TABLE IF NOT EXISTS `cardiogroup` (
        `userId` int(11) NOT NULL,
        `exerciseType` varchar(100) NOT NULL,
        `exrLog` varchar(100) NOT NULL,
        PRIMARY KEY (`exerciseType`),
        KEY `cardiogroup_FK` (`userId`),
        CONSTRAINT `cardiogroup_FK` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    //validate creation
    if ($GLOBALS["dbconnect"]->query($sqlcreate) === TRUE) {
        //echo("Table 'cardiogroup' created successfully. ");
    } else {
        echo("Error creating table 'cardiogroup'. ");
    };

    // $sql = "SELECT username,`password` FROM users";
    // $result = $GLOBALS["dbconnect"]->query($sql);
    

    //'NEW EXERCISE' - INPUT = TRUE
    if(isset($_POST['enterNew'])){
        //puts string entry as php variable and cleans it
        $txt = $_POST['typeNew'];
        $newEntry = filter_var($txt, FILTER_SANITIZE_STRING);

        //Logs entry if not empty (nor spaces)
        if($newEntry=='' or $newEntry[0]==' ') {
        echo("<span style='color:blue;grid-column: 1 / span 2;'>Please enter a value</span>");
        } else {
        //prepare to insert new exercise into db
        $sql = ("INSERT INTO cardiogroup (`userId`,`exerciseType`,`exrLog`) VALUES (?,?,?)");
        $stmt = $GLOBALS["dbconnect"]->prepare($sql);
        $stmt->bind_param("iss",$userId,$exerciseType,$exrLog);
        
        //execute insert
        $userId = $_COOKIE["id"];
        $exerciseType = "$newEntry";
        $exrLog = "filler";
        $stmt->execute();
        };
    };
    
    $sql = "SELECT exerciseType FROM cardiogroup";
    $result = $GLOBALS["dbconnect"]->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
        echo '<div class="exr-item" name="exr-item">',$row["exerciseType"], '</div>';
        }
    };
    ?>
    </div>
</body>
</html>
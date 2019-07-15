<?php
session_start();

require 'Includes/dbcon.php';
//include 'Includes/RepoDB.php';
include 'Includes/Common.php';

use PostgreSQL\Connection as Connection;

// connect to the PostgreSQL database
$pdo = Connection::get()->get_db();

$FailedLogin = false; //initialize

if (isset($_POST['username']) && isset($_POST['pwd'])){

    //post values exist
	$username = htmlspecialchars($_POST['username']);
    $pwd = htmlspecialchars($_POST['pwd']);
    $sql = 'SELECT password FROM login WHERE username=:username';

	$statement = $pdo->prepare($sql);
    $statement->bindValue(':username', $username);
    $result = $statement->execute();

	if ($result){
		$row = $statement->fetch();
        $hashedPasswordFromDB = $row['password'];

        if (password_verify($pwd, $hashedPasswordFromDB)) {
			//password validates so login
			$_SESSION['username'] = $username;
			header("Location: ../Personnel.php");
			die();
		}
		else{
            $FailedLogin = true;
		}
	}
	else {
        //no results
        $FailedLogin = true;
	}
}

?>
<html>
<head>
    <title>Sign In</title>
    <link href='https://fonts.googleapis.com/css?family=Kanit' rel='stylesheet' />
</head>
<body>
    <div class="container masthead">
        <div class="logo"></div>
    </div>
    <div class="container">
        <form action="index.php" method="post">
            <?php
            if ($FailedLogin)
            {
                echo '<div class="alert alert-warning">Incorrect username or password!</div>';
            }
            ?>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control input-edit" id="username" name="username" placeholder="Username" required />
            </div>
            <div class="form-group">
                <label for="pwd">Password:</label>
                <input type="password" class="form-control input-edit" id="pwd" name="pwd" placeholder="Password" required />
            </div>
            <div class="row">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary pull-right">Submit</button>
                </div>
            </div>
        </form>
    </div>




</body>
</html>
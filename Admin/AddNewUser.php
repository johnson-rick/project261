<?php

/**
 * AddNewUser short summary.
 *
 * AddNewUser description.
 *
 * @version 1.0
 * @author rickj
 */

session_start();
if (isset($_SESSION['username'])){
    $username = $_SESSION['username'];
}
else
{
	header("Location: index.php");
    die();
}

require '../Includes/dbcon.php';
//include '../Includes/RepoDB.php';
//include '../Includes/Common.php';

use PostgreSQL\Connection as Connection;

// connect to the PostgreSQL database
$pdo = Connection::get()->get_db();

$pwd_match = true;
$pwd_strength = true;
$post_back = false;

$username = htmlspecialchars($_POST['username']);
$password = htmlspecialchars($_POST['pwd']);
$confirmpassword = htmlspecialchars($_POST['confirm_pwd']);

if (!isset($username) || $username == "" || !isset($password) || $password == ""){
    //no post, do nothing
}
else {
    $post_back = true;
    //check password rules
    if(strlen($password) >= 7 && ContainsNumber($password)){
        //if( preg_match("^(?=.*[\d])[A-Za-z\d]{7,}$", $password)){
        $pwd_strength = true;
    }
    else {
        $pwd_strength = false;
    }
    if($password == $confirmpassword){
        $pwd_match = true;
    }
    else {
        $pwd_match = false;
    }

    if($pwd_match == true && $pwd_strength == true){
        //only continue processing if passwords match & password strength is good
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        //in real-life, check uniqueness on username before insert
        $statement = $pdo->prepare('INSERT INTO login(username, password) VALUES(:username, :password)');
        $statement->bindValue(':username', $username);
        $statement->bindValue(':password', $passwordHash);
        $statement->execute();

        // now redirect to login
        header("Location: ../Personnel.php");
        die();
    }
}

function ContainsNumber($String){
    return preg_match('/\\d/', $String) > 0;
}

?>
<html>
<head>
	<title>Add User</title>
<!--	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />-->
	<!--<script src="../js/PasswordValidate.js"></script>-->
<!--	<link href="../css/project1.css" type="text/css" rel="stylesheet" />
	<link href="../css/forms.css" type="text/css" rel="stylesheet" />-->
</head>
<body>
	<div class="container masthead">
		<div class="logo">
			
		</div>
		<div class="navbar  navbar-static-top">
			<ul>
				<li>
					<a href="../Personnel.php">Personnel</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="container">
		<h3>Add User:</h3>
		<form action="AddNewUser.php" method="post">

			<div class="form-group">
				<label for="username">Username:</label>
				<input type="text" class="form-control input-edit" id="username" name="username" placeholder="Username" value="<?=$username?>" required />
			</div>
			<div class="form-group">
				<label for="pwd">Password:</label>
				<input type="password" class="form-control input-edit" id="pwd" name="pwd" placeholder="Password" value="<?=$password?>" required />
			</div>
			<div class="form-group">
				<label for="pwd">Password:</label>
				<input type="password" class="form-control input-edit" id="confirm_pwd" name="confirm_pwd" placeholder="Confirm Password" value="<?=$confirmpassword?>" required />
				<div id="password_error" class="badge badge-danger hide">Password must be at least 7 characters and contain a number!</div>
				<?php
                if ($pwd_match == false && $post_back == true){
                    echo '<div class="badge badge-danger">Passwords must match!</div>';
                }
                if ($pwd_strength == false && $post_back == true){
                    echo '<div class="badge badge-danger">Password must be at least 7 characters and contain a number!</div>';
                }
				?>
			</div>
			<div class="row">
				<div class="col-md-6">
					<a href="../Personnel.php" type="submit" class="btn btn-default">Cancel</a>
				</div>
				<div class="col-md-6">
					<button id="addAccount" type="submit" class="btn btn-primary pull-right">Add User</button>
				</div>
			</div>
		</form>
	</div>

</body>
</html>
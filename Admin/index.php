<?php
session_start();

require '../Includes/dbcon.php';
//include 'Includes/RepoDB.php';
include '../Includes/Common.php';

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
<!--	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />-->
	<link href='https://fonts.googleapis.com/css?family=Kanit' rel='stylesheet' />
<!--	<link href="css/project1.css" type="text/css" rel="stylesheet" />
	<link href="css/forms.css" type="text/css" rel="stylesheet" />-->
</head>
<body>
	<div class="container masthead">
		<div class="logo">
			
		</div>
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
				<input type="text" class="form-control input-edit" id="username" name="username" value="Rick" placeholder="Username" required />
			</div>
			<div class="form-group">
				<label for="pwd">Password:</label>
				<input type="password" class="form-control input-edit" id="pwd" name="pwd" placeholder="Password" required value="Porra1a9454" />
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
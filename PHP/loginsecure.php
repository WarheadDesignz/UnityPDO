<?php

	//Created by - David Watts. (C)Warhead-Designz 2016 All Rights Reserved.
	//(you are free to use this for your game, but you can not sell or re-distribute this as either an item or in a pack with other items).


	$servername = "localhost";
	$server_username = "Username";
	$server_password = "Password";
	$dbName = "databaseName"; // Might look something like (ActName_DatabaseName)

	//Connection
	$conn = new PDO ("mysql:host=$servername;dbname=$dbName", $server_username, $server_password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	//Check Connection
	if(!$conn){
		die("Connection failed.");
	}
	
	$username = ($_POST['usernamePost']);
	$password = ($_POST['passwordPost']);
	
	// We select the HASHED password inside the database, then we feed it our password from Unity,
	// Then we use password_verify at the bottom to determine if they are matched.
	if($stmt = $conn->prepare("SELECT password FROM accounts WHERE username=:username")){
	$stmt->bindParam(":username",$username);
	$stmt->execute();

	// Result = the HASHED password, this will not give out a unhashed password.
	$result = $stmt->fetchColumn();

	// Now we verify it.
	if(password_verify($password,$result)){
		echo"1"; // password MATCHES (HASH) (LOGIN SUCCESSFUL!) - Tells Unity.
	}else{
		echo"00";// Tells Unity it wasn't successful, so to try again.
	}
}
?>
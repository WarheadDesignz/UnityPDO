<?php

	//Created by - David Watts. (C)Warhead-Designz 2016 All Rights Reserved.
	//(you are free to use this for your game, but you can not sell or re-distribute this as either an item or in a pack with other items).
	
	$servername = "localhost";
	$server_username = "Username";
	$server_password = "Password";
	$dbName = "databaseName"; // Might look something like (ActName_DatabaseName)
	

	try{
		// Don't Change These to anything.
		$conn = new PDO ("mysql:host=$servername;dbname=$dbName", $server_username, $server_password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$username = ($_POST['usernamePost']);
		$password = ($_POST['passwordPost']);
		$email = ($_POST['emailPost']);
		
		// Lets Encrypt Our Password with a strength of 12 (High = More time needed to make a stronger pass)
		// NOT RECOMMENDED to go higher unless your machine(server) can handle it.
		$options = [
		'cost' => 12,
		];
		$passwordNew = password_hash($password, PASSWORD_BCRYPT,$options);
			
			
		// Make sure to have a Table called accounts with a columns called email, username and password.
		// This is where we check if an account already exists with the Username.
		// Change "guestaccounts" to databasename of choice if you already have one.
		$stmt = $conn->prepare('SELECT * FROM accounts WHERE username=?');
		$stmt->bindParam(1,$username);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		// If Account (DON'T EXIST) { CREATE IT! }.
		if(!$row){
			if($stmt = $conn->prepare("INSERT INTO accounts (email,username,password) VALUES (?,?,?)")){
				$stmt->bindValue(1,$email);
				$stmt->bindValue(2,$username);
				$stmt->bindValue(3,$passwordNew);
				$stmt->execute();
			}
		}else{
			// Else - if account DOES exist - Tell Unity.
			die('Account Exists!');
			echo"00";
		}
		
		

	}	catch(PDOException $e){
		echo $sql . "<br>" . $e->getMessage();
	}
	$conn = null;
?>
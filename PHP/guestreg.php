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
		
		$userName = ($_POST['usernamePost']);
		$passWord = ($_POST['passwordPost']);
		
		
		// Lets Encrypt Our Password with a strength of 12 (High = More time needed to make a stronger pass)
		// NOT RECOMMENDED to go higher unless your machine(server) can handle it.
		$options = [
		'cost' => 12,
		];
		$passwordNew = password_hash($passWord, PASSWORD_BCRYPT,$options);
		
		// Make sure to have a Table called guestaccounts with a column called username.
		// This is where we check if an account already exists with the Username.
		// Change "guestaccounts" to databasename of choice if you already have one.
		$stmt = $conn->prepare('SELECT * FROM guestaccounts WHERE username=?');
		$stmt->bindParam(1,$username);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		// If the Username doesn't exist, then lets create it! 
		if(!$row){
			if($stmt = $conn->prepare("INSERT INTO guestaccounts (username,password) VALUES (?,?)")){
				echo"1";
				$stmt->bindValue(1,$userName);
				$stmt->bindValue(2,$passwordNew);
				$stmt->execute();
				// This tells Unity we are successful!
			}
		}else{
			// If it isn't successful (means account exists), then we tell Unity, and the Guest Script will try again on its own!
			echo"00";
		}
	}	catch(PDOException $e){
		echo $sql . "<br>" . $e->getMessage();
	}
	$conn = null;
?>
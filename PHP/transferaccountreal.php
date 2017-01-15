<?php

/*
This script is ONLY useful if you want others to play your game without officially registering,
and allowing them to transfer data from a guest account to a new official registered account.

I made this for my game, (IT HAD MORE IN IT), such as transfering in game money stored on server
into the registered account - so it was seamless without loosing things because of lack of registering.

So I recommend only using this if you either 

A) - Want people to play without registering to try the game.

Or 

B) - Want to modify this to support more things that would really make sense (As in my case, transfering)
in game currency into a registered account, or upgraded powerups an such.

But regardless, this is good if you still want to do it, because not everyone wants to register to play,
and would rather try it and get addicted first :p.

Created by - David Watts. (C)Warhead-Designz 2016 All Rights Reserved.
(you are free to use this for your game, but you can not sell or re-distribute this as either an item or in a pack with other items).
*/

	
	$servername = "localhost";
	$server_username = "Username";
	$server_password = "Password";
	$dbName = "databaseName"; // Might look something like (ActName_DatabaseName)
	
	

	
	try{
		$conn = new PDO ("mysql:host=$servername;dbname=$dbName", $server_username, $server_password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		//Guest Act Info.
		$gUserName = ($_POST['guestUsernamePost']);
		$gPass = ($_POST['guestPassPost']);
		
		// Register Account Info.
		$username = ($_POST['usernamePost']);
		$password = ($_POST['passwordPost']);
		$email = ($_POST['emailPost']);
		
		
		// Local Variables
		$passResult;
		
		// Lets Encrypt Our Password with a strength of 12 (High = More time needed to make a stronger pass)
		// NOT RECOMMENDED to go higher unless your machine(server) can handle it.
		$options = [
		'cost' => 12,
		];
		$passwordNew = password_hash($password, PASSWORD_BCRYPT,$options);
		
		// Make sure to have a Table called guestaccounts with a column called username.
		// This is where we check if an account already exists with the Username.
		// Change "guestaccounts" to databasename of choice if you already have one.
		
		// Get the password for the guest account (HASH)
		if($stmt = $conn->prepare("SELECT password FROM guestaccounts WHERE username=:username")){
			$stmt->bindParam(":username",$gUserName);
			$stmt->execute();
			$passResult = $stmt->fetchColumn();
		}
		
		// Now lets verify that you are the actual Guest Account owner, if so - lets begin the transfer!
		if(password_verify($gPass,$passResult)){
			// Make sure you have a table called "accounts" with email, username and password in it.
			if($stmt = $conn->prepare("INSERT INTO accounts (email,username,password) VALUES (?,?,?)")){
					$stmt->bindValue(1,$email);
					$stmt->bindValue(2,$username);
					$stmt->bindValue(3,$passwordNew);
					if($stmt->execute()){
						echo"1"; // ACCOUNT CREATED - Tell Unity.
					}
				}
			
			
			// Destroy old Guest Data after transfer.
			if($stmt = $conn->prepare("DELETE FROM guestaccounts WHERE username=:username")){
				$stmt->bindParam(":username",$gUserName);
				$stmt->execute();
			}
			
		
		}else{
			echo"wrong password";
		}
		
		
	}catch(PDOException $e){
		echo $sql . "<br>" . $e->getMessage();
	}
	$conn = null;
		
?>
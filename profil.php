<?php
/*
* By Stefan Schumacher
*/
include ('./template/header.php');?>

<title>Profil</title>
</head>	
<body>
<?php include ('./template/navbar.php'); ?>

<div class="container">
	
<?php require_once("dbConnection.php"); 
session_start();
if(!isset($_SESSION["username"])){
    header("Location: login.php");
    exit;
}

if(isset($_POST["btProfilAbschicken"])){
	$_SESSION["vName"] = $_POST["tfvName"];
	$_SESSION["nName"] = $_POST["tfnName"];
	$_SESSION["pPath"] = $_POST["tfpPath"];
	$_SESSION["bDate"] = $_POST["tfbDate"];	


    $stmt = $mysql->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$_SESSION["username"]]);
	$inhalt = $stmt->fetch();
	$userEiDi = $inhalt[0];
	$_SESSION["uId"] = $inhalt[0];

	$stmt = $mysql->prepare("SELECT id FROM profiles WHERE user_id = ?");
    $stmt->execute([$userEiDi]);    
	$count = $stmt->rowCount();    
	
	if($count != 0){
		echo"<h4><b>Profil bereits vorhanden!</b></h4>";
		echo"<form action='profil.php' method='post'> <p>Überschreiben ?</p> <input type='submit' id='btUpdate' name='btUpdate' value='Update'></form>";
		echo "<p> oder hier <a href = 'usersPage.php'>zurueck</a></p>";
		include ('./template/footer.php');
		exit;

	} else {
		$stmt = $mysql->prepare("INSERT INTO profiles (id, user_id, first_name, last_name, picture_path, birth_date, created) VALUES (NULL, ?, ?, ?, ?, ? , current_timestamp());");
		$stmt->execute([$userEiDi, $_POST["tfvName"], $_POST["tfnName"], $_POST["tfpPath"], $_POST["tfbDate"]]);
		echo "<h4><b>Profil erstellt!</b></h4>";
		echo "<a href = 'usersPage.php'>zurueck</a>";
		include ('./template/footer.php');
		exit;		
	}

}

if(isset($_POST["btUpdate"])){
		$stmt = $mysql->prepare("UPDATE profiles SET first_name = ?, last_name = ?, picture_path = ?, birth_date = ?, created = current_timestamp() WHERE user_id = ?");
		$stmt->execute([$_SESSION["vName"], $_SESSION["nName"], $_SESSION["pPath"], $_SESSION["bDate"], $_SESSION["uId"]]);
	
	echo "<h3>Profil upgedatet</h3>";  
	echo "<a href = 'usersPage.php'>zurueck</a>";
	include ('./template/footer.php');
	exit;		
	}

	if(isset($_POST["btDelete"])){
		$stmt = $mysql->prepare("DELETE FROM profiles WHERE user_id = ?");
		$stmt->execute([$_SESSION["uId"]]);
	
	echo "<h3>Profil gelöscht</h3>";  
	echo "<a href = 'usersPage.php'>zurueck</a>";
	include ('./template/footer.php');
	exit;		
	}


?>
<p>Geben Sie hier Ihre Daten ein um Ihr Profil zu vervollständigen: </p>
<form action="profil.php" method="post">		
<h3>Ihre Daten</h3>
<br>
<label for="tfvName">Vorname:</label>
<input type="text" id="tfvName" name="tfvName" placeholder="Vorname">
<br><br>
<label for="tfnName">Nachname:</label>
<input type="text" id="tfnName" name="tfnName" placeholder="Nachname">
<br><br>			
<label for="tfpPath">Bilderpfad:</label>
<input type="text" id="tfpPath" name="tfpPath" placeholder="zB: C:\Users\...\Pictures">
<br><br>
<label for="tfpPath">Geburtsdatum:</label>
<input type="text" id="tfbDate" name="tfbDate" placeholder="yyyy-mm-tt">
<br>
<input type="submit" id="btProfilAbschicken" name="btProfilAbschicken" value="Absenden">	
<br>
</form>
<br>
<form>
<input type='submit' id='btDelete' name='btDelete' value='Profil löschen'>
<br>
</form>
</div>
<?php include ('./template/footer.php');?>
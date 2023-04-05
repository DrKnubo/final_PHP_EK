<?php
/*
* By Stefan Schumacher
*/

include ('./template/header.php');?>

<title>Register</title>
</head>
<?php include ('./template/navbar.php');?>	
<body>
<?php
require_once("dbConnection.php");

if(isset($_POST["submit"])){
    $stmt = $mysql->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $count = $stmt->rowCount();

    if($count == 0){
        if($_POST["pw"] == $_POST["pw2"]){
            $stmt = $mysql->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $hash = password_hash($_POST["pw"], PASSWORD_BCRYPT);            
            $stmt->execute([$_POST['username'], $_POST['email'], $hash]);
            echo "<div class='container'><hr><h1>Dein Account wurde angelegt</h1><hr><br><br>";
            echo "<a href='login.php'>Zum Login</a></div>";
            include ('./template/footer.php');
            exit;
            } else {
                echo "Die Passwörter stimmen nicht überein!";
            }
        }else {
            echo "Der Username ist bereits vergeben";
        }
}
?>

<div class="container">
<hr>
<h1>Geben Sie hier Ihre Daten ein um ein neues Konto zu registrieren: </h1>
<hr>
<br>
<h2>Account erstellen</h2>

<form action="register.php" method="post">
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="email" name="email" placeholder="E-Mail" required><br>
    <input type="password" name ="pw" placeholder="Passwort" required><br>
    <input type="password" name ="pw2" placeholder="Passwort wiederholen" required><br>
    <button type="submit"  name="submit">Register</button><br>
       
</form>
<br>
<a href="login.php">Bereits registriert?</a>
</div>
<?php include ('./template/footer.php');?>
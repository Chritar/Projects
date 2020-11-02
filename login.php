<?php
require_once "pdo.php";

if ( isset($_POST['cancel'])) {
    header("Location: index.php");
    return;
}

$failure = false;

$salt = 'XyZzy12*_';

if ( isset($_POST['who']) && isset($_POST['pass']) && isset($_POST['einloggen'])  ) {

    $pass = htmlentities($_POST['pass']);
    $who = htmlentities($_POST['who']);

        $sql = "SELECT Passwort FROM users 
            WHERE Benutzername = :em";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':em' => $who));
        $row = $stmt->fetch(PDO::FETCH_BOTH);
        
        $check = hash('md5', $salt.$_POST['pass']);

        if ($row["Passwort"] === $check) {
            error_log("Login success ".$_POST['who']);
            header("Location: auto.php?name=".urlencode($who));
        } else { 
            error_log("Login fail ".$_POST['who']." $check");
            echo("<p> Falsches Password </p>");
        }}

if ( isset($_POST['who']) && isset($_POST['pass']) && isset($_POST['registrieren']) ) {

    $pass = htmlentities($_POST['pass']);
    $who = htmlentities($_POST['who']);
    $check = hash('md5', $salt.$_POST['pass']);

    $sql = "INSERT INTO users ( Benutzername, Passwort ) Values (:em, :pw)";


    $stmt = $pdo->prepare($sql);
    $stmt ->execute(array(
        ':em' => $who,
        ':pw' => $check));
    header("Location: auto.php?name=".urlencode($who));
}
?>

<!doctype html>
<html>
<head>

<title>Chris's Login Page</title>

<?php 
require_once "bootstrap.php";
?>

</head>
<body>
<div class="container">
<h1>Bitte logge dich ein</h1>

<?php
if ( $failure !== false ) {
    echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
}
?>

    <form method="POST">
        <label for="mail">Benutzername</label>
        <input type="email" name="who" id="email"><br/>
        <label for="userpassword">Passwort</label>
        <input type="password" name="pass" id="password"><br/>
        <input type="submit" name="einloggen" value="Einloggen">
        <input type="submit"  name="registrieren" value="Registrieren">
        <input type="submit" name="cancel" value="Cancel">
    </form>

</div>
</body>

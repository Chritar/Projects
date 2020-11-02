<?php
require_once "pdo.php";

if ( ! isset($_GET['name']) || strlen($_GET['name']) < 1  ) {
    die('Name parameter missing');
    }

if (isset($_POST['logout'])) {
    header("Location: index.php");
    }

if (isset($_POST['carname'])) {

    if (isset($_POST['carmileage']) && isset($_POST['manufactured']) && is_numeric($_POST['carmileage']) && is_numeric($_POST['manufactured'])) {
        
        $carname = $_POST['carname'];
        $sql = "SELECT 1 FROM autos WHERE make =?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$carname]); 
        $row = $stmt->fetch();

            if ($row === TRUE)  {
                echo("already exists");
            } else {

                $sql = "INSERT INTO autos (make, mileage, year)
                 VALUES (:carname, :carmileage, :manufactured)";

                 $stmt = $pdo->prepare($sql);
                 $stmt->execute(array(
                    ':carname' => $_POST['carname'],
                    ':carmileage' => $_POST['carmileage'],
                    ':manufactured' => $_POST['manufactured']));
                echo("erstellt");
            }

    } else {
        echo("Milage und Year müssen numerisch sein!");
        return;
    }

} else {
    echo("make is ein Pflichtfeld!");
    return;
}

$stmt = $pdo->query("SELECT auto_id, make, year, mileage FROM autos");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!doctype html>
<html>
<head>
<title>Christians Autopage</title>
<?php require_once "bootstrap.php"; ?>
</head>

<body>
    <div class="container">
        <h1>Autos</h1>

        <form method="POST">
        <label for="cars">Make</label>
        <input type="text" name="carname"></br>
        <label for="usage">Milage</label>
        <input type="number" name="carmileage"></br>
        <label for="birth">Year</label>
        <input type="number" name="manufactured"></br>
        <input type="submit" name="add" value="Add">
        <input type="submit" name="logout" value="Logout">
    </div>
    <table border="1">
        <?php
             echo "<tr><td>";
             echo('auto_id');
             echo("</td><td>");
             echo('make');
             echo("</td><td>");
             echo('year');
             echo("</td><td>");
             echo('mileage');
             echo("</td><tr>");
            foreach ( $rows as $row ) {
            echo "<tr><td>";
            echo($row['auto_id']);
            echo("</td><td>");
            echo($row['make']);
            echo("</td><td>");
            echo($row['year']);
            echo("</td><td>");
            echo($row['mileage']);
            echo("</td><tr>");}
        ?>
    </table>
</body>
</html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php

require 'db.php';


if(isset($_GET['id'])){

    $stmt = $pdo->prepare('SELECT * FROM employees WHERE id = :id');
    $stmt->execute(['id' => $_GET['id']]);

    $row = $stmt->fetch();

}

?>
    <header>
        <img src="logo_dunder_mifflin.png" alt="logo">

            <a href="index.php">Prodeje</a>
            <a href="vyrobky.php">Výrobky</a>
            <a href="employees.php">Zaměstnanci</a>
            <a href="statistika.php">Statistiky</a>
    </header>
    <section>
    <h1>ZAMĚSTNANCI</h1>
    <form class="DetailForm" action="" method="post">
    <a class="exitbutton" href="employees.php">Zpět</a>
    <label>
        Jméno: <br>
        <input type="text" class="DetailInput" name="Name" value="<?php echo isset($_GET['id']) ? $row['Name'] :  '' ?>" required> <br>
    </label>

    <label>
        Příjmení: <br> 
        <input type="text" class="DetailInput" name="Surname" value="<?php echo isset($_GET['id']) ? $row['Surname'] :  '' ?>" required> <br>
    </label>

    <label>
        E-mail: <br> 
        <input type="text" class="DetailInput" name="email" value="<?php echo isset($_GET['id']) ? $row['email'] :  '' ?>" required> <br>
    </label>

    <label>
        Datum narození: <br> 
        <input type="date" class="DetailInput" name="Birth" value="<?php echo isset($_GET['id']) ? $row['Birth'] :  '' ?>"required> <br> 
    </label>

    <label>
        Pozice: <br> 
        <input type="text" class="DetailInput" name="Position" value="<?php echo isset($_GET['id']) ? $row['Position'] :  '' ?>" required> <br> 
    </label> <br>
    <input type="submit" class="formbutton"  value="Potvrdit">

</form>

</section>
<?php



    if(isset($_POST) && !empty($_POST)){



        $Name = trim($_POST['Name']);
        $Surname = trim($_POST['Surname']);
        $email = trim($_POST['email']);
        $Birth = trim($_POST['Birth']);
        $Position = trim($_POST['Position']);



        if(strlen($Name) == 0 || strlen($Surname) == 0 || strlen($Birth) == 0 || strlen($Position) == 0 || strlen($email) == 0 ){
            echo "Pole nesmí obsahovat jen mezery";
            die();
        }

        if(empty($_POST['Name']) || empty($_POST['Surname']) || empty($_POST['Birth']) || empty($_POST['Position']) || empty($_POST['email']) ){
            echo "Pole musí být vyplněna";
            die();
        }

        if(!isset($_GET['id'])){

            $stmt = $pdo->prepare("INSERT INTO employees  (Name, Surname,email, Birth, Position) 
            VALUES (:Name, :Surname,:email, :Birth, :Position)");

            $stmt->execute([
            'Name' => $_POST['Name'],
            'Surname' => $_POST['Surname'],
            'email'=>$_POST['email'],
            'Birth' => $_POST['Birth'],
            'Position'=>$_POST['Position']
            ]);
        }else{

            $stmt = $pdo->prepare("UPDATE employees SET Name = :Name, Surname = :Surname,email = :email, Birth = :Birth, Position = :Position WHERE id  = :id");

            $stmt->execute([
            'Name' => $_POST['Name'],
            'Surname' => $_POST['Surname'],
            'email'=>$_POST['email'],
            'Birth' => $_POST['Birth'],
            'Position' => $_POST['Position'],
            'id' => $_GET['id']
            ]);

        }

        header('Location: employees.php');
     
    }


?>
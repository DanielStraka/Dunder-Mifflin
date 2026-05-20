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

    $stmt = $pdo->prepare('SELECT * FROM sales WHERE id = :id');
    $stmt->execute(['id' => $_GET['id']]);

    $row = $stmt->fetch();

}



    $stmt = $pdo->prepare('SELECT * FROM products');
    $stmt->execute();

    $DataProducts = $stmt->fetchAll();




    $stmt = $pdo->prepare('SELECT * FROM employees');
    $stmt->execute();

    $DataEmployees = $stmt->fetchAll();




   
?>
    <header>
        <img src="logo_dunder_mifflin.png" alt="logo">

            <a href="index.php">Prodeje</a>
            <a href="vyrobky.php">Výrobky</a>
            <a href="employees.php">Zaměstnanci</a>
            <a href="statistika.php">Statistiky</a>
    </header>

<section>

<h1>PRODEJE</h1>
    <form class="DetailForm"  action="" method="post">
    <a class="exitbutton" href="index.php">Zpět</a>    

    <label for="idProduct">Produkt:</label>
    <select name="idProduct"><br>
    <?php foreach ($DataProducts as $key => $value):?>
    <option value="<?= $value['id'] ?>"><?= $value['ProductName'] ?></option>
    <?php endforeach;?>
    </select><br>
    
    <label for="idEmployee">Zaměstnanec:</label>
    <select name="idEmployee" >
    <?php foreach ($DataEmployees as $key => $value):?>
    <option value="<?= $value['id'] ?>"><?= $value['Name'],' ', $value['Surname'] ?></option>
    <?php endforeach;?> 
    </select> <br>


    <label>
        Datum: <br>
        <input class="DetailInput" type="date" name="Date" value="<?php echo isset($_GET['id']) ? $row['Date'] :  '' ?>"required> <br>
    </label>

    <label>
        Počet:<br>
        <input class="DetailInput"  type="number"  name="Quantity" value="<?php echo isset($_GET['id']) ? $row['Quantity'] :  '' ?>" required>    <br>
    </label>


    <fieldset required >
        <legend>Sleva 10%:</legend>
        <input type="radio" name="Sale" value="1">Ano<br>
        <input type="radio" name="Sale" value="null">Ne<br>

    </fieldset>
    
    <input class="formbutton" type="submit" value="Potvrdit">

</form>


</section>
</body>
<?php



    if(isset($_POST) && !empty($_POST)){


        
        @$idProduct = trim($_POST['idProduct']);
        @$idEmployee = trim($_POST['idEmployee']);
        $Quantity = trim($_POST['Quantity']);
        $Date = trim($_POST['Date']);
        @$Sale=$_POST['Sale'];

        if(strlen($Quantity) == 0 || strlen($Date) == 0){
            echo "Pole nesmí obsahovat jen mezery";
            die();
        }

        if(empty($_POST['Quantity']) || empty($_POST['Date'])){
            echo "Pole musí být vyplněna";
            die();
        }

        if(!isset($_GET['id'])){

            $stmt = $pdo->prepare("INSERT INTO sales  (Quantity, Date, idProduct, idEmployee, Sale) 
            VALUES (:Quantity, :Date, '$idProduct', '$idEmployee', '$Sale')");

            $stmt->execute([
            'Quantity' => $_POST['Quantity'],
            'Date' => $_POST['Date'],
            ]);
        }else{

            $stmt = $pdo->prepare("UPDATE sales SET Quantity = :Quantity, Date = :Date, idProduct = $idProduct , idEmployee = $idEmployee , Sale = $Sale  WHERE id  = :id");

            $stmt->execute([
                'Quantity' => $_POST['Quantity'],
                'Date' => $_POST['Date'],
                'id' => $_GET['id']
            ]);

        }


        header('Location: index.php');
    }


?>
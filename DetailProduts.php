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

    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id');
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
        <h1>VÝROBKY</h1>

    <form class="DetailForm" action="" method="post">
    <a class="exitbutton" href="vyrobky.php">Zpět</a>
    <label for="Category">Kategorie:</label>
    <select name="Category">
    <option value="elektronika">elektronika</option>
    <option value="papír">papír</option>
    <option value="psací potřeby">psací potřeby</option>
    </select> <br>

    <label>
        Název Výrobku: <br>
        <input type="text" class="DetailInput" name="ProductName" value="<?php echo isset($_GET['id']) ? $row['ProductName'] :  '' ?>" required> <br>
    </label>


    <label>
        Cena: <br>
        <input class="DetailInput" type="number" name="Price" value="<?php echo isset($_GET['id']) ? $row['Price'] :  ''  ?>"  min="1" required> <br>
    </label>

        Popis Výrobku: <br>
        <input class="DetailInput" type="text" name="Description" size="50" value="<?php echo isset($_GET['id']) ? $row['Description'] :  '' ?>" required> <br>
        <input class="formbutton" type="submit" value="Potvrdit">

</form>
</section>

<?php



    if(isset($_POST) && !empty($_POST)){



        $ProductName = trim($_POST['ProductName']);
        $Price = trim($_POST['Price']);
        $Description = trim($_POST['Description']);
        @$Category=$_POST['Category'];



        if(strlen($ProductName) == 0 || strlen($Price) == 0 || strlen($Description) == 0){
            echo "Pole nesmí obsahovat jen mezery";
            die();
        }

        if(empty($_POST['ProductName']) || empty($_POST['Price']) || empty($_POST['Description'])){
            echo "Pole musí být vyplněna";
            die();
        }

        if(!isset($_GET['id'])){

            $stmt = $pdo->prepare("INSERT INTO products  (ProductName, Price, Description, Category) 
            VALUES (:ProductName, :Price, :Description, '$Category')");

            $stmt->execute([
            'ProductName' => $_POST['ProductName'],
            'Price' => $_POST['Price'],
            'Description' => $_POST['Description'],
            ]);
        }else{

            $stmt = $pdo->prepare("UPDATE products SET ProductName = :ProductName, Price = :Price, Description = :Description, Category = '$Category'  WHERE id  = :nevim");

            $stmt->execute([
            'ProductName' => $_POST['ProductName'],
            'Price' => $_POST['Price'],
            'Description' => $_POST['Description'],
            'nevim' => $_GET['id']
            ]);

        }


        header('Location: vyrobky.php');
    }


?>
</body>
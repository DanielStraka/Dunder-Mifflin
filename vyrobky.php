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


    if(isset($_GET['search']))
        $search = $_GET['search'];

    

    if(!empty($search)){
        $stmt = $pdo->prepare('SELECT * FROM Products
        WHERE  ProductName LIKE "%" :ProductName "%" 
        OR Category LIKE "%" :Category "%"
        OR Price LIKE "%" :Price "%"
        ');

    $stmt->execute(['ProductName' => $search, 'Category' => $search, 'Price' => $search]);
    }else{
        $stmt = $pdo->prepare('SELECT * FROM Products');
        $stmt->execute();
    }
   
    

    $data = $stmt->fetchAll();

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
        <form class='hledat' action="" method="get">
        <input class='inputsearch' type="text" name="search">
        <input class='search'type="submit" value="Vyhledat">
        </form>
        <table>
    <thead>
        <tr>
            <th>Název</th>
            <th>Kategorie</th>
            <th>JednotkováCena</th>
            <th>Upravit</th>
            <th>Smazat</th>
        </tr>
    </thead>
    <tbody>

        <?php foreach ($data as $key => $value):?>
            <tr>
                <td> <?= $value['ProductName'] ?></td>
                <td><?= $value['Category'] ?></td>
                <td><?= $value['Price'] ?></td>
                <td><a href="DetailProduts.php?id=<?= $value['id'] ?>" class="detail">Upravit</a></td>
                <td><a href="deleteP.php?id=<?= $value['id'] ?>" class="delete">Smazat</a></td>
            </tr>
        <?php endforeach;?>

      

    </tbody>
</table>
        <a class ='button'href="DetailProduts.php">VYTVOŘIT PRODUKT</a>
    </section>
</body>
</html>
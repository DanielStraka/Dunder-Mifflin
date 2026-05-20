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
        $stmt = $pdo->prepare('SELECT * FROM Sales s inner join Products p on p.id = s.idProduct inner join employees e on e.id = s.idEmployee
        WHERE  p.ProductName LIKE "%" :ProductName "%" 
        OR e.Name LIKE "%" :Name "%"
        OR e.Surname LIKE "%" :Surname "%"
        OR s.Date LIKE "%" :Date "%"
        ');

    $stmt->execute(['ProductName' => $search, 'Name' => $search, 'Surname' => $search, 'Date' => $search]);
    }else{
        $stmt = $pdo->prepare('SELECT s.id,s.idEmployee,s.idProduct,s.Quantity,s.FinalPrice,s.Date,s.Sale,p.ProductName,e.Name,e.Surname,p.Price FROM Sales s inner join Products p on p.id = s.idProduct inner join employees e on e.id = s.idEmployee order by s.id');
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
        <h1>PRODEJE</h1>
        <form class='hledat' action="" method="get">
        <input class='inputsearch' type="text" name="search">
        <input class='search'type="submit" value="Vyhledat">
        </form>
        <table>
            <thead>
                <tr>
                    <th>ID objednávky</th>
                    <th>Prodejce</th>
                    <th>Výrobek</th>
                    <th>Množství</th>
                    <th>Datum</th>
                    <th>Cena</th>
                    <th>Sleva</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>

            <?php foreach ($data as $key => $value):?>
                <tr>
                    <td> <?= $value['id'] ?></td>
                    <td><?= $value['Name'],' ', $value['Surname'] ?></td>
                    <td><?= $value['ProductName'] ?></td>
                    <td><?= $value['Quantity'] ?></td>            
                    <td><?= $value['Date'] ?></td>
                    <?php if ($value['Sale'] == 1):?>
                        <?php $final = ($value['Quantity'] * $value['Price'])*0.9?>
                        <td><?= $final?></td>
                        <td>Ano</td>
                    <?php endif;?>
                    <?php if ($value['Sale'] != 1):?>
                        <?php $final = $value['Quantity'] * $value['Price']?>
                        <td><?=$final ?></td>
                        <td>Ne</td>
                    <?php endif;?>    
                    <td><a href="DetailSales.php?id=<?= $value['id'] ?>" class="detail">Detail</a></td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
        <a class ='button'href="DetailSales.php">VYTVOŘIT PRODEJ</a>
    </section>
</body>
<?php 
?>
</html>
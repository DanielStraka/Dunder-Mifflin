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
        $stmt = $pdo->prepare('SELECT * FROM employees
        WHERE  Name LIKE "%" :Name "%" 
        OR Surname LIKE "%" :Surname "%"
        OR email LIKE "%" :email "%"
        OR Birth LIKE "%" :Birth "%"
        OR Position LIKE "%" :Position "%"
        ');

    $stmt->execute(['Name' => $search, 'Surname' => $search,'email' => $search, 'Birth' => $search, 'Position' => $search]);
    }else{
        $stmt = $pdo->prepare('SELECT * FROM employees');
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
        <h1 class="h11">ZAMĚSTNANCI</h1>
        <form class='hledat' action="" method="get">
        <input class='inputsearch' type="text" name="search">
        <input class='search'type="submit" value="Vyhledat">
        </form>
        <table>
    <thead>
        <tr>
            <th>Jméno</th>
            <th>Příjmení</th>
            <th>E-mail</th>
            <th>Dat. Nar.</th>
            <th>Pozice</th>
            <th>Upravit</th>
            <th>Smazat</th>
        </tr>
    </thead>
    <tbody>

        <?php foreach ($data as $key => $value):?>
            <tr>
                <td> <?= $value['Name'] ?></td>
                <td><?= $value['Surname'] ?></td>
                <td><?= $value['email'] ?></td>
                <td><?= $value['Birth'] ?></td>
                <td><?= $value['Position'] ?></td>
                <td><a href="DetailEmployees.php?id=<?= $value['id'] ?>" class="detail">Upravit</a></td>
                <td><a href="deleteE.php?id=<?= $value['id'] ?>" class="delete">Smazat</a></td>
            </tr>
        <?php endforeach;?>

      

    </tbody>
</table>
        <a class ='button'href="DetailEmployees.php">VYTVOŘIT ZAMĚSTNANCE</a>
    </section>
</body>
</html>
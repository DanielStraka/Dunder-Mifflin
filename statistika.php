<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
 
    </style>
</head>
<body>
<?php 
        require 'db.php';
        $stmt = $pdo->prepare('SELECT s.Sale,s.Quantity,p.Price,s.idEmployee FROM Sales s inner join Products p on p.id = s.idProduct inner join employees e on e.id = s.idEmployee');
        $stmt->execute();

        $data = $stmt->fetchAll();
        $final = 0;
        

        $stmt = $pdo->prepare('SELECT idEmployee, count(idEmployee) as val FROM Sales group by idEmployee order by val DESC Limit 1;');
        $stmt->execute();

        $BestEmployee = $stmt->fetch();


        $stmt = $pdo->prepare('SELECT id,Name,Surname FROM employees ');
        $stmt->execute();

        $IDemp = $stmt->fetchAll();


        $stmt = $pdo->prepare(' SELECT id FROM Sales where Date > now() - INTERVAL 14 day;');
        $stmt->execute();

        $Last14Days = $stmt->fetchAll();


        $stmt = $pdo->prepare(' SELECT id,count(Date) as datum FROM Sales where Date > now() - INTERVAL 7 day;');
        $stmt->execute();

        $Last7Graf = $stmt->fetchAll();
   

?>
    <header>
        <img src="logo_dunder_mifflin.png" alt="logo">

            <a href="index.php">Prodeje</a>
            <a href="vyrobky.php">Výrobky</a>
            <a href="employees.php">Zaměstnanci</a>
            <a href="statistika.php">Statistiky</a>
    </header>
    <section>
        <h1 class="h11">STATISTIKY</h1>
    <div class="DetailStats">


        <div class="CountPrice">

            <?php foreach ($data as $key => $value):?>
                <?php if ($value['Sale'] == 1):?>
                        <?php $finalAlmost = ($value['Quantity'] * $value['Price'])*0.9?>
                <?php endif;?>
                <?php if ($value['Sale'] == null):?>
                        <?php $finalAlmost = $value['Quantity'] * $value['Price']?>
                <?php endif;?> 
                    <?php $final = $final + $finalAlmost ?>
            <?php endforeach;?>    
            <?= "Celkový obrat: ". $final." Kč" ?><br>

        </div>

        



        <div class="bestemp">

            <?php foreach ($IDemp as $key => $value):?>
                <?php if ($value['id'] == $BestEmployee['idEmployee']):?>
                    <?= "Nejlepší Zaměstnanec: ". $value['Name']." ".$value['Surname']." s počtem objednávek: ".$BestEmployee['val'] ?> <br>
                <?php endif;?>
            <?php endforeach;?>          

        </div>

        


            <?= "Počet Objednávek za posledních 14 dní: ". count($Last14Days) ?>  

      
    </div> 


    </section>
</body>
</html>
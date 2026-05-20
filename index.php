<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prodeje — Dunder Mifflin</title>
    <script>if(localStorage.getItem('darkMode')==='1')document.documentElement.classList.add('dark');</script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
    require 'db.php';

    $search = $_GET['search'] ?? '';

    if (!empty($search)) {
        $stmt = $pdo->prepare('SELECT s.id,s.idEmployee,s.idProduct,s.Quantity,s.FinalPrice,s.Date,s.Sale,p.ProductName,e.Name,e.Surname,p.Price
            FROM Sales s
            INNER JOIN Products p ON p.id = s.idProduct
            INNER JOIN employees e ON e.id = s.idEmployee
            WHERE p.ProductName LIKE :q
               OR e.Name LIKE :q
               OR e.Surname LIKE :q
               OR s.Date LIKE :q
            ORDER BY s.id');
        $stmt->execute(['q' => '%' . $search . '%']);
    } else {
        $stmt = $pdo->prepare('SELECT s.id,s.idEmployee,s.idProduct,s.Quantity,s.FinalPrice,s.Date,s.Sale,p.ProductName,e.Name,e.Surname,p.Price
            FROM Sales s
            INNER JOIN Products p ON p.id = s.idProduct
            INNER JOIN employees e ON e.id = s.idEmployee
            ORDER BY s.id');
        $stmt->execute();
    }
    $data = $stmt->fetchAll();
?>
    <button id="darkToggle" title="Přepnout tmavý/světlý režim">🌙</button>
    <header>
        <img src="logo_dunder_mifflin.png" alt="logo">
        <a href="index.php">Prodeje</a>
        <a href="vyrobky.php">Výrobky</a>
        <a href="employees.php">Zaměstnanci</a>
        <a href="statistika.php">Statistiky</a>
    </header>
    <section>
        <h1>PRODEJE <span class="record-count" id="recordCount"></span></h1>
        <form class="hledat" action="" method="get">
            <input class="inputsearch" id="liveSearch" type="text" name="search"
                   value="<?= htmlspecialchars($search) ?>" placeholder="Hledat…">
            <input class="search" type="submit" value="Vyhledat">
        </form>
        <table>
            <thead>
                <tr>
                    <th data-sort>ID objednávky</th>
                    <th data-sort>Prodejce</th>
                    <th data-sort>Výrobek</th>
                    <th data-sort>Množství</th>
                    <th data-sort>Datum</th>
                    <th data-sort>Cena</th>
                    <th data-sort>Sleva</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($data as $value): ?>
                <tr>
                    <td><?= $value['id'] ?></td>
                    <td><?= htmlspecialchars($value['Name'] . ' ' . $value['Surname']) ?></td>
                    <td><?= htmlspecialchars($value['ProductName']) ?></td>
                    <td><?= $value['Quantity'] ?></td>
                    <td><?= $value['Date'] ?></td>
                    <?php if ($value['Sale'] == 1): ?>
                        <td><?= round($value['Quantity'] * $value['Price'] * 0.9, 2) ?></td>
                        <td>Ano</td>
                    <?php else: ?>
                        <td><?= round($value['Quantity'] * $value['Price'], 2) ?></td>
                        <td>Ne</td>
                    <?php endif; ?>
                    <td><a href="DetailSales.php?id=<?= $value['id'] ?>" class="detail">Detail</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <p id="noResults">Žádné výsledky nenalezeny.</p>
        <a class="button" href="DetailSales.php">VYTVOŘIT PRODEJ</a>
    </section>
    <div id="toast"></div>
    <script src="script.js"></script>
</body>
</html>

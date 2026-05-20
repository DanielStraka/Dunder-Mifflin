<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Výrobky — Dunder Mifflin</title>
    <script>if(localStorage.getItem('darkMode')==='1')document.documentElement.classList.add('dark');</script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
    require 'db.php';

    $search = $_GET['search'] ?? '';

    if (!empty($search)) {
        $stmt = $pdo->prepare('SELECT * FROM Products
            WHERE ProductName LIKE :q
               OR Category LIKE :q
               OR Price LIKE :q');
        $stmt->execute(['q' => '%' . $search . '%']);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM Products ORDER BY ProductName');
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
        <h1>VÝROBKY <span class="record-count" id="recordCount"></span></h1>
        <form class="hledat" action="" method="get">
            <input class="inputsearch" id="liveSearch" type="text" name="search"
                   value="<?= htmlspecialchars($search) ?>" placeholder="Hledat…">
            <input class="search" type="submit" value="Vyhledat">
        </form>
        <table>
            <thead>
                <tr>
                    <th data-sort>Název</th>
                    <th data-sort>Kategorie</th>
                    <th data-sort>Jednotková cena</th>
                    <th>Upravit</th>
                    <th>Smazat</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($data as $value): ?>
                <tr>
                    <td><?= htmlspecialchars($value['ProductName']) ?></td>
                    <td><?= htmlspecialchars($value['Category']) ?></td>
                    <td><?= $value['Price'] ?></td>
                    <td><a href="DetailProduts.php?id=<?= $value['id'] ?>" class="detail">Upravit</a></td>
                    <td><a href="deleteP.php?id=<?= $value['id'] ?>" class="delete"
                           onclick="return potvrditSmazani('<?= htmlspecialchars($value['ProductName'], ENT_QUOTES) ?>')">Smazat</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <p id="noResults">Žádné výsledky nenalezeny.</p>
        <a class="button" href="DetailProduts.php">VYTVOŘIT PRODUKT</a>
    </section>
    <div id="toast"></div>
    <script src="script.js"></script>
</body>
</html>

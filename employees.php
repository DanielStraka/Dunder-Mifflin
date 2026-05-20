<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zaměstnanci — Dunder Mifflin</title>
    <script>if(localStorage.getItem('darkMode')==='1')document.documentElement.classList.add('dark');</script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
    require 'db.php';

    $search = $_GET['search'] ?? '';

    if (!empty($search)) {
        $stmt = $pdo->prepare('SELECT * FROM employees
            WHERE Name LIKE :q
               OR Surname LIKE :q
               OR email LIKE :q
               OR Birth LIKE :q
               OR Position LIKE :q');
        $stmt->execute(['q' => '%' . $search . '%']);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM employees ORDER BY Surname, Name');
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
        <h1 class="h11">ZAMĚSTNANCI <span class="record-count" id="recordCount"></span></h1>
        <form class="hledat" action="" method="get">
            <input class="inputsearch" id="liveSearch" type="text" name="search"
                   value="<?= htmlspecialchars($search) ?>" placeholder="Hledat…">
            <input class="search" type="submit" value="Vyhledat">
        </form>
        <table>
            <thead>
                <tr>
                    <th data-sort>Jméno</th>
                    <th data-sort>Příjmení</th>
                    <th data-sort>E-mail</th>
                    <th data-sort>Dat. Nar.</th>
                    <th data-sort>Pozice</th>
                    <th>Upravit</th>
                    <th>Smazat</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($data as $value): ?>
                <tr>
                    <td><?= htmlspecialchars($value['Name']) ?></td>
                    <td><?= htmlspecialchars($value['Surname']) ?></td>
                    <td><?= htmlspecialchars($value['email']) ?></td>
                    <td><?= $value['Birth'] ?></td>
                    <td><?= htmlspecialchars($value['Position']) ?></td>
                    <td><a href="DetailEmployees.php?id=<?= $value['id'] ?>" class="detail">Upravit</a></td>
                    <td><a href="deleteE.php?id=<?= $value['id'] ?>" class="delete"
                           onclick="return potvrditSmazani('<?= htmlspecialchars($value['Name'] . ' ' . $value['Surname'], ENT_QUOTES) ?>')">Smazat</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <p id="noResults">Žádné výsledky nenalezeny.</p>
        <a class="button" href="DetailEmployees.php">VYTVOŘIT ZAMĚSTNANCE</a>
    </section>
    <div id="toast"></div>
    <script src="script.js"></script>
</body>
</html>

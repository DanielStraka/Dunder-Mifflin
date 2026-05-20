<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Výrobek — Dunder Mifflin</title>
    <script>if(localStorage.getItem('darkMode')==='1')document.documentElement.classList.add('dark');</script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
    require 'db.php';

    if (isset($_GET['id'])) {
        $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id');
        $stmt->execute(['id' => $_GET['id']]);
        $row = $stmt->fetch();
    }
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
        <h1>VÝROBKY</h1>
        <form class="DetailForm" action="" method="post">
            <a class="exitbutton" href="vyrobky.php">Zpět</a>
            <label for="Category">Kategorie:</label>
            <select name="Category">
                <option value="elektronika" <?= (isset($row) && $row['Category'] === 'elektronika') ? 'selected' : '' ?>>elektronika</option>
                <option value="papír"       <?= (isset($row) && $row['Category'] === 'papír')       ? 'selected' : '' ?>>papír</option>
                <option value="psací potřeby" <?= (isset($row) && $row['Category'] === 'psací potřeby') ? 'selected' : '' ?>>psací potřeby</option>
            </select><br>
            <label>
                Název výrobku: <br>
                <input type="text" class="DetailInput" name="ProductName"
                       value="<?= isset($row) ? htmlspecialchars($row['ProductName']) : '' ?>" required> <br>
            </label>
            <label>
                Cena: <br>
                <input class="DetailInput" type="number" name="Price"
                       value="<?= isset($row) ? $row['Price'] : '' ?>" min="1" required> <br>
            </label>
            Popis výrobku: <br>
            <input class="DetailInput" type="text" name="Description" size="50"
                   value="<?= isset($row) ? htmlspecialchars($row['Description']) : '' ?>" required> <br>
            <input class="formbutton" type="submit" value="Potvrdit">
        </form>
    </section>
    <div id="toast"></div>
    <script src="script.js"></script>
</body>
</html>
<?php
    if (isset($_POST) && !empty($_POST)) {
        $ProductName = trim($_POST['ProductName']);
        $Price       = trim($_POST['Price']);
        $Description = trim($_POST['Description']);
        $Category    = $_POST['Category'] ?? '';

        if (strlen($ProductName) == 0 || strlen($Price) == 0 || strlen($Description) == 0) {
            echo "Pole nesmí obsahovat jen mezery";
            die();
        }

        if (!isset($_GET['id'])) {
            $stmt = $pdo->prepare("INSERT INTO products (ProductName, Price, Description, Category)
                VALUES (:ProductName, :Price, :Description, :Category)");
            $stmt->execute([
                'ProductName' => $ProductName,
                'Price'       => $Price,
                'Description' => $Description,
                'Category'    => $Category,
            ]);
            $msg = 'Výrobek byl úspěšně přidán.';
        } else {
            $stmt = $pdo->prepare("UPDATE products
                SET ProductName = :ProductName, Price = :Price, Description = :Description, Category = :Category
                WHERE id = :id");
            $stmt->execute([
                'ProductName' => $ProductName,
                'Price'       => $Price,
                'Description' => $Description,
                'Category'    => $Category,
                'id'          => $_GET['id'],
            ]);
            $msg = 'Výrobek byl úspěšně uložen.';
        }

        header('Location: vyrobky.php?msg=' . urlencode($msg));
        exit;
    }
?>

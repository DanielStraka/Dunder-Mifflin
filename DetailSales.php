<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prodej — Dunder Mifflin</title>
    <script>if(localStorage.getItem('darkMode')==='1')document.documentElement.classList.add('dark');</script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
    require 'db.php';

    if (isset($_GET['id'])) {
        $stmt = $pdo->prepare('SELECT * FROM sales WHERE id = :id');
        $stmt->execute(['id' => $_GET['id']]);
        $row = $stmt->fetch();
    }

    $stmt = $pdo->prepare('SELECT * FROM products ORDER BY ProductName');
    $stmt->execute();
    $DataProducts = $stmt->fetchAll();

    $stmt = $pdo->prepare('SELECT * FROM employees ORDER BY Surname, Name');
    $stmt->execute();
    $DataEmployees = $stmt->fetchAll();
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
        <h1>PRODEJE</h1>
        <form class="DetailForm" action="" method="post">
            <a class="exitbutton" href="index.php">Zpět</a>

            <label for="idProduct">Produkt:</label>
            <select name="idProduct">
                <?php foreach ($DataProducts as $p): ?>
                    <option value="<?= $p['id'] ?>"
                        <?= (isset($row) && $row['idProduct'] == $p['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['ProductName']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br>

            <label for="idEmployee">Zaměstnanec:</label>
            <select name="idEmployee">
                <?php foreach ($DataEmployees as $e): ?>
                    <option value="<?= $e['id'] ?>"
                        <?= (isset($row) && $row['idEmployee'] == $e['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($e['Name'] . ' ' . $e['Surname']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br>

            <label>
                Datum: <br>
                <input class="DetailInput" type="date" name="Date"
                       value="<?= isset($row) ? $row['Date'] : '' ?>" required> <br>
            </label>
            <label>
                Počet: <br>
                <input class="DetailInput" type="number" name="Quantity"
                       value="<?= isset($row) ? $row['Quantity'] : '' ?>" min="1" required> <br>
            </label>

            <fieldset>
                <legend>Sleva 10%:</legend>
                <input type="radio" name="Sale" value="1"
                       <?= (isset($row) && $row['Sale'] == 1) ? 'checked' : '' ?>> Ano<br>
                <input type="radio" name="Sale" value="null"
                       <?= (!isset($row) || $row['Sale'] != 1) ? 'checked' : '' ?>> Ne<br>
            </fieldset>

            <input class="formbutton" type="submit" value="Potvrdit">
        </form>
    </section>
    <div id="toast"></div>
    <script src="script.js"></script>
</body>
</html>
<?php
    if (isset($_POST) && !empty($_POST)) {
        $idProduct  = (int)$_POST['idProduct'];
        $idEmployee = (int)$_POST['idEmployee'];
        $Quantity   = trim($_POST['Quantity']);
        $Date       = trim($_POST['Date']);
        $Sale       = $_POST['Sale'] ?? 'null';

        if (strlen($Quantity) == 0 || strlen($Date) == 0) {
            echo "Pole nesmí obsahovat jen mezery";
            die();
        }

        if (!isset($_GET['id'])) {
            $stmt = $pdo->prepare("INSERT INTO sales (Quantity, Date, idProduct, idEmployee, Sale)
                VALUES (:Quantity, :Date, :idProduct, :idEmployee, :Sale)");
            $stmt->execute([
                'Quantity'   => $Quantity,
                'Date'       => $Date,
                'idProduct'  => $idProduct,
                'idEmployee' => $idEmployee,
                'Sale'       => ($Sale === '1') ? 1 : null,
            ]);
            $msg = 'Prodej byl úspěšně přidán.';
        } else {
            $saleVal = ($Sale === '1') ? 1 : null;
            $stmt = $pdo->prepare("UPDATE sales
                SET Quantity = :Quantity, Date = :Date, idProduct = :idProduct,
                    idEmployee = :idEmployee, Sale = :Sale
                WHERE id = :id");
            $stmt->execute([
                'Quantity'   => $Quantity,
                'Date'       => $Date,
                'idProduct'  => $idProduct,
                'idEmployee' => $idEmployee,
                'Sale'       => $saleVal,
                'id'         => $_GET['id'],
            ]);
            $msg = 'Prodej byl úspěšně uložen.';
        }

        header('Location: index.php?msg=' . urlencode($msg));
        exit;
    }
?>

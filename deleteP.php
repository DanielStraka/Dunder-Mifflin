<?php
    require 'db.php';

    if (isset($_GET['id'])) {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
        $stmt->execute(['id' => $_GET['id']]);

        header('Location: vyrobky.php?msg=' . urlencode('Výrobek byl úspěšně smazán.'));
        exit;
    }

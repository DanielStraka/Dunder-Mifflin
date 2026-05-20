<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiky — Dunder Mifflin</title>
    <script>if(localStorage.getItem('darkMode')==='1')document.documentElement.classList.add('dark');</script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
    require 'db.php';

    // Celkový obrat
    $stmt = $pdo->prepare('SELECT s.Sale, s.Quantity, p.Price
        FROM Sales s
        INNER JOIN Products p ON p.id = s.idProduct');
    $stmt->execute();
    $allSales = $stmt->fetchAll();
    $celkovyObrat = 0;
    foreach ($allSales as $row) {
        $celkovyObrat += $row['Quantity'] * $row['Price'] * ($row['Sale'] == 1 ? 0.9 : 1);
    }

    // Nejlepší zaměstnanec
    $stmt = $pdo->prepare('SELECT idEmployee, COUNT(idEmployee) AS val FROM Sales GROUP BY idEmployee ORDER BY val DESC LIMIT 1');
    $stmt->execute();
    $bestEmp = $stmt->fetch();

    $stmt = $pdo->prepare('SELECT id, Name, Surname FROM employees');
    $stmt->execute();
    $employees = $stmt->fetchAll();

    // Počet objednávek za posledních 14 dní
    $stmt = $pdo->prepare('SELECT id FROM Sales WHERE Date > NOW() - INTERVAL 14 DAY');
    $stmt->execute();
    $last14Days = $stmt->fetchAll();

    // Data pro graf — prodeje po dnech za posledních 7 dní
    $stmt = $pdo->prepare("
        SELECT DATE(s.Date) AS den, COUNT(*) AS pocet,
               ROUND(SUM(s.Quantity * p.Price * IF(s.Sale = 1, 0.9, 1)), 2) AS obrat
        FROM Sales s
        INNER JOIN Products p ON p.id = s.idProduct
        WHERE DATE(s.Date) >= CURDATE() - INTERVAL 6 DAY
        GROUP BY DATE(s.Date)
        ORDER BY DATE(s.Date)
    ");
    $stmt->execute();
    $salesByDay = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Sestavení kompletního 7denního pole (dny bez prodejů = 0)
    $salesMap = [];
    foreach ($salesByDay as $row) {
        $salesMap[$row['den']] = $row;
    }
    $chartLabels = [];
    $chartPocet  = [];
    $chartObrat  = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-{$i} days"));
        $chartLabels[] = date('j.n.', strtotime($date));
        if (isset($salesMap[$date])) {
            $chartPocet[] = (int)$salesMap[$date]['pocet'];
            $chartObrat[] = (float)$salesMap[$date]['obrat'];
        } else {
            $chartPocet[] = 0;
            $chartObrat[] = 0.0;
        }
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
        <h1 class="h11">STATISTIKY</h1>
        <div class="DetailStats">

            <div class="CountPrice">
                <?= 'Celkový obrat: ' . number_format($celkovyObrat, 2, ',', ' ') . ' Kč' ?>
            </div>

            <div class="bestemp">
                <?php foreach ($employees as $emp): ?>
                    <?php if ($emp['id'] == $bestEmp['idEmployee']): ?>
                        <?= 'Nejlepší zaměstnanec: ' . htmlspecialchars($emp['Name'] . ' ' . $emp['Surname'])
                            . ' s počtem objednávek: ' . $bestEmp['val'] ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <?= 'Počet objednávek za posledních 14 dní: ' . count($last14Days) ?>

            <div class="chart-container">
                <h2>Prodeje za posledních 7 dní</h2>
                <canvas id="salesChart" height="100"></canvas>
            </div>

        </div>
    </section>
    <div id="toast"></div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    (function () {
        var isDark = document.documentElement.classList.contains('dark');
        var textColor  = isDark ? '#ddd'    : '#111';
        var gridColor  = isDark ? '#2d3a5e' : '#ddd';
        var barColor   = isDark ? 'rgba(77,166,255,0.7)' : 'rgba(0,0,0,0.7)';
        var borderClr  = isDark ? '#4da6ff' : '#000';

        Chart.defaults.color = textColor;

        new Chart(document.getElementById('salesChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($chartLabels) ?>,
                datasets: [{
                    label: 'Počet prodejů',
                    data: <?= json_encode($chartPocet) ?>,
                    backgroundColor: barColor,
                    borderColor: borderClr,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { labels: { color: textColor } }
                },
                scales: {
                    x: { ticks: { color: textColor }, grid: { color: gridColor } },
                    y: {
                        beginAtZero: true,
                        ticks: { color: textColor, stepSize: 1 },
                        grid: { color: gridColor }
                    }
                }
            }
        });
    })();
    </script>
    <script src="script.js"></script>
</body>
</html>

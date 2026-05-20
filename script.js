// Dark mode toggle
(function () {
    var isDark = document.documentElement.classList.contains('dark');
    var btn = document.getElementById('darkToggle');
    if (btn) {
        btn.textContent = isDark ? '☀️' : '🌙';
        btn.addEventListener('click', function () {
            isDark = document.documentElement.classList.toggle('dark');
            btn.textContent = isDark ? '☀️' : '🌙';
            localStorage.setItem('darkMode', isDark ? '1' : '0');
        });
    }
})();

// Active nav link
(function () {
    var page = window.location.pathname.split('/').pop() || 'index.php';
    document.querySelectorAll('header a').forEach(function (a) {
        if (a.getAttribute('href') === page) {
            a.classList.add('active');
        }
    });
})();

// Toast notification from ?msg=...
(function () {
    var toast = document.getElementById('toast');
    if (!toast) return;
    var params = new URLSearchParams(window.location.search);
    var msg = params.get('msg');
    if (!msg) return;
    toast.textContent = msg;
    toast.classList.add('show');
    setTimeout(function () { toast.classList.remove('show'); }, 3500);
})();

// Live search + record counter
(function () {
    var input = document.getElementById('liveSearch');
    if (!input) return;
    var tbody = document.querySelector('table tbody');
    if (!tbody) return;
    var rows = Array.from(tbody.querySelectorAll('tr'));
    var counter = document.getElementById('recordCount');
    var noResults = document.getElementById('noResults');

    function normalize(str) {
        return str.toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '');
    }

    if (counter) counter.textContent = rows.length;

    input.addEventListener('input', function () {
        var q = normalize(this.value);
        var visible = 0;
        rows.forEach(function (row) {
            var match = normalize(row.textContent).includes(q);
            row.style.display = match ? '' : 'none';
            if (match) visible++;
        });
        if (counter) counter.textContent = visible;
        if (noResults) noResults.style.display = (visible === 0 && q.length > 0) ? 'block' : 'none';
    });
})();

// Table sorting with Czech locale
(function () {
    var table = document.querySelector('table');
    if (!table) return;
    var tbody = table.querySelector('tbody');
    if (!tbody) return;
    var headers = table.querySelectorAll('thead th[data-sort]');

    headers.forEach(function (th) {
        th.dataset.dir = 'asc';
        var icon = document.createElement('span');
        icon.className = 'sort-icon';
        th.appendChild(icon);

        th.addEventListener('click', function () {
            var dir = (th.dataset.dir === 'asc') ? 1 : -1;
            th.dataset.dir = (dir === 1) ? 'desc' : 'asc';

            headers.forEach(function (h) {
                var ic = h.querySelector('.sort-icon');
                if (ic) ic.textContent = '';
            });
            icon.textContent = (dir === 1) ? ' ▲' : ' ▼';

            var allTh = Array.from(table.querySelectorAll('thead th'));
            var colIdx = allTh.indexOf(th);

            var rows = Array.from(tbody.querySelectorAll('tr'));
            rows.sort(function (a, b) {
                var aText = a.cells[colIdx] ? a.cells[colIdx].textContent.trim() : '';
                var bText = b.cells[colIdx] ? b.cells[colIdx].textContent.trim() : '';
                var aNum = parseFloat(aText.replace(/\s/g, '').replace(',', '.'));
                var bNum = parseFloat(bText.replace(/\s/g, '').replace(',', '.'));
                if (!isNaN(aNum) && !isNaN(bNum)) return (aNum - bNum) * dir;
                return aText.localeCompare(bText, 'cs') * dir;
            });
            rows.forEach(function (r) { tbody.appendChild(r); });
        });
    });
})();

// Row animations (fade + slide)
(function () {
    var rows = document.querySelectorAll('table tbody tr');
    rows.forEach(function (row, i) {
        row.style.opacity = '0';
        row.style.transform = 'translateY(12px)';
        row.style.transition = 'opacity 0.3s ease ' + (i * 0.04) + 's, transform 0.3s ease ' + (i * 0.04) + 's';
        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            });
        });
    });
})();

// Delete confirmation
function potvrditSmazani(name) {
    var msg = 'Opravdu chcete smazat záznam';
    if (name) msg += ' „' + name + '“';
    msg += '?';
    return confirm(msg);
}

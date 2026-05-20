# Dunder Mifflin — Systém správy prodejů

> Závěrečný projekt 3. ročníku střední školy

Webová aplikace pro správu prodejů, výrobků a zaměstnanců fiktivní papírenské společnosti Dunder Mifflin z amerického seriálu The Office.

---

## Funkce

- **Prodeje** — přehled všech objednávek s informacemi o produktu, zaměstnanci, množství, ceně a slevě
- **Výrobky** — správa katalogu produktů (kategorie, cena, popis)
- **Zaměstnanci** — správa zaměstnanců (jméno, e-mail, datum narození, pozice)
- **Statistiky** — celkový obrat, nejlepší zaměstnanec, počet objednávek za 14 dní, graf prodejů za posledních 7 dní

### JavaScript funkce
- **Live search** — filtrování tabulky při psaní bez odeslání formuláře, zobrazení zprávy „Žádné výsledky nenalezeny"
- **Potvrzení smazání** — `confirm()` dialog před odstraněním záznamu
- **Řazení sloupců** — kliknutím na záhlaví sloupce (vzestupně/sestupně), česká lokalizace
- **Aktivní odkaz v navigaci** — vizuální označení aktuální stránky
- **Animace řádků** — plynulé načítání záznamů (fade + slide)
- **Toast notifikace** — zpráva po přesměrování z formuláře přes URL parametr `?msg=`
- **Počítadlo záznamů** — zobrazení počtu záznamů u nadpisu, aktualizuje se při live search
- **Dark mode** — tlačítko 🌙 v pravém horním rohu, preference uložena v `localStorage`

---

## Technologie

| Vrstva    | Technologie          |
|-----------|----------------------|
| Frontend  | HTML, CSS, JavaScript |
| Backend   | PHP 8                |
| Databáze  | MySQL                |
| Graf      | Chart.js 4.4         |

---

## Struktura projektu

```
├── index.php             # Přehled prodejů
├── employees.php         # Seznam zaměstnanců
├── vyrobky.php           # Seznam výrobků
├── statistika.php        # Statistiky a graf
├── DetailEmployees.php   # Formulář zaměstnance (create/edit)
├── DetailProduts.php     # Formulář výrobku (create/edit)
├── DetailSales.php       # Formulář prodeje (create/edit)
├── deleteE.php           # Smazání zaměstnance
├── deleteP.php           # Smazání výrobku
├── db.php                # Připojení k databázi (PDO)
├── style.css             # Styly (responzivní, dark mode)
├── script.js             # JavaScript funkce
└── papercompany.sql      # Databázový dump se vzorovými daty
```

---

## Databázové tabulky

- **employees** — `id, Name, Surname, email, Birth, Position`
- **products** — `id, ProductName, Category, Price, Description`
- **sales** — `id, idProduct, idEmployee, Quantity, FinalPrice, Date, Sale`

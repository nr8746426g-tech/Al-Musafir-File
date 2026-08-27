# نظام عقود تأجير السيارات — المسافر لتأجير السيارات

موقع ويب بسيط (PHP + MySQL) يعيد إنتاج نموذج "عقد تأجير سيارات" الخاص بشركة
المسافر لتأجير السيارات كصفحة قابلة للتعبئة، يحفظ العقود في قاعدة بيانات
MySQL، ويعرض كل عقد بصيغة جاهزة للطباعة والتوقيع.

## Car Rental Contract System — Al Musafir for Car Rental

A small PHP + MySQL web app that reproduces the company's bilingual
(Arabic/English) "Car Rental Agreement" as a fillable web form, stores
every contract in MySQL, and renders a print-ready copy of each one.

## المتطلبات / Requirements

- PHP 8.1+ with the `pdo_mysql` extension
- MySQL 5.7+ / MariaDB 10.3+
- Any web server (Apache, Nginx+PHP-FPM) or just PHP's built-in server

## الإعداد / Setup

1. **أنشئ قاعدة البيانات والجدول / Create the database and table:**

   ```bash
   mysql -u root -p < schema.sql
   ```

   This creates the `al_musafir_contracts` database and the `contracts` table.
   ⚠️ **`schema.sql` drops and recreates the `contracts` table** — re-running it
   after a redesign erases any contracts saved under the old structure.

2. **اضبط بيانات الاتصال / Configure the DB connection** via environment
   variables (recommended) or by editing `config.php` directly:

   ```bash
   export DB_HOST=127.0.0.1
   export DB_PORT=3306
   export DB_NAME=al_musafir_contracts
   export DB_USER=root
   export DB_PASS=your_password
   ```

3. **شغّل الموقع / Run it:**

   ```bash
   php -S localhost:8000
   ```

   Then open <http://localhost:8000/> — or point Apache/Nginx's document
   root at this folder for a production deployment.

## الصفحات / Pages

| Page | Purpose |
|---|---|
| `index.php` | Landing page — links to a new contract or the saved list |
| `form.php` | Fillable data-entry form for all contract fields (add `?id=` to edit an existing contract) |
| `save.php` | Validates the submission and inserts/updates the `contracts` table |
| `view.php?id=` | Renders the saved contract in the original bilingual layout with a **Print** button |
| `contracts.php` | Lists/searches saved contracts, with links to view, edit, or delete |
| `delete.php` | Deletes a contract (POST only) |

## نموذج العقد / Contract template

هذا الموقع يطابق نموذج "CAR RENTAL AGREEMENT / عقد إيجار سيارة" (10 بنود)
الذي زوّدتنا به الشركة: الطرف الأول = المستأجر (العميل)، الطرف الثاني =
المؤجر (شركة المسافر، ثابت). البنود 4–10 نصوص ثابتة بدون حقول؛ الحقول
القابلة للتعبئة موجودة فقط بالبنود 1–3 (السيارة، مدة الإيجار، التأمين).

This site matches the "CAR RENTAL AGREEMENT / عقد إيجار سيارة" (10
articles) template supplied by the company: First Party = Lessee (the
customer), Second Party = Lessor (Al Musafir, fixed). Articles 4–10 are
fixed boilerplate text with no fields; fillable fields only appear in
Articles 1–3 (vehicle, rental period, deposit).

## كيف يعمل / How it works

- كل حقول العقد معرّفة في مكان واحد فقط: `fields.php`. هذا الملف يحدد نوع
  كل حقل (نص/تاريخ/وقت/رقم/قائمة) ويُستخدم في التحقق من صحة البيانات والحفظ.
- `save.php` يتحقق من البيانات حسب `fields.php` ثم يدرج/يحدّث السجل عبر
  PDO prepared statements (حماية من SQL injection).
- `view.php` يعيد بناء نص العقد الأصلي بالعربية والإنجليزية مع إدراج
  القيم المحفوظة مكان الفراغات (تحت شعار وتذييل الشركة الفعليين)؛ أي حقل
  لم يُعبأ يظهر كخط فارغ ليُكتب يدويًا، تمامًا كما في النموذج الأصلي.
- زر الطباعة يستخدم `window.print()` مع تنسيق طباعة (`@media print`) يخفي
  أزرار التنقل ويحافظ على تقسيم الصفحات حسب كل بند.

- All contract fields are defined once in `fields.php`, which drives both
  validation and the save logic.
- `save.php` validates input against `fields.php`'s type rules, then
  inserts/updates via PDO prepared statements (SQL-injection safe).
- `view.php` reconstructs the original bilingual contract text with the
  saved values substituted in (under the company's real logo and footer
  bar); any field left blank still prints as an underline for manual
  completion, just like the original fillable template.
- The print button calls `window.print()`; print-specific CSS hides the
  navigation chrome and avoids splitting a row across a page break.

## ملاحظة أمنية / Security note

لا تُخزَّن أي بيانات اعتماد لقاعدة البيانات داخل الكود — تُقرأ من متغيرات
البيئة. لا ترفع ملف `.env` أو أي بيانات اعتماد فعلية إلى المستودع.

No database credentials are hard-coded — they're read from environment
variables. Never commit a `.env` file or real credentials to the repo.

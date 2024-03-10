# contact-system

simple contact manager in plain php + mysql. you make an account, then add / edit / delete / search your own contacts. started as a downtime project, cleaned it up when i felt like it.

## what it does

- register + login (passwords hashed with bcrypt)
- contacts table per user, sorted by name
- **live search** over name, company, phone and email (debounced, no page reload)
- add / edit contacts, delete with a confirm popup
- flash messages after every action
- dark mode follows your os setting

## security stuff i fixed after the first version

- every query uses prepared statements (pdo, no emulated prepares)
- all output goes through `e()` (htmlspecialchars), the first version printed contact names raw so you could put a `<script>` in a contact name lol
- you can only see / edit / delete your **own** contacts, before you could edit anyones by changing `?id=` in the url
- csrf token on every form, delete only works as a POST from the popup (not a plain link anymore)
- new session id on login, session cookie is httponly + samesite=lax

## run it

### with docker (easiest)

```
docker compose up
```

open http://localhost:8080 and log in with `demo@example.com` / `password123`

the db container loads `database/schema.sql` and `database/seed.sql` the first time it starts. to reset everything: `docker compose down -v`

### with xampp / any php + mysql

1. needs php 8.1+ with `pdo_mysql`
2. import the schema: `mysql -u root -p < database/schema.sql` (or paste it in phpmyadmin)
3. optional demo data: `mysql -u root -p contact_system < database/seed.sql`
4. put the folder in `htdocs` and open http://localhost/contact-system

db settings default to `root` with no password on localhost (xampp default). change them with env vars (`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`) or make a `config.local.php` that returns the same array as `config.php` (its gitignored)

## folders structure

```
includes/        bootstrap (session, helpers, csrf, flash) + db connection
partials/        nav and contact table rows, shared by the pages
css/app.css      the only stylesheet
database/        schema + demo seed
*.php            one file per page
```

## notes

- no framework, no composer, on purpose. its a small app
- search hits `search_contacts.php` which returns the table rows as html, the page just swaps them in

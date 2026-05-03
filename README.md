# 🦅 Bethel International School CMS

A complete Content Management System for Bethel International School in Pawing, Palo, Leyte.

## Features

- **Dynamic homepage** – announcements, feature cards, editable hero section.
- **News system** – one featured article, highlight stars, regular news grid.
- **Academics** – programs with icons, special programs.
- **Admissions** – editable content sections and steps.
- **Calendar** – PDF uploads and event management.
- **Newsletters** – PDF uploads with Coming Soon toggle.
- **About Us** – mission, vision, story, core values (individual entries with icons), statistics.
- **Contact messages** – store, read, delete.
- **Fully responsive** – works on all devices.

## Quick Installation

1. Create a database `bethel_school` in phpMyAdmin.
2. Import `database/setup.sql`.
3. Edit `includes/db.php` with your database credentials.
4. Set folder permissions: `chmod 755 uploads/ Images/`.
5. Access the website: `http://localhost/bethel-school/`
6. Admin login: `http://localhost/bethel-school/admin/login.php`  
   Username: `admin`  
   Password: `password`

## Admin Guide
s
- **Dashboard** – statistics, quick actions (Edit Hero, About Us, Academics, Admissions, Calendar, Newsletter). No direct article creation link.
- **News** – write, edit, delete. Set **featured** (only one) and **highlight** (star icon toggles gold).
- **Academics** – add/edit programs with custom icons (Font Awesome).
- **Admissions** – edit welcome text, requirements, key dates, steps.
- **Calendar** – upload PDF calendars, manage school events.
- **Newsletters** – upload PDFs, toggle Coming Soon page.
- **About Us** – edit mission, vision, story; manage core values (each with own icon and description); add/delete statistics.

## File Structure

- `includes/header.php` and `includes/footer.php` – shared layout.
- `css/style.css` – frontend styles.
- `css/admin-style.css` – admin panel styles.
- All pages now use external CSS and include files – no duplicate code.

## Security

- Change default admin password immediately.
- Delete `setup.sql` from your live server.
- Use HTTPS in production.
- Keep `uploads/` folder protected with `.htaccess` (or `index.html`).

## Support

For issues, check the error logs or contact the developer.

---

**Bethel International School** – *Soaring to Excellence in International Education*
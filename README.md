# 🦅 Bethel International School CMS

A complete Content Management System for Bethel International School in Pawing, Palo, Leyte.

## 🚀 Quick Installation

1. **Create database**: `bethel_school` in phpMyAdmin
2. **Import SQL**: Import `database/setup.sql`
3. **Configure DB**: Edit `includes/db.php` with your credentials
4. **Set permissions**: `chmod 755 uploads/ Images/`
5. **Access website**: `http://localhost/bethel-school/`
6. **Admin login**: `http://localhost/bethel-school/admin/login.php`
   - Username: `admin`
   - Password: `password`

## ✨ Key Features

- **News System** - One featured article + highlights + regular news
- **Core Values** - Individual values with icons and descriptions
- **Academic Programs** - Custom icons for each program
- **Admissions Management** - Steps and content sections
- **Calendar & Newsletters** - PDF upload with Coming Soon toggle
- **Full CRUD** - All content editable via admin panel

## 📊 Quick Admin Guide

| Section | What you can do |
|---------|-----------------|
| **News** | Set featured article (only one), toggle highlights with star icon |
| **About Us** | Edit Mission, Vision, Story; manage Core Values individually |
| **Academics** | Add programs with custom icons, manage special programs |
| **Admissions** | Edit welcome text, requirements, and admission steps |
| **Calendar** | Upload PDFs, manage events, toggle Coming Soon |
| **Newsletters** | Upload PDFs, toggle Coming Soon |

## 🔧 Common Issues

| Problem | Solution |
|---------|----------|
| Can't login | Use `admin` / `password` |
| Core values not showing | Add them via Admin → Manage About Us → Core Values |
| Featured article not appearing | Only one article can be featured - set it in Manage News |
| Images not loading | Use placeholder: `https://placehold.co/800x600/002366/FFD700?text=Title` |

## 🔒 Security (Important!)

**Before going live:**
- Change default admin password
- Delete `setup.sql` from server
- Use HTTPS/SSL

## 📚 More Info

- **Detailed setup**: See `SETUP.txt`
- **Database schema**: See `database/setup.sql`

## 📞 Contact

Bethel International School - Pawing, Palo, Leyte
📞 0917-173-0284
📧 secretary@bethel.edu.ph

---

*"Soaring to Excellence in International Education"*
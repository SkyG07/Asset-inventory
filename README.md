# LGU Asset Management System

A fully-featured **PHP + MySQL municipal asset management system** built for local government units (LGUs).  
Designed to manage offices, assets, and asset logs with PDF exports and audit trails.

---

## 🛠 Technology Stack

- **Backend:** PHP (procedural) + PDO
- **Database:** MySQL / MariaDB
- **Frontend:** HTML5, Bootstrap 5, jQuery
- **Interactivity:** DataTables (search, filter, pagination)
- **Reports:** jsPDF for PDF exports
- **Notifications:** Toastr, SweetAlert2
- **QR & Signatures:** QRCode Reader JS, jSignature

---

## ⚡ Features

- User authentication with session protection
- Role-based access (Admin / Staff)
- CRUD operations for offices, assets, and users
- Asset logs / audit trail
- PDF report exports (full inventory, by office, single asset)
- Fully offline-capable (LAN or self-hosted)
- Security: password hashing, session checks, input validation
- Professional UI with Bootstrap + DataTables

---

## 📁 Folder Structure
asset-inventory/
├─ actions/ # PHP scripts for CRUD and system actions
├─ config/ # Database configuration
├─ includes/ # Header, footer, sidebar, helpers
├─ logs/ # Activity logs
├─ public/ # CSS, JS, images
├─ reports/ # PDF / printable reports
├─ views/ # Pages for admin/staff
├─ index.php
├─ router.php
└─ README.md


---

## ⚙️ Deployment (Optional)

1. Copy folder to a server (Apache / XAMPP)  
2. Import MySQL database (`users`, `offices`, `assets`, `asset_logs`)  
3. Update `/config/database.php` with server credentials  
4. Set `/uploads` folder writable  
5. Open `index.php` in browser → ready to use  

---

## 📌 Demo / Portfolio

This system is **fully functional locally**.  
For portfolio purposes, you can view the code and structure on GitHub.

- GitHub: [https://github.com/SkyG07/asset-inventory](https://github.com/SkyG07/asset-inventory)

---

## 🔒 Notes

- `/config/database.php` and `/uploads` are ignored for privacy/security  
- Designed to run on **internal LGU servers** or XAMPP for testing  
- Can be extended with QR code scanning, bulk import/export, email notifications

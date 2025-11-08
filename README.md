# 🧠 CCET Student Vault

> A centralized, role-based academic resource sharing platform built with **Laravel**, **PostgreSQL**, and **Docker** — enabling students, teachers, CRs, and admins to securely upload, search, and manage college documents like question papers, notes, and assignments.

---

## 🚀 Features

✅ **Role-based access control**

- Students, Teachers, CRs (Class Reps), and Admins
- Each role has its own permissions and dashboards

✅ **Secure Authentication**

- Email-based Sign Up / Sign In
- OTP verification via Gmail
- Password rules and reset flow

✅ **File Upload & Management**

- Upload question papers, notes, assignments, MSTs, etc.
- Supports **PDF, JPG, PNG, DOCX** formats
- CR/Admin approval system before publishing

✅ **Smart Search & Filters**

- Search by **Subject**, **Branch**, **Year**, **Teacher Name**
- Auto-filter results based on user’s role and branch

✅ **Notifications System**

- CRs and Admins can send branch/year-based announcements
- Real-time alerts for approvals and uploads

✅ **Responsive Frontend**

- Built using **Blade**, **TailwindCSS**, and **JavaScript**
- Mobile-first, fast-loading UI

✅ **Activity Logs**

- Tracks every upload, approval, and profile update

✅ **Dockerized Setup**

- Portable and production-ready with Docker & docker-compose

✅ **CI/CD**

- Automated testing and deployment with **GitHub Actions**

---

## 🏗️ System Architecture

```plaintext
┌──────────────────────────┐
│        Frontend          │
│ HTML • CSS • JS • Blade  │
└────────────┬─────────────┘
             │
┌────────────▼─────────────┐
│        Backend (API)     │
│ Laravel 11 + PHP 8.2     │
│ Authentication • Uploads │
└────────────┬─────────────┘
             │
┌────────────▼─────────────┐
│       Database Layer     │
│ PostgreSQL 15 (Eloquent) │
└────────────┬─────────────┘
             │
┌────────────▼─────────────┐
│     Storage & Services   │
│ AWS S3 / Local • Redis   │
└────────────┬─────────────┘
             │
┌────────────▼─────────────┐
│   Docker + GitHub CI/CD  │
│ Build • Test • Deploy    │
└──────────────────────────┘
⚙️ Tech Stack
Layer	Technology	Description
Frontend	HTML5, CSS3, JS (ES6), Blade, TailwindCSS	Responsive UI, templated layouts
Backend	PHP 8.2 + Laravel 11	Core logic, APIs, authentication
Database	PostgreSQL 15	Relational data storage
Caching/Queue	Redis (optional)	Speeds up queries & background jobs
Containerization	Docker + Docker Compose	Portable, reproducible setup
Web Server	Nginx + PHP-FPM	Efficient serving of Laravel app
Version Control	Git + GitHub	Source code & CI/CD pipeline
CI/CD	GitHub Actions	Auto build, test, deploy
Storage	AWS S3 / Local	File uploads (PDFs, notes, etc.)
Email Service	SMTP (Gmail / SendGrid)	OTPs, notifications
Testing	PHPUnit / Pest	Unit and feature testing

📁 Folder Structure (Simplified)
bash
Copy code
ccet-student-vault/
├── app/
│   ├── Http/Controllers/
│   ├── Models/
│   ├── Services/
│   └── Policies/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/          # Blade templates
│   ├── css/
│   └── js/
├── routes/
│   ├── web.php         # UI routes
│   └── api.php         # API endpoints
├── docker/
│   ├── php/Dockerfile
│   ├── nginx/default.conf
│   └── postgres/init.sql
├── infra/
│   ├── docker-compose.yml
│   └── .env.example
├── .github/workflows/
│   └── ci-cd.yml
├── tests/
│   └── Feature/ & Unit/
└── README.md
🧠 How It Works
1️⃣ User Signup:

Enters details → OTP sent via Gmail → verified account.

2️⃣ Upload Flow:

Uploads file (PDF/image) → stored temporarily → CR/Admin approval.

3️⃣ Approval Workflow:

CR/Admin reviews → approves/rejects → user notified.

4️⃣ Search & Filter:

Students can browse files filtered by branch, subject, or teacher.

5️⃣ Notifications:

CR/Admin sends updates visible on dashboards.

6️⃣ Track Progress:

Users view their uploaded files + approval status.

🐳 Docker Setup
🧩 1. Clone the Repository
bash
Copy code
git clone https://github.com/yourusername/ccet-student-vault.git
cd ccet-student-vault
🧩 2. Copy Environment File
bash
Copy code
cp infra/.env.example .env
🧩 3. Start Containers
bash
Copy code
docker-compose up --build
This spins up:

app → Laravel + PHP-FPM

db → PostgreSQL

nginx → Web server proxy

Access the app at:
👉 http://localhost:8080

🧰 Environment Variables (.env)
Variable	Description
APP_NAME	Application name
APP_ENV	Environment (local/production)
APP_KEY	Laravel app key
DB_CONNECTION	pgsql
DB_HOST	db (Docker service name)
DB_PORT	5432
DB_DATABASE	Database name
DB_USERNAME	Database user
DB_PASSWORD	Database password
MAIL_MAILER	smtp
MAIL_HOST	Gmail/SendGrid SMTP
MAIL_PORT	587
MAIL_USERNAME	Sender email
MAIL_PASSWORD	App password
MAIL_ENCRYPTION	tls

🧪 Run Tests
bash
Copy code
php artisan test
or inside Docker:

bash
Copy code
docker exec -it ccet-app php artisan test
🤖 GitHub Actions (CI/CD)
.github/workflows/ci-cd.yml automates:

✅ Build Laravel app

✅ Run tests

✅ Build Docker image

✅ Push to container registry

✅ Deploy to production

Triggered on:

yaml
Copy code
on:
  push:
    branches: [ main ]
  pull_request:
    branches: [ main ]
📊 Database Schema (Simplified)
bash
Copy code
users
 ├─ id
 ├─ name
 ├─ email
 ├─ password
 ├─ role (student, teacher, cr, admin)
 ├─ branch_id
 ├─ year
 └─ email_verified_at

uploads
 ├─ id
 ├─ user_id
 ├─ subject_id
 ├─ branch_id
 ├─ upload_type
 ├─ file_path
 ├─ status (pending, approved, rejected)
 └─ admin_comment

subjects
 ├─ id
 ├─ name
 ├─ branch_id

notifications
 ├─ id
 ├─ title
 ├─ body
 ├─ branch_id
 ├─ year
 ├─ user_id
 └─ read_at
🔒 Security
✅ Encrypted passwords (bcrypt)

✅ CSRF protection for forms

✅ Role-based access via Policies

✅ Rate-limited login/OTP

✅ Input validation & sanitization

✅ HTTPS enforced in production

👩‍💻 Contribution Guide
Fork the repository

Create a new branch:

bash
Copy code
git checkout -b feature/your-feature-name
Commit changes:

bash
Copy code
git commit -m "Added new feature"
Push your branch:

bash
Copy code
git push origin feature/your-feature-name
Submit a Pull Request ✅
```

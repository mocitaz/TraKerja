# 🎯 JobTracker

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg?style=for-the-badge&logo=laravel)
![Livewire](https://img.shields.io/badge/Livewire-3.6-orange.svg?style=for-the-badge)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.1-blue.svg?style=for-the-badge&logo=tailwindcss)
![PHP](https://img.shields.io/badge/PHP-8.2+-purple.svg?style=for-the-badge&logo=php)
![Vite](https://img.shields.io/badge/Vite-7.0+-yellow.svg?style=for-the-badge&logo=vite)

**A comprehensive job application tracking system built with Laravel & Livewire**

[🚀 Live Demo](trakerja.web.id) • [📖 Documentation](#-documentation) • [⚡ Quick Start](#-quick-start) • [🤝 Contributing](#-contributing)

</div>

---

## ✨ Features

### 📊 **Smart Dashboard & Analytics**
- **Real-time Analytics Cards** - Track applications, interviews, and success rates
- **Career Summary Pro** - Comprehensive career insights and statistics
- **Visual Progress Tracking**  - Beautiful charts and progress indicators

### 📋 **Job Application Management**
- **Kanban Board** - Drag-and-drop interface for managing application status
- **Detailed Job Records** - Company info, position, location, platform, and notes
- **Status Tracking** - On Process, Declined, Accepted with color-coded indicators
- **Recruitment Stage Management** - Track HR interviews, user interviews, follow-ups

### 🎯 **Goals & Cadence System**
- **Weekly Goal Setting** - Set targets for applications and follow-ups
- **Progress Monitoring** - Real-time progress tracking with percentage completion
- **Streak Tracking** - Monitor consecutive days meeting daily targets
- **Smart Notifications** - Milestone alerts and achievement celebrations
- **Cadence Effect Analysis** - Compare performance when goals are met vs missed

### 📈 **Advanced Analytics**
- **Export Functionality** - CSV export for job applications and statistics
- **Historical Data** - 8-week history tracking and trend analysis
- **Performance Insights** - Target achievement rates and improvement suggestions

### 👤 **User Management**
- **Profile Management** - Customizable user profiles with logo upload
- **Secure Authentication** - Laravel Breeze with email verification
- **Password Security** - Strong password validation and secure updates

---

## 🛠️ Tech Stack

### Backend
- **Laravel 12.x** - Modern PHP framework
- **Livewire 3.6** - Full-stack framework for dynamic UIs
- **SQLite** - Lightweight database (easily configurable for MySQL/PostgreSQL)

### Frontend
- **TailwindCSS 3.1** - Utility-first CSS framework
- **Alpine.js 3.4** - Lightweight JavaScript framework
- **Vite 7.0** - Fast build tool and dev server

### Development Tools
- **Laravel Pint** - Code style fixer
- **PHPUnit** - Testing framework
- **Faker** - Data generation for testing

---

## 🚀 Quick Start

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- SQLite (or MySQL/PostgreSQL)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/jobtracker.git
   cd jobtracker
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   # For SQLite (default)
   touch database/database.sqlite
   php artisan migrate
   
   # Or for MySQL/PostgreSQL, update .env and run:
   # php artisan migrate
   ```

5. **Build assets**
   ```bash
   npm run build
   ```

6. **Start the application**
   ```bash
   php artisan serve
   ```

7. **Access the application**
   Open [http://localhost:8000](http://localhost:8000) in your browser

### Development Mode

For development with hot reloading:

```bash
# Terminal 1: Start Laravel server
php artisan serve

# Terminal 2: Start Vite dev server
npm run dev

# Terminal 3: Start queue worker (optional)
php artisan queue:work
```

---

## 📱 Screenshots

### Dashboard Overview
![Dashboard](https://via.placeholder.com/800x400/3B82F6/FFFFFF?text=Dashboard+Overview)

### Kanban Board
![Kanban Board](https://via.placeholder.com/800x400/10B981/FFFFFF?text=Kanban+Board)

### Goals Management
![Goals](https://via.placeholder.com/800x400/F59E0B/FFFFFF?text=Goals+Management)

### Analytics
![Analytics](https://via.placeholder.com/800x400/8B5CF6/FFFFFF?text=Analytics+Dashboard)

---

## 🏗️ Project Structure

```
JobTracker/
├── app/
│   ├── Http/Controllers/     # API and web controllers
│   ├── Livewire/            # Livewire components
│   ├── Models/              # Eloquent models
│   └── Services/            # Business logic services
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/            # Database seeders
├── resources/
│   ├── views/              # Blade templates
│   └── css/                # Stylesheets
└── tests/                  # Test files
```

---

## 🔧 Configuration

### Database Configuration
Update your `.env` file with your database credentials:

```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite

# Or for MySQL/PostgreSQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=jobtracker
# DB_USERNAME=root
# DB_PASSWORD=
```

### Mail Configuration
Configure mail settings for notifications:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
```

---

## 🧪 Testing

Run the test suite:

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=JobApplicationTest

# Run with coverage
php artisan test --coverage
```

---

## 📊 API Endpoints

### Job Applications
- `GET /tracker` - Job tracker dashboard
- `GET /jobs/{id}` - View specific job application
- `POST /job-applications` - Create new job application
- `PUT /job-applications/{id}` - Update job application
- `DELETE /job-applications/{id}` - Delete job application

### Export
- `GET /export/job-applications/csv` - Export job applications to CSV
- `GET /export/job-applications/stats` - Get export statistics

### Goals
- `GET /goals` - Goals management page
- `POST /goals` - Set weekly goals

---

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines
- Follow PSR-12 coding standards
- Write tests for new features
- Update documentation as needed
- Use conventional commit messages

---

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The amazing PHP framework
- [Livewire](https://livewire.laravel.com) - Full-stack framework
- [TailwindCSS](https://tailwindcss.com) - Utility-first CSS framework
- [Vite](https://vitejs.dev) - Next generation frontend tooling

---

## 📞 Support

If you have any questions or need help:

- 📧 Email: support@jobtracker.com
- 🐛 Issues: [GitHub Issues](https://github.com/yourusername/jobtracker/issues)
- 💬 Discussions: [GitHub Discussions](https://github.com/yourusername/jobtracker/discussions)

---

<div align="center">

**Made with ❤️ by [Your Name](https://github.com/yourusername)**

[⭐ Star this repo](https://github.com/yourusername/jobtracker) • [🐛 Report Bug](https://github.com/yourusername/jobtracker/issues) • [💡 Request Feature](https://github.com/yourusername/jobtracker/issues)

</div>

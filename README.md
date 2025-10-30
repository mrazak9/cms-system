# SimpleCMS

> A simple, user-friendly Content Management System built with Laravel

![License](https://img.shields.io/badge/license-MIT-blue.svg)
![PHP Version](https://img.shields.io/badge/PHP-8.1-purple.svg)
![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)

---

## 📖 Overview

**SimpleCMS** adalah Content Management System yang dirancang untuk menjadi lebih mudah digunakan dibandingkan WordPress, khususnya untuk user non-teknis. Sistem ini menggunakan pendekatan section-based page builder yang memungkinkan admin untuk membuat halaman dengan cara drag & drop section yang sudah tersedia.

### 🎯 Tujuan Proyek

- ✅ Membuat CMS yang lebih sederhana dan intuitif dibanding WordPress
- ✅ Memudahkan admin non-teknis untuk mengelola website
- ✅ Menyediakan section templates siap pakai yang dapat dicustomize
- ✅ Fokus pada user experience yang excellent

### ⭐ Fitur Utama

- 📄 **Page Builder**: Sistem section-based dengan drag & drop
- 📝 **Post Management**: Blog/artikel dengan kategori
- 🎨 **Theme System**: 3 tema utama yang dapat dipilih
- 🔧 **Menu Builder**: Buat menu custom dengan hierarki
- 📦 **Section Library**: Koleksi section templates siap pakai
- 🖼️ **Media Library**: Upload dan kelola gambar/file
- ⚙️ **Settings**: Konfigurasi site-wide
- 👥 **User Management**: Role & permission system

---

## 🚀 Tech Stack

| Technology | Version | Purpose |
|------------|---------|---------|
| PHP | 8.1 | Backend language |
| Laravel | 10.x | PHP framework |
| MySQL | 8.0+ | Database |
| Bootstrap | 5.3 | CSS framework |
| Stisla | Latest | Admin template |
| SortableJS | Latest | Drag & drop |
| Alpine.js | 3.x (Optional) | Reactive components |

---

## 📋 Prerequisites

Sebelum memulai, pastikan Anda memiliki:

- **PHP 8.1** or higher
- **MySQL 8.0** or higher
- **Composer** 2.x
- **Node.js & NPM** 16.x or higher
- **Git** (optional)

---

## 🔧 Installation

### Quick Start (Windows)

1. **Clone repository**
   ```bash
   git clone https://github.com/yourusername/simplecms.git
   cd simplecms
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Setup environment**
   ```bash
   copy .env.example .env
   php artisan key:generate
   ```

4. **Configure database**
   Edit `.env` file:
   ```env
   DB_DATABASE=simplecms
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Run migrations & seeders**
   ```bash
   php artisan migrate --seed
   ```

6. **Create storage link**
   ```bash
   php artisan storage:link
   ```

7. **Start development server**
   ```bash
   php artisan serve
   ```

8. **Access application**
   ```
   Frontend: http://localhost:8000
   Admin: http://localhost:8000/admin
   ```

### Default Admin Credentials

```
Email: admin@simplecms.test
Password: admin123
```

**⚠️ Important:** Change default credentials after first login!

---

## 📚 Documentation

Dokumentasi lengkap tersedia di direktori proyek:

| Document | Description |
|----------|-------------|
| [TECH_SPEC.md](TECH_SPEC.md) | Technical specification lengkap |
| [DATABASE_SCHEMA.md](DATABASE_SCHEMA.md) | Database structure & relationships |
| [SETUP_GUIDE.md](SETUP_GUIDE.md) | Setup guide untuk Windows |
| [DEVELOPMENT_GUIDE.md](DEVELOPMENT_GUIDE.md) | Coding standards & best practices |
| [API_DOCUMENTATION.md](API_DOCUMENTATION.md) | API endpoints reference |

---

## 🎨 Features Overview

### 1. Page Builder

Buat halaman dengan mudah menggunakan section templates:

1. **Create Page** → Beri title & slug
2. **Add Sections** → Pilih dari library
3. **Customize Content** → Edit text, gambar, link
4. **Reorder** → Drag & drop untuk atur posisi
5. **Publish** → Langsung live!

**Available Section Categories:**
- Hero Sections (5 variants)
- About Sections (3 variants)
- Features/Services (4 variants)
- Testimonials (2 variants)
- Pricing Tables (2 variants)
- Gallery/Portfolio (3 variants)
- Contact Forms (2 variants)
- CTA Sections (3 variants)
- Footer (3 variants)

### 2. Post Management

- Create/Edit/Delete posts
- Categories & tags
- Featured images
- WYSIWYG editor
- SEO meta tags
- Publish scheduling

### 3. Menu Builder

- Create multiple menus
- Add pages, posts, categories, or custom links
- Drag & drop to reorder
- Support nested menu (max 2 levels)
- Multiple locations (header, footer, etc)

### 4. Theme System

3 tema utama:

1. **Default Theme** - General purpose, clean
2. **Business Theme** - Professional corporate
3. **Portfolio Theme** - Creative agency

Switch theme dengan 1 klik!

### 5. Media Library

- Upload images & files
- Auto image optimization
- Browse & select media
- Alt text & title support

---

## 🏗️ Project Structure

```
simplecms/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin controllers
│   │   │   └── Frontend/       # Frontend controllers
│   │   └── Requests/           # Form validation
│   ├── Models/                 # Eloquent models
│   ├── Services/               # Business logic
│   └── View/Components/        # Blade components
├── resources/
│   ├── views/
│   │   ├── admin/              # Admin panel views
│   │   ├── frontend/           # Public views
│   │   └── components/         # Section components
│   └── css/
│       ├── admin.css
│       └── frontend.css
├── public/
│   ├── assets/
│   │   ├── admin/              # Stisla files
│   │   └── frontend/
│   └── uploads/                # User uploads
├── database/
│   ├── migrations/
│   └── seeders/
└── routes/
    └── web.php
```

---

## 🛠️ Development

### Setup Development Environment

Lihat [SETUP_GUIDE.md](SETUP_GUIDE.md) untuk instruksi lengkap.

### Coding Standards

- Follow **PSR-12** coding standard
- Use **type hints** for parameters and return types
- Write **descriptive variable names**
- Add **comments** for complex logic
- See [DEVELOPMENT_GUIDE.md](DEVELOPMENT_GUIDE.md) for details

### Git Workflow

```bash
# Create feature branch
git checkout -b feature/your-feature

# Make changes and commit
git add .
git commit -m "feat: add your feature"

# Push to remote
git push origin feature/your-feature
```

### Running Tests

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage
```

---

## 🚢 Deployment

### Production Checklist

- [ ] Change default admin credentials
- [ ] Update `.env` for production
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure database credentials
- [ ] Run `composer install --optimize-autoloader --no-dev`
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Setup SSL certificate
- [ ] Configure backup system
- [ ] Setup monitoring

### Server Requirements

- PHP 8.1+
- MySQL 8.0+ or MariaDB 10.5+
- Apache 2.4+ or Nginx 1.18+
- Minimum 512MB RAM (1GB recommended)
- Minimum 500MB storage

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'feat: Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

### Coding Guidelines

- Follow PSR-12 standard
- Write tests for new features
- Update documentation
- Add comments for complex logic

---

## 📝 License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

---

## 👥 Team

- **Project Lead**: Your Name
- **Backend Developer**: Developer 1
- **Frontend Developer**: Developer 2
- **UI/UX Designer**: Designer 1

---

## 📞 Support

Need help? Here are some resources:

- **Documentation**: Read the docs in `/docs` folder
- **Issues**: Report bugs on GitHub Issues
- **Discussions**: Ask questions on GitHub Discussions
- **Email**: support@simplecms.test

---

## 🗺️ Roadmap

### Phase 1 (MVP) - ✅ Current
- [x] Basic page management
- [x] Section template system
- [x] Post management
- [x] Menu builder
- [x] Theme switcher

### Phase 2 (Enhancement)
- [ ] Advanced drag & drop
- [ ] More section templates (30+ total)
- [ ] Form builder with submissions
- [ ] Multi-language support
- [ ] Advanced SEO tools

### Phase 3 (Pro Features)
- [ ] E-commerce integration
- [ ] Analytics dashboard
- [ ] A/B testing
- [ ] Custom field builder
- [ ] Plugin system

---

## 🌟 Highlights

### Why SimpleCMS?

| Feature | SimpleCMS | WordPress |
|---------|-----------|-----------|
| **Ease of Use** | ⭐⭐⭐⭐⭐ | ⭐⭐⭐ |
| **Learning Curve** | Gentle | Steep |
| **Interface** | Clean & Modern | Cluttered |
| **Performance** | Fast | Can be slow |
| **Target User** | Non-technical | Developers |

### Performance

- ⚡ Page load time < 2 seconds
- 📊 Admin panel response < 1 second
- 🎯 Optimized database queries
- 💾 Built-in caching system

---

## 📸 Screenshots

### Admin Panel
*Coming soon*

### Page Builder
*Coming soon*

### Frontend
*Coming soon*

---

## 🔄 Changelog

### Version 1.0.0 (2025-10-30)
- Initial release
- Basic page builder
- Post management
- Menu system
- 3 themes
- Section templates

---

## 🙏 Acknowledgments

- **Laravel** - PHP framework
- **Bootstrap** - CSS framework
- **Stisla** - Admin template
- **SortableJS** - Drag & drop library
- All contributors and supporters

---

## 📊 Stats

![GitHub stars](https://img.shields.io/github/stars/yourusername/simplecms?style=social)
![GitHub forks](https://img.shields.io/github/forks/yourusername/simplecms?style=social)
![GitHub watchers](https://img.shields.io/github/watchers/yourusername/simplecms?style=social)

---

**Made with ❤️ in Indonesia**

---

<p align="center">
  <a href="#top">⬆️ Back to Top</a>
</p>

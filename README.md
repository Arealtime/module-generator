# 🛠️ Module Generator for Laravel

An Artisan command package for generating Laravel modules following a clean, modular architecture.  
This package helps you easily create and manage modules in your Laravel projects, following the principles of clean architecture.

> 🔗 Repository: [github.com/Arealtime/module-generator](https://github.com/Arealtime/module-generator)

---

## ✨ Features

- ✅ Generate Laravel modules with a simple Artisan command
- ✅ Clean and consistent modular architecture
- ✅ Easy integration into existing Laravel projects
- ✅ Helps maintain scalability and code organization in large applications
- ✅ Built-in structure for controller, model, config, routes, migration, etc.

---

## 🚀 Installation

In your Laravel project's `composer.json`, add the following:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/Arealtime/module-generator"
    }
]
"require": {
    "arealtime/module-generator": "*"
}
```

Then run:

```bash
composer update
```

---

## 📋 Usage

### ➕ Generate a New Module

```bash
php artisan module:generate ModuleName
```

Generates a new module in:

```
packages/Arealtime/ModuleName
```

### 📄 List All Modules

```bash
php artisan module:list
```

Shows a list of all modules present in your Laravel project.

---

## 📁 Module Structure

Each generated module will have the following structure:

```
packages/
└── Arealtime/
    └── ModuleName/
        ├── composer.json
        └── src/
            ├── app/
            │   ├── Console/
            │   ├── Http/
            │   ├── Models/
            │   └── Providers/
            ├── config/
            ├── routes/
            └── database/
                └── migrations/
```

---

## ✏️ Customization Guide

- Add logic in `Controllers`, `Commands`, or `Services`
- Define API routes in `routes/api.php`
- Update module config via `config/arealtime-modulename.php`
- Register bindings or services in `Providers/ModuleServiceProvider.php`

---

## 👤 Author

**Arash Taghavi**  
📧 arash.taghavi69@gmail.com  
🔗 [GitHub: arash-sh](https://github.com/arash-sh)

---

## 📄 License

MIT © Arealtime

---

## ⭐️ Contribute & Support

- 🌟 Star the repository
- 🛠️ Fork and improve the package
- 🐛 Submit issues or feature requests

---

> _Built with ❤️ to help you scale Laravel the modular way._
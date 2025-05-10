# Module Generator for Laravel 🛠️

An Artisan command package for generating Laravel modules following a clean, modular architecture. This package helps you easily create and manage modules in your Laravel projects, following the principles of clean architecture.

## Features ✨

- Generate Laravel modules with a simple Artisan command.
- Modules follow a clean, modular architecture.
- Easy to integrate into existing Laravel projects.
- Helps maintain scalability and organization in large projects.


## Installation 🚀

### Add the package to your `composer.json`

In your Laravel project, add the following to your `composer.json`:

```json
"repositories": [
    {
        "type": "path",
        "url": "packages/Arealtime/ModuleGenerator"
    }
],
"require": {
    "arealtime/module-generator": "*"
}
```

## Usage 📋

### Generating a Module

To generate a new module, run the following Artisan command:

```bash
php artisan module:generate ModuleName
```
This command generates a new module in the packages/ directory with the given name.

### List all Modules:

```bash
php artisan module:list
```
This command will list all the available modules in your project.





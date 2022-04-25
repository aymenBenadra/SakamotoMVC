<!-- MVC framework readme file -->
# SakamotoMVC

![SakamotoMVC](https://i.ibb.co/F4mppQj/Sakamoto-MVC-cover.png)

## Introduction

SakamotoMVC (坂本MVC) is a simple yet powerful MVC framework for PHP, written in PHP. and it is made for simple and fast development of web applications and APIs.

## Features

SakamotoMVC has a lot of features out of the box and it is easy to use and customize. Some of the features are:

- PDO Database Binding
- ORM-like Model
- PSR-4 Autoloading
- Server-Side Rendering (SSR)
- API
- Dynamic Routing
- Authentification JWT + API KEY
- CRUD operations out of the box
- Scaffolding Examples
- Data Sanitization
- Data Validation with so many flags
- Straight-forward structure
- Improved performance
- And many more features for you to discover

## Installation

### Install via Composer

```bash
composer create-project sakamoto/mvc [project-name] --stability dev
```

Navigate to the project folder:

```bash
> cd [project_name]
```

Install requirements:

```bash
> composer install
```

### Install via Github Cli

Make sure you have PHP 8 or higher installed on your computer, install git and Github CLI (if you don't have it already).

Login with your Github account:

```bash
> gh auth login
```

Create a new repository with your project name and SakamotoMVC as the template:

```bash
> gh repo create [project_name] --template aymenBenadra/sakamotoMVC --[private, public, internal]
```

Clone the repository:

```bash
> gh repo clone [project_name]
```

Navigate to the project folder:

```bash
> cd [project_name]
```

Install requirements:

```bash
> composer install
```

## Usage

1. Specify the project name in the composer.json file.
2. Add your routes in the routes.php file in the app/config directory of your project.
3. Create a new model using the Example Model template.
4. Create a new controller using the Example Controller template.
5. Add your Views in the views directory of your project.
6. optional: use the PsySH shell to test your project.

    ```bash
    php ./vendor/bin/psysh
    ```

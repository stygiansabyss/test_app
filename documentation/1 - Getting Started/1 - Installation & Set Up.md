# Installation & Set Up

- [Installation](#installation)
    - [Cloning](#cloning)
- [Set Up](#set-up)
    - [Composer](#composer)
    - [Environment](#environment)
    - [Database](#database)

<a name="installation"></a>
## Installation

<a name="cloning"></a>
### Cloning

To begin, you will need to clone the repository.

```
git clone <repo> ./application
```

<a name="set-up"></a>
## Set Up

<a name="composer"></a>
### Composer

You will need to gather the required packages.  To do this, just run the following command.

```
composer install
```

<a name="environment"></a>
### Environment

Now set up your environment file.  Run the following commands.

```
cp .env.example .env
php artisan key:generate
```

<a name="database"></a>
### Database

Next you will need to get the database running.  First, add your database however you normally would (command line, sequel 
pro, etc).  Then update your `.env` file with these database details.  The necessary keys begin with `db_`.

You can now migrate the database tables.

```
php artisan migrate
```

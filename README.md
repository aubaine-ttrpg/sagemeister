# 🧙‍♂️ Sagemeister
![PHP Badge](https://img.shields.io/badge/PHP-8.2-%23777BB4?logo=php&logoColor=%23777BB4)
![Symfony Badge](https://img.shields.io/badge/Symfony-7.1-%23ecf0f1?logo=symfony&logoColor=%23ecf0f1)

A polyvalent tool to create Classes, Races, Jobs, and Lore for the Aubaine system.

## 📦 Install
Install everything using these command:
```bash
# If composer is installed on your computer
$ composer install 

# If Symfony CLI is installed you can use the command below
$ symfony composer install
```

## 🏃 Run
You might want to run the developper database and database inspector:
```bash
# Only run this command once when setting up the project
$ make up 
```
> The database administrator can be found at [this URL](http://localhost:8080) !

Then you can simply run the local server using:
```bash
$ make dev
```

## 🔨 Make rules
You can find the Make rules by running:
```bash
$ make help
```

## ⚙️ Dependencies
- [Symfony CLI](https://symfony.com/download)
- [GNU Make](https://www.gnu.org/software/make/)
- [PHP 8.2](https://www.php.net/releases/8.2/en.php) with the following extensions:
    - mysql
    - xml
    - intl
    - mbstring
    > You can check those using Symfony's checker using: `$ symfony check:requirements`
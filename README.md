# Snowtricks
Blog de présentation du snowboard.
Application écrite avec Symfony 4.

## Installation du repository

- Créer un répertoire "snowtricks" et se placer dedans.
- Ouvrir votre IDE (ou un Terminal en étant dans le bon répertoire) et taper en ligne de commande :
```
git clone git@github.com:plouha/Snowtricks.git
```
- Installer composer en tapant en ligne de commande :
```
composer install
```
- Configurer la variable d'environnement `DATABASE_URL` dans le fichier `.env`.
- Créer la base de données snowtricks puis ses tables. Taper en ligne de commande :
```
php bin/console doctrine:database:create snowtricks
php bin/console doctrine:schema:update --force
```
- Création des fixtures dans la base de données
```
php bin/console doctrine:fixtures:load
```

## Lancement du serveur de dévellopement

- Lancer le serveur de dévellopement en tapant en ligne de commande
```
php bin/console server:run
```

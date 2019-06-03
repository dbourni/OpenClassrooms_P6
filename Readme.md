# P6 - Projet OpenClassrooms
<a href="https://codeclimate.com/github/dbourni/Openclassrooms_P6/maintainability"><img src="https://api.codeclimate.com/v1/badges/ebeaa80aec2a660d42f4/maintainability" /></a>
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/1698efd40be24d24a5d63a05e1c308b6)](https://www.codacy.com/app/dbourni/Openclassrooms_P6?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=dbourni/Openclassrooms_P6&amp;utm_campaign=Badge_Grade)

Créer un site communautaire

## Installation

*   Clonez ou téléchargez le repository GitHub :
```system
git clone https://github.com/dbourni/Openclassrooms_P6.git
```
*   Configurez vos variables d'environnement telles que la connexion à la base de données ou votre adresse email dans le fichier .env

*   Installez les dépendances avec Composer :
```system
composer install
```
 
*   Créez la structure de la base de données :
```system
php bin/console doctrine:schema:create
```
 
*   Créez les fixtures vous permettant de tester :
```system
php bin/console doctrine:fixtures:load
```
 
## Connexion
 
Vous pouvez vous connecter avec les utilisateurs suivants (login | password) :
*   admin@admin.com | admin
*   user@user.com | user
 
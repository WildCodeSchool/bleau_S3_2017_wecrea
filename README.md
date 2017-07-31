WeCrea - Les artistes d'abord
=============================

A Symfony 3.2 project created onMay 2, 2017, 9:48 am.

#### Pré-requis: 
composer ==> [Install Composer](https://getcomposer.org/doc/00-intro.md)

#### Initialisation du projet
1. **Avec ssh**: git clone git@github.com:WildCodeSchool/bleau_S3_2017_wecrea.git
2. **Sans ssh**: git clone https://github.com/WildCodeSchool/bleau_S3_2017_wecrea.git
3. cd bleau_S3_2017_wecrea.git
4. composer install  
5. php bin/console doctrine:database:create
6. php bin/console doctrine:schema:update --force
7. php bin/console a:i
8. mkdir web/images
9. mkdir web/media
10. mkdir web/pdf
11. chmod -R 777 web/uploads/images
12. Setting up or Fixing File Permissions ==> https://symfony.com/doc/current/setup/file_permissions.html
13. php bin/console doctrine:fixtures:load
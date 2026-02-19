# Installation et lancement du projet sur Ubuntu

Ce guide explique pas-à-pas comment partir de zéro (install, clone git, configuration MySQL/Apache, import SQL, permissions) pour lancer le projet présent dans ce dépôt. Les commandes sont prêtes à copier-coller. Adaptations : remplacez `https://github.com/JohannChiarotto/RenduTp.git` par l'URL Git de votre dépôt si vous partez d'un clone distant.

Pré-requis : une machine Ubuntu (20.04/22.04 ou supérieur), accès sudo.

--

## Récapitulatif des chemins importants

- Projet : `/var/www/php_exam`
- Fichier principal : `/var/www/php_exam/index.php` (`index.php`)
- Config DB : `/var/www/php_exam/config/config.php` (`config/config.php`)
- Script d'import SQL : `/var/www/php_exam/sql/php_exam_db.sql` (`sql/php_exam_db.sql`)

Dans ce projet, les paramètres DB par défaut sont déjà dans `config/config.php` :

```
DB_HOST = 'localhost'
DB_NAME = 'php_exam_db'
DB_USER = 'php_exam_user'
DB_PASS = 'Azerty1234'
```

Vous pouvez soit créer la base et l'utilisateur avec ces valeurs, soit modifier `config/config.php` avant d'importer.

## 1) Installer les paquets nécessaires

Exécutez :

```bash
sudo apt update
sudo apt install -y apache2 mysql-server php libapache2-mod-php php-mysql php-xml php-mbstring php-curl php-zip git unzip
```

Vérifiez la version PHP installée :

```bash
php -v
```

## 2) Cloner le dépôt (ou copier les fichiers dans `/var/www`)

Si vous partez d'un dépôt Git distant :

```bash
sudo rm -rf /var/www/php_exam
sudo git clone https://github.com/JohannChiarotto/RenduTp.git /var/www/php_exam
sudo chown -R $USER:$USER /var/www/php_exam
```

Si vous avez déjà le code localement (déjà présent), passez à l'étape suivante.

## 3) Créer la base de données et l'utilisateur MySQL

Le projet attend par défaut :

- `DB_NAME` = `php_exam_db`
- `DB_USER` = `php_exam_user`
- `DB_PASS` = `Azerty1234`

Exécutez ces commandes (copier-coller) pour créer la base et l'utilisateur :

```bash
sudo mysql -e "CREATE DATABASE IF NOT EXISTS php_exam_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
sudo mysql -e "CREATE USER IF NOT EXISTS 'php_exam_user'@'localhost' IDENTIFIED BY 'Azerty1234';"
sudo mysql -e "GRANT ALL PRIVILEGES ON php_exam_db.* TO 'php_exam_user'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"
```

Ensuite importez le dump SQL fourni :

```bash
sudo mysql php_exam_db < /var/www/php_exam/sql/php_exam_db.sql
```

Si l'import échoue à cause d'autorisations sur le fichier, exécutez :

```bash
sudo cat /var/www/php_exam/sql/php_exam_db.sql | sudo mysql -u root
```

## 4) Configurer `config/config.php`

Ouvrez `config/config.php` (`/var/www/php_exam/config/config.php`) et vérifiez/ajustez :

- `DB_HOST` (par défaut `localhost`)
- `DB_NAME` (par défaut `php_exam_db`)
- `DB_USER` (par défaut `php_exam_user`)
- `DB_PASS` (par défaut `Azerty1234`)
- `BASE_URL` : mettez `http://localhost` si vous accéderez au site localement.

Exemple pour mettre `BASE_URL` en `http://localhost` via commande :

```bash
sudo sed -i "s|define('BASE_URL', '');|define('BASE_URL', 'http://localhost');|" /var/www/php_exam/config/config.php
```

Si vous avez modifié la base de données ou l'utilisateur, adaptez ces valeurs dans `config/config.php`.

## 5) Configurer Apache (virtual host)

Créer un fichier de site Apache :

```bash
sudo tee /etc/apache2/sites-available/php_exam.conf > /dev/null <<'EOF'
<VirtualHost *:80>
    ServerName localhost
    DocumentRoot /var/www/php_exam

    <Directory /var/www/php_exam>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/php_exam_error.log
    CustomLog ${APACHE_LOG_DIR}/php_exam_access.log combined
</VirtualHost>
EOF

sudo a2ensite php_exam.conf
sudo a2enmod rewrite
sudo systemctl reload apache2
```

Notes :

- `AllowOverride All` permet d'utiliser les `.htaccess` si le projet en a.
- Si vous voulez un nom de domaine local (ex: `php_exam.test`), ajoutez une entrée dans `/etc/hosts` et changez `ServerName`.

## 6) Droits et permissions

Apache tourne sous l'utilisateur `www-data`. Donnez la propriété et les droits au répertoire :

```bash
sudo chown -R www-data:www-data /var/www/php_exam
sudo find /var/www/php_exam -type d -exec chmod 755 {} \\\;
sudo find /var/www/php_exam -type f -exec chmod 644 {} \\\;
```

Si votre application a besoin d'un dossier `uploads` modifiable, ajustez-le séparément :

```bash
sudo mkdir -p /var/www/php_exam/uploads
sudo chown -R www-data:www-data /var/www/php_exam/uploads
sudo chmod -R 775 /var/www/php_exam/uploads
```

## 7) Vérifier les exigences PHP dans le code

Le projet utilise PDO/MySQL (`/var/www/php_exam/config/database.php`). Assurez-vous que l'extension `pdo_mysql` est active (généralement fournie par `php-mysql`). Pour vérifier :

```bash
php -m | grep -i pdo
php -m | grep -i mysql
```

## 8) Redémarrer Apache et vérifier

```bash
sudo systemctl restart apache2
sudo systemctl status apache2 --no-pager
```

Ouvrez votre navigateur à :

- `http://localhost/`
- ou l'URL que vous avez configurée

Si vous voyez la page d'accueil du projet (contrôleur `HomeController` / `views/home/index.php`), l'installation est réussie.

## 9) Dépannage rapide

- Erreur DB : vérifiez `config/config.php` et que l'utilisateur MySQL existe.
- Permission denied : vérifiez la propriété `www-data:www-data` et les droits.
- 500 Internal Server Error : vérifiez les logs Apache :

```bash
sudo tail -n 200 /var/log/apache2/php_exam_error.log
sudo tail -n 200 /var/log/apache2/error.log
```

## 10) Commandes utiles supplémentaires

- Recharger la configuration Apache : `sudo systemctl reload apache2`
- Lancer MySQL en ligne de commande : `sudo mysql -u root -p`
- Voir les logs d'accès : `sudo tail -f /var/log/apache2/php_exam_access.log`

---

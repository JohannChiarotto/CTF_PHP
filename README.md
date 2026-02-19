# Plateforme de Bug Bounty / CTF

## Membres 

- Dylan THOMAS
- Theo DARRIBEAU
- Johann CHIAROTTO
**Année :** B2 Cybersécurité

## Description

Ce projet à pour but de créer une plateforme de CTF entièrement avec un back-end en `php`, on y retrouve des pseudo challenge que les utilisateurs peuvent acheter, résoudre et soumettre via un flag. Le système supporte les rôles `user`, `creator`, `admin`, un panier d'achat léger, facturation minimale, et un leaderboard.

## Fonctionnalités principales

- Inscription / connexion (session + BCrypt)
- Création de challenges par des créateurs
- Achat de challenges (panier + facturation)
- Soumission de flags (hashés avec BCrypt)
- Leaderboard (admin exclu)
- Pages: Accueil, Détail challenge, Panier, Compte, Scoreboard, Admin

## Prérequis

- PHP 8.0+
- PDO MySQL (extension `pdo_mysql`)
- MySQL/MariaDB

## Installation rapide (local)

⚠️ L'installation et le lancement du projet peut être faite de manière différente en fonction de votre OS.

1. Copier le projet sur votre machine

2. Configurer la base de données
   - Créez une base `php_exam_db` (ou modifiez `config/database.php`)
   - Importez le dump si besoin: `tools/database.sql` contient un export utile

3. Configuration
   - Vérifiez `config/config.php` et `config/database.php` pour adapter l'accès DB

4. Démarrer le serveur PHP intégré (développement)

```bash
cd /var/www/php_exam
php -S localhost:8000
```
Puis ouvrir `http://localhost:8000/`.

## Structure du projet

- `index.php` — routeur frontal
- `config/` — configuration DB et application
- `controllers/` — contrôleurs MVC
- `models/` — accès aux données et logique métier
- `views/` — templates PHP
- `core/` — classes de base (Router, Controller, Auth, Middleware, Session, Security)
- `tools/` — dumps et utilitaires (non déployés en prod)

## Sécurité et bonnes pratiques

- Mots de passe et flags hashés avec `password_hash`/`password_verify`
- Utilisation de `Prepared statements` pour prévenir les injections SQL
- Jetons CSRF sur les formulaires sensibles
- Ne publiez pas `config/config.php` si il contient des identifiants réels — utilisez des variables d'environnement en production
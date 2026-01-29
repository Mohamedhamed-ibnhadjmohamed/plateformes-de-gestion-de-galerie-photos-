# 📸 Photo Gallery Platform

Une plateforme web moderne et complète pour la gestion de galeries photos, développée en PHP pur avec une architecture MVC propre.

## 🚀 Fonctionnalités

### 🎯 Core Features
- **Gestion des Albums** : Création, modification, suppression d'albums photos
- **Upload de Photos** : Upload multiple avec génération automatique de miniatures
- **Système de Tags** : Organisation des photos avec des tags personnalisés
- **Favoris** : Marquer ses photos préférées
- **Commentaires** : Système de commentaires avec modération
- **Recherche** : Recherche avancée dans les photos et albums

### 👥 User Management
- **Authentification** : Inscription, connexion, déconnexion
- **Profils Utilisateurs** : Gestion du profil personnel
- **Rôles** : Système d'administration avec rôles utilisateur/admin
- **Activity Log** : Suivi des activités des utilisateurs

### 🎨 Interface & UX
- **Design Responsive** : Adaptatif mobile/desktop avec CSS3 Grid/Flexbox
- **Lightbox Interactive** : Visualisation plein écran des photos
- **Navigation Intuitive** : Interface moderne et facile à utiliser
- **Notifications** : Système de messages flash
- **Search & Filter** : Filtrage et recherche en temps réel

### 🔧 Technical Features
- **Architecture MVC** : Séparation claire des responsabilités
- **URLs Propres** : Système de routing avec réécriture d'URL
- **Upload Sécurisé** : Validation des fichiers et gestion des erreurs
- **Miniatures Automatiques** : Génération de thumbnails avec GD
- **Pagination** : Navigation efficace dans les grands contenus

## 🛠️ Stack Technique

### Backend
- **PHP 8.0+** : Architecture MVC personnalisée
- **MySQL/MariaDB** : Base de données avec PDO
- **Apache** : Serveur web avec .htaccess
- **GD Library** : Traitement d'images

### Frontend
- **HTML5** : Sémantique moderne et accessible
- **CSS3** : Grid, Flexbox, animations, variables CSS
- **JavaScript ES6+** : Vanilla JS sans framework
- **Responsive Design** : Mobile-first approach

### Architecture
- **Pattern MVC** : Models, Views, Controllers
- **Routing** : Système de routes personnalisé
- **Helpers** : Fonctions utilitaires (auth, upload, image)
- **Configuration** : Gestion centralisée des paramètres

## 📁 Structure du Projet

```
plateformes-de-gestion-de-galerie-photos/
│
├── public/                  # Accès web (Document Root)
│   ├── index.php           # Front controller
│   ├── .htaccess           # Réécriture d'URL
│   ├── uploads/            # Photos uploadées
│   │   ├── albums/         # Photos par album
│   │   └── thumbs/         # Miniatures
│   └── assets/             # CSS, JS, images publiques
│       ├── css/style.css
│       ├── js/main.js
│       └── images/
│
├── app/                    # Code source MVC
│   ├── Controllers/        # Contrôleurs
│   ├── Models/            # Modèles de données
│   ├── Views/             # Templates HTML
│   ├── Core/              # Classes MVC de base
│   └── Helpers/           # Fonctions utilitaires
│
├── config/                # Configuration
│   ├── config.php         # DB + settings
│   └── routes.php         # Définition des routes
│
├── database/              # Base de données
│   ├── photo_gallery.sql  # Schéma complet
│   └── migrations/        # Scripts de migration
│
└── logs/                  # Logs d'activité
```

## 🚀 Installation

### Prérequis
- PHP 8.0 ou supérieur
- MySQL/MariaDB 5.7+
- Apache 2.4+ (avec mod_rewrite)
- Extensions PHP : PDO, GD, mbstring

### Étapes d'Installation

1. **Clonez le dépôt**
   ```bash
   git clone https://github.com/username/plateformes-de-gestion-de-galerie-photos.git
   cd plateformes-de-gestion-de-galerie-photos
   ```

2. **Configurez la base de données**
   ```sql
   CREATE DATABASE photo_gallery;
   -- Importez database/photo_gallery.sql
   ```

3. **Configurez l'application**
   ```php
   // Éditez config/config.php
   'db_host' => 'localhost',
   'db_name' => 'photo_gallery',
   'db_user' => 'votre_user',
   'db_pass' => 'votre_password',
   ```

4. **Configurez le serveur web**
   ```apache
   # DocumentRoot doit pointer vers /public
   # Assurez-vous que mod_rewrite est activé
   ```

5. **Permissions des dossiers**
   ```bash
   chmod 755 public/uploads/
   chmod 755 logs/
   ```

6. **Accédez à l'application**
   Ouvrez votre navigateur sur `http://localhost/`

## 🎯 Utilisation

### Premiers Pas
1. **Créez un compte** : `/users/register`
2. **Connectez-vous** : `/users/login`
3. **Créez votre premier album** : `/albums/create`
4. **Uploadez des photos** : `/photos/upload`

### Administration
- **Compte admin par défaut** : `admin@example.com` / `admin123`
- **Panneau d'administration** : `/admin/dashboard`

## 🔧 Configuration

### Personnalisation
- **Thème** : Modifiez `assets/css/style.css`
- **Upload limits** : `config/config.php` → `max_file_size`
- **Thumbnails** : `config/config.php` → dimensions
- **Notifications** : Configuration email dans `config.php`

### Sécurité
- **Sessions** : Durée configurable dans `config.php`
- **Upload validation** : Extensions et tailles limitées
- **CSRF protection** : Tokens intégrés
- **SQL Injection** : Requêtes préparées avec PDO

## 📊 Base de Données

### Tables Principales
- `users` : Utilisateurs et rôles
- `albums` : Galeries photos
- `photos` : Fichiers et métadonnées
- `tags` : Étiquettes de classification
- `favorites` : Favoris des utilisateurs
- `comments` : Commentaires et modération
- `activity_logs` : Historique des actions

### Relations
- Users → Albums (1:N)
- Users → Photos (1:N)
- Albums → Photos (1:N)
- Photos ↔ Tags (N:M)
- Users ↔ Favorites (1:N)
- Users ↔ Comments (1:N)

## 🚀 Développement

### Architecture MVC
- **Controllers** : Logique métier et routing
- **Models** : Accès aux données et business rules
- **Views** : Templates HTML avec PHP
- **Core** : Classes de base (Database, Model, Controller)

### Bonnes Pratiques
- **PSR-4** compatible (autoloading)
- **Séparation des responsabilités**
- **Code commenté et documenté**
- **Gestion d'erreurs centralisée**
- **Logs d'activité**

### Extensions Possibles
- **API REST** : Endpoints JSON
- **OAuth2** : Authentification sociale
- **WebSockets** : Notifications temps réel
- **Cloud Storage** : AWS S3 integration
- **CDN** : Optimisation des assets

## 🤝 Contribuer

Les contributions sont les bienvenues !

1. Fork le projet
2. Créez une branche (`git checkout -b feature/nouvelle-fonctionnalite`)
3. Commitez vos changements (`git commit -am 'Ajout de la fonctionnalité X'`)
4. Push vers la branche (`git push origin feature/nouvelle-fonctionnalite`)
5. Créez une Pull Request

## 📝 License

Ce projet est sous license MIT - voir le fichier [LICENSE](LICENSE) pour plus de détails.

## 🆘 Support

Pour toute question ou problème :
- **Issues GitHub** : Signalez les bugs
- **Documentation** : Consultez le code commenté
- **Wiki** : Guides et tutoriels

## 🌟 Acknowledgments

- **PHP Community** : Ressources et documentation
- **MDN Web Docs** : Références web standards
- **Stack Overflow** : Support communautaire

---

**Développé avec ❤️ en PHP pur**

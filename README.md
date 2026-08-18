# Dossier Technique - SafeMarket

SafeMarket est une plateforme de marché (marketplace) sécurisée intégrant un système de séquestre (ESCROW), un portefeuille numérique (wallet), un chat de négociation en temps réel et un processus de vérification KYC (Know Your Customer).

---

## 🛠️ Stack Technique

- **Backend** : Laravel 11 (PHP 8.2+)
- **Frontend** : Laravel Blade & Livewire (Alpine.js)
- **Base de données** : SQLite (pour le développement local et la simplicité de déploiement)
- **Design/Styles** : Tailwind CSS v3 / v4

---

## 📦 Fonctionnalités Clés

1. **Système de Séquestre (ESCROW)** : Les fonds de l'acheteur sont bloqués sur la plateforme jusqu'à confirmation de réception de la commande via un code de libération unique à 8 caractères.
2. **Négociations de Prix** : Un acheteur peut faire des offres de prix sur des articles. Le vendeur peut accepter, refuser ou négocier via un chat interactif intégré.
3. **Wallet Intégré** : Gestion des soldes avec déduction automatique des achats et versement des gains net de frais (taxe plateforme fixe de 5%).
4. **Vérification KYC** : Processus d'approbation administrative des pièces d'identité soumises par les utilisateurs pour obtenir le statut de vendeur certifié.
5. **API REST Complète** : Endpoints sécurisés via Laravel Sanctum pour toutes les opérations (profil, produits, commandes, litiges, KYC, avis).

---

## 🚀 Publier le projet sur GitHub (Dépôt Privé)

Suivez ces étapes pour héberger votre code sur un dépôt privé GitHub de manière sécurisée :

### 1. Initialiser le dépôt local
Ouvrez votre terminal dans le dossier racine du projet et exécutez :
```bash
git init
```

### 2. Configurer le fichier `.gitignore`
Assurez-vous que le fichier `.gitignore` à la racine contient bien les lignes suivantes pour éviter de pousser des fichiers sensibles ou temporaires (comme la base de données SQLite locale ou les dépendances) :
```text
/vendor
/node_modules
/public/storage
/storage/*.key
.env
.env.backup
.phpunit.result.cache
/database/database.sqlite
```

### 3. Ajouter et commiter les fichiers
Ajoutez tous les fichiers autorisés et effectuez votre premier commit :
```bash
git add .
git commit -m "Initial commit - Projet SafeMarket complètement fonctionnel"
```

### 4. Lier au dépôt privé GitHub
1. Allez sur [GitHub](https://github.com) et créez un nouveau dépôt.
2. Sélectionnez **Private** (Privé) lors de la création pour restreindre l'accès.
3. Copiez l'URL du dépôt (ex: `https://github.com/votre-utilisateur/safemarket.git`).
4. Exécutez les commandes suivantes dans votre terminal local :
```bash
git remote add origin https://github.com/votre-utilisateur/safemarket.git
git branch -M main
git push -u origin main
```

---

## ⚙️ Installation et Configuration Locale

Suivez ces étapes pour installer et lancer le projet sur une nouvelle machine :

### Prérequis
- **PHP** (version 8.2 ou supérieure) avec les extensions `pdo_sqlite`, `mbstring`, `openssl`, `xml`, `curl` activées.
- **Composer** pour la gestion des dépendances PHP.
- **Node.js & NPM** pour les assets frontend.

### Étape 1 : Cloner le dépôt
Clonez le dépôt privé depuis GitHub :
```bash
git clone https://github.com/votre-utilisateur/safemarket.git
cd safemarket
```

### Étape 2 : Configurer les variables d'environnement
Copiez le fichier d'exemple pour créer votre fichier `.env` local :
```bash
cp .env.example .env
```
Assurez-vous que la configuration de la base de données dans votre `.env` pointe bien vers SQLite :
```env
DB_CONNECTION=sqlite
```

### Étape 3 : Installer les dépendances PHP et JS
Installez les bibliothèques backend :
```bash
composer install
```
Installez les dépendances frontend et compilez les assets en mode production :
```bash
npm install
npm run build
```

### Étape 4 : Générer la clé d'application
Générez la clé de sécurité de l'application Laravel :
```bash
php artisan key:generate
```

### Étape 5 : Initialiser la base de données
1. Créez un fichier vide pour la base de données SQLite dans le dossier `database` :
   - *Sur Windows (PowerShell)* :
     ```powershell
     New-Item -Path database\database.sqlite -ItemType File
     ```
   - *Sur macOS / Linux* :
     ```bash
     touch database/database.sqlite
     ```
2. Exécutez les migrations et alimentez la base de données avec les données de démonstration (rôles, catégories, utilisateur test) :
```bash
php artisan migrate:fresh --seed
```

### Étape 6 : Lancer le serveur de développement
Démarrez le serveur local intégré à Laravel :
```bash
php artisan serve
```
L'application sera accessible dans votre navigateur à l'adresse : `http://127.0.0.1:8000`.

---

## 🔑 Identifiants de test par défaut
Après avoir exécuté le seeder (`--seed`), trois comptes de test distincts sont créés avec des rôles et soldes bien séparés :

1. **Administrateur (Admin) :**
   - **Email** : `admin@example.com`
   - **Mot de passe** : `password`
   - **Rôle** : Gestion de la plateforme (Validation KYC, résolutions de litiges, catégories).

2. **Vendeur / Commerçant (Vendor) :**
   - **Email** : `vendor@example.com`
   - **Mot de passe** : `password`
   - **Solde initial (Wallet)** : 250 000,00 FCFA
   - **Rôle** : Publication de produits, réception des commandes, gestion d'expédition (KYC déjà vérifié).

3. **Acheteur / Client (Buyer) :**
   - **Email** : `buyer@example.com`
   - **Mot de passe** : `password`
   - **Solde initial (Wallet)** : 500 000,00 FCFA
   - **Rôle** : Recherche et achat de produits, négociation de prix, libération des fonds d'escrow.

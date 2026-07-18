# 🎓 SACourses – Plateforme E-Learning

![SACourses](screenshots/home.png)

## 📌 Description

**SACourses** est une plateforme d'apprentissage en ligne (*E-Learning*) conçue pour faciliter l'accès à des contenus pédagogiques interactifs.

La plateforme permet aux utilisateurs de consulter des cours, regarder des vidéos éducatives et participer à des quiz afin de renforcer leurs connaissances.

Elle dispose également d'un espace d'administration permettant de gérer les contenus et les ressources pédagogiques.

---

## ✨ Fonctionnalités

### 👨‍🎓 Utilisateur

* 🏠 Accueil et présentation de la plateforme
* 📚 Consultation des cours
* 🎥 Accès aux vidéos pédagogiques
* 📝 Participation aux quiz
* 📊 Évaluation des connaissances
* 🔎 Navigation entre les différentes ressources

### 👨‍💼 Administration

* 📋 Gestion des cours
* 🎥 Gestion des vidéos
* 📝 Gestion des quiz
* 📂 Gestion des ressources pédagogiques
* 👥 Gestion des utilisateurs

---

## 🛠️ Technologies utilisées

| Technologie | Utilisation                   |
| ----------- | ----------------------------- |
| HTML5       | Structure des pages           |
| CSS3        | Design et mise en page        |
| JavaScript  | Interactivité                 |
| PHP         | Développement Backend         |
| MySQL       | Gestion de la base de données |
| XAMPP       | Serveur local                 |

---

## 📂 Structure du projet

```text
SACourses/
│
├── admin/              # Interface d'administration
├── components/         # Composants réutilisables
├── css/                # Fichiers CSS
├── image/              # Images et vidéos
├── images/             # Ressources graphiques
├── js/                 # Scripts JavaScript
├── plateforme/         # Fonctionnalités principales
├── quiz/               # Module des quiz
├── uploads/            # Fichiers téléchargés
│
├── screenshots/        # Captures d'écran
├── README.md           # Documentation du projet
└── .gitignore          # Fichiers ignorés par Git
```

---

## ⚙️ Installation

### 1. Cloner le projet

```bash
git clone https://github.com/saadia-achaka/SACourses.git
```

### 2. Placer le projet dans XAMPP

Copier le dossier `SACourses` dans :

```text
C:\xampp\htdocs\
```

### 3. Démarrer XAMPP

Lancer :

* Apache
* MySQL

### 4. Configurer la base de données

Ouvrir :

```text
http://localhost/phpmyadmin
```

Créer une base de données pour le projet et importer le fichier SQL de la base de données.

> Si aucun fichier SQL n'est encore présent dans le projet, il est recommandé d'ajouter un fichier `database.sql` à la racine du projet.

### 5. Configurer la connexion à la base de données

Modifier les paramètres de connexion à la base de données selon votre configuration locale.

### 6. Lancer l'application

Ouvrir dans le navigateur :

```text
http://localhost/SACourses/
```

---

## 📸 Captures d'écran

### 🏠 Page d'accueil

![Page d'accueil](screenshots/home.png)

### 📚 Page des cours

![Cours](screenshots/courses.png)

### 🎥 Cours vidéo

![Vidéo](screenshots/video.png)

### 📝 Quiz

![Quiz](screenshots/quiz.png)

### 👨‍💼 Dashboard Administrateur

![Administration](screenshots/admin.png)

---

## 🚀 Améliorations futures

* 🔐 Amélioration de la sécurité et de l'authentification
* 📱 Adaptation complète aux appareils mobiles
* 📊 Ajout d'un système de suivi de progression
* 🏆 Système de récompenses et de badges
* 💬 Ajout d'un système de commentaires
* 📧 Notifications pour les utilisateurs
* ☁️ Déploiement de la plateforme en ligne

---

## 👩‍💻 Auteur

**Saadia Achaka**

🎓 Master en Informatique et Télécommunications – Génie Logiciel

🔗 GitHub : https://github.com/saadia-achaka

---

## 📄 Licence

Ce projet est développé dans un cadre académique et personnel.

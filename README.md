# 🚀 HooloyBooked

## 📖 Description

Ce projet est une application **Symfony 7.2** développée en **PHP 8.4**, qui implémente une architecture **Hexagonale (Ports & Adapters)** avec **CQRS** et expose une **API REST**.

Il gère des entités comme :
- **Campus** et ses **Slots**
- **FoodTrucks**
- **Bookings** (réservations)
- **Unavailabilities** (indisponibilités avec stratégie `day`, `month`, `year`, `specific_date`)

L’objectif est de fournir une API propre, testée et extensible, adaptée à la mise en production dans un environnement Dockerisé.

---

## 🛠️ Technologies

- **PHP 8.4**
- **Symfony 7.2**
- **Doctrine ORM (mapping XML)**
- **PostgreSQL**
- **Docker**
- **Mailhog** pour tester l’envoi d’e-mails
- **PHPUnit** pour les tests unitaires et fonctionnels

---

## 📂 Architecture Hexagonale


- **Domain** : cœur métier, indépendant de Symfony/Doctrine.
- **Application** : orchestration (Handlers pour Commands & Queries).
- **Infrastructure** : implémentations techniques (Doctrine, Mailer, API).

---

## 🚀 Installation

### 1️⃣ Cloner le projet
```bash
git clone https://github.com/nsabas/HoolyBooked.git
cd HoolyBooked
```


### 2️⃣ Lancer Docker
```bash
make start
```

### 3️⃣ Installer les dépendances
```bash
docker-compose exec php composer install
```

### 4️⃣ Créer la base de données et lancer les migrations
```bash
make init_db
```

---

## 📡 API & Documentation

L'API est exposée via **API Platform**.

**Documentation Swagger/OpenAPI** disponible sur :
👉 **http://localhost/api/docs**

---

## 📧 Emails

Les emails envoyés par l'application sont interceptés par **Mailhog** :
👉 **http://localhost:8025**

---

## 🧪 Tests

Lancer la suite de tests :
```bash
make tests
```

---

## 👨‍💻 Développement

- **PHP CS Fixer** pour la qualité du code
- **Symfony Profiler** pour le debug
- **Intégration continue (CI/CD)** prévue avec GitHub Actions

---

## 🔧 Architecture Détaillée

### Domain Layer
- **Models** : `Campus`, `Slot`, `FoodTruck`, `Booking`, `Unavailability`
- **Services** : `FoodTruckService`, `SlotService`, `UnavailabilityChecker`
- **Enums** : Types de stratégies d'indisponibilité

### Application Layer (CQRS)
- **Commands** : Actions qui modifient l'état
- **Queries** : Requêtes en lecture seule
- **Handlers** : Orchestration de la logique métier
- **Ports** : Interfaces pour la communication avec l'infrastructure

---

## 🐳 Services Docker

| Service | Port | Description |
|---------|------|-------------|
| **php** | - | Application Symfony PHP 8.4 |
| **nginx** | 80 | Serveur web |
| **postgres** | 5432 | Base de données PostgreSQL |
| **mailhog** | 8025 | Interface web pour les emails |

---

## 🛡️ Bonnes Pratiques

- ✅ **Architecture Hexagonale** pour la séparation des responsabilités
- ✅ **CQRS** pour une meilleure organisation des opérations
- ✅ **Tests unitaires** avec PHPUnit et mocks
- ✅ **Mapping XML Doctrine** pour découpler les entités
- ✅ **Docker** pour un environnement de développement reproductible

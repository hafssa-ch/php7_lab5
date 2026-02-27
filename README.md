# LAB 5 — Accès aux Données avec PDO (CRUD sécurisé)

## 📌 Description

Ce lab est une application PHP orientée objet permettant de gérer des **filières** et des **étudiants** via une base de données MySQL.

Il met en pratique :

- L’architecture en couches (Entity / DAO / Database / Log)
- L’utilisation de PDO
- La gestion des transactions (Commit / Rollback)
- La gestion des exceptions
- Un autoload personnalisé

---

## 🏗️ Architecture du projet

Le projet est organisé selon une séparation claire des responsabilités :

- **Entity** : Représentation des objets métiers (Filière, Étudiant)
- **DAO** : Gestion des opérations d’accès aux données
- **Database** : Centralisation de la connexion à la base de données
- **Log** : Gestion et enregistrement des erreurs
- **Bootstrap** : Initialisation globale du projet

Cette organisation améliore la lisibilité, la maintenabilité et l’évolutivité du code.

---

## 🗄️ Base de données

L’application repose sur :

- Une table *Filière*
- Une table *Étudiant*
- Une relation entre étudiant et filière
- Des contraintes d’unicité (email, CNE, code)
<img width="841" height="279" alt="image" src="https://github.com/user-attachments/assets/2e5039ff-db73-4da5-9c85-ccf61f942e1b" />

---

## 🔄 Gestion des transactions

Les opérations importantes sont exécutées dans une transaction afin de garantir l’intégrité des données :

- En cas de succès → validation des modifications
- En cas d’erreur → annulation automatique des opérations

---

## 📝 Gestion des erreurs

Toutes les erreurs liées à la base de données sont :

- Capturées via les exceptions PDO
- Enregistrées dans un fichier de log
- Séparées de la logique métier

Cela permet un meilleur suivi et une maintenance plus professionnelle.

---

## 🎯 Objectifs pédagogiques

Ce projet permet de pratiquer :

- La programmation orientée objet en PHP
- Le pattern DAO
- Les transactions SQL
- La gestion des exceptions
- L’organisation d’un projet structuré

---

## ✅ Fonctionnalités

- Ajout d’une filière
- Ajout d’un étudiant
- Gestion des erreurs
- Test de rollback
- Architecture claire et modulaire

---

## 👨‍💻 Resultat

<img width="708" height="232" alt="image" src="https://github.com/user-attachments/assets/238049f6-dc25-4898-8f89-8bcf909f3264" />

# EcoRide

Mon projet de formation

# Déployer l'application en local

## 1️⃣ Récupérer le projet

1. Ouvrir le dépôt GitHub : https://github.com/Alex-Yali/EcoRide
2. Cliquer sur **Code → Download ZIP**
3. Dézipper le dossier dans `C:\Users\<nom_utilisateur>\projet\`
4. Renommer le dossier en `EcoRide`
5. Copier le fichier `.env.example` et le renommer en `.env`
6. Modifier le fichier `.env` si nécessaire (mots de passe, ports, etc.)

---

## 2️⃣ Ouvrir le projet dans VS Code

1. Télécharger et installer **Visual Studio Code** via le Microsoft Store
2. Lancer VS Code
3. Cliquer sur **File → Open Folder** et sélectionner le dossier `EcoRide`

> L’ensemble du code apparaît dans VS Code.

---

## 3️⃣ Installer et activer Docker

1. Télécharger et installer **Docker Desktop** : https://www.docker.com/products/docker-desktop/
2. Lancer Docker Desktop
3. Vérifier que Docker fonctionne en ouvrant le terminal VS Code (Ctrl + ù) et en tapant :

```powershell
docker --version
```

---

## 4️⃣ Construire et lancer les conteneurs

1. Ouvrir le terminal intégré dans VS Code (Ctrl + ù)
2. Lancer la commande pour reconstruire les images :

```powershell
docker compose build --no-cache
```

⚠️ Attendre que la build se termine complètement

3. Lancer les conteneurs en arrière-plan :

```powershell
docker compose up -d
```

---

## 5️⃣ Importer la base MongoDB

1. Lancer la commande :

```powershell
docker exec -i ecoride-mongo mongoimport --db ecoride --collection preferences --file /tmp/database/ecoride.preferences.json --jsonArray
```

> Cette commande importe la collection `preferences` dans la base `ecoride`.

---

## 6️⃣ Accéder à l'application

1. Ouvrir votre navigateur web
2. Aller à l’adresse : http://localhost:8000/index.php

> Vous avez accès à l’application EcoRide

---

### 💡 Notes / Astuces

- Pour MySQL, utilisez Adminer : [http://localhost:8080](http://localhost:8080)
- Pour MongoDB, utilisez Mongo-Express : [http://localhost:8081](http://localhost:8081)
- Ne jamais mettre `localhost` dans le `.env` pour MongoDB ou MySQL en Docker. Utilisez toujours les noms des services (`mongo` et `db`).
- Pour accéder à l'application en production : https://www.ecoride.page/

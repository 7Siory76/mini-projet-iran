# Back Office - PHP + Apache + PostgreSQL (Docker)

## Structure

```text
Back_office/
├─ app/
│  ├─ config/
│  │  └─ database.php
│  ├─ public/
│  │  ├─ .htaccess
│  │  └─ index.php
│  └─ src/
│     └─ Database/
│        └─ Connection.php
├─ database/
│  └─ init/
│     └─ 01_init.sql
├─ docker/
│  └─ php-apache/
│     ├─ Dockerfile
│     └─ vhost.conf
├─ .env
├─ .env.example
└─ docker-compose.yml
```

## Démarrage

1. Ouvrir un terminal dans `Back_office`
2. Lancer :

```bash
docker compose up --build -d
```

3. Ouvrir : `http://localhost:8080`

## Arrêt

```bash
docker compose down
```

## Infos utiles

- Service Apache/PHP : `app`
- Service PostgreSQL : `db`
- DB par défaut : `backoffice_db`
- User par défaut : `backoffice_user`
- Port web : `8080`
- Port PostgreSQL : `5432`


psql -U backoffice_user -d backoffice_db
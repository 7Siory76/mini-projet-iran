<?php

return [
    'host' => getenv('DB_HOST') ?: 'backoffice_db',
    'port' => getenv('DB_PORT') ?: '5432',
    'name' => getenv('DB_NAME') ?: (getenv('POSTGRES_DB') ?: 'backoffice_db'),
    'user' => getenv('DB_USER') ?: (getenv('POSTGRES_USER') ?: 'backoffice_user'),
    'password' => getenv('DB_PASSWORD') ?: (getenv('POSTGRES_PASSWORD') ?: 'backoffice_pass'),
];

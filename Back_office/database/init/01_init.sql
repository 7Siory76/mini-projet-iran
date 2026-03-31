CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(120) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL DEFAULT $hash$$2y$10$rI5rvf.ondXlJ9r1fl/jyO7iwGKyNQqa1A01CFKfowOmVb7tO/zwC$hash$,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Mot de passe "admin" (hash bcrypt)
INSERT INTO users (full_name, email, password)
VALUES ('Admin BackOffice', 'admin@backoffice.local', $hash$$2y$10$rI5rvf.ondXlJ9r1fl/jyO7iwGKyNQqa1A01CFKfowOmVb7tO/zwC$hash$)
ON CONFLICT (email) DO NOTHING;



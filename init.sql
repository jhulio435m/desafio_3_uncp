CREATE TABLE IF NOT EXISTS contacts (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    office VARCHAR(255),
    phone VARCHAR(50),
    email VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS requests (
    id SERIAL PRIMARY KEY,
    ticket_id VARCHAR(10) UNIQUE NOT NULL,
    representative_name VARCHAR(255) NOT NULL,
    representative_dni VARCHAR(20) NOT NULL,
    institution_name VARCHAR(255) NOT NULL,
    institution_type VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(255) NOT NULL,
    status VARCHAR(50) DEFAULT 'Recibido',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO contacts (name, office, phone, email) VALUES 
('Unidad de Modernización - Contacto 1', 'Sede Central', '939205127', 'modernizacion1@uncp.edu.pe'),
('Unidad de Modernización - Contacto 2', 'Sede Central', '929241557', 'modernizacion2@uncp.edu.pe'),
('Unidad de Modernización - Contacto 3', 'Sede Central', '912301577', 'modernizacion3@uncp.edu.pe'),
('Mtra. Rosario Llancari', 'Directora DECPS', '+51 64 481060', 'proyeccion@uncp.edu.pe');

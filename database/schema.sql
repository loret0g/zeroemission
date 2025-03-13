USE bdvq6h7z2czsqus1jevh;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    surname VARCHAR(50) NOT NULL,
    email VARCHAR(70) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabla de parkings
CREATE TABLE IF NOT EXISTS parkings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    parking_name VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabla de parking spots
CREATE TABLE IF NOT EXISTS parking_spots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    parking_id INT NOT NULL,
    spot_number INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parking_id) REFERENCES parkings(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabla de vehicles
CREATE TABLE IF NOT EXISTS vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(20) NOT NULL,
    parking_id INT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    spanish_name VARCHAR(50) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parking_id) REFERENCES parkings(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabla de reservations
CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    vehicle_id INT NOT NULL,
    parking_spot_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE,
    FOREIGN KEY (parking_spot_id) REFERENCES parking_spots(id) ON DELETE CASCADE
) ENGINE=InnoDB;
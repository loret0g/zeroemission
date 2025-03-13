-- Insertar parkings
INSERT INTO parkings (parking_name) VALUES ('Paseo Mallorca'), ('Compte Empuries');

-- Insertar plazas de parking
INSERT INTO parking_spots (parking_id, spot_number) VALUES 
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5),
(2, 1), (2, 2), (2, 3), (2, 4), (2, 5);

-- Insertar vehículos
INSERT INTO vehicles (type, parking_id, image, spanish_name) VALUES
('bike', 1, '/assets/media/img/bicicleta.webp', 'Bicicleta'),
('bike', 2, '/assets/media/img/bicicleta.webp', 'Bicicleta'),
('scooter', 1, '/assets/media/img/patinete.webp', 'Patinete eléctrico'),
('scooter', 2, '/assets/media/img/patinete.webp', 'Patinete eléctrico'),
('motorbike', 1, '/assets/media/img/moto.webp', 'Motocicleta eléctrica'),
('motorbike', 2, '/assets/media/img/moto.webp', 'Motocicleta eléctrica'),
('car', 1, '/assets/media/img/coche.webp', 'Coche eléctrico'),
('car', 2, '/assets/media/img/coche.webp', 'Coche eléctrico');

INSERT INTO vehicles (type, parking_id, image, spanish_name) VALUES
('motorbike', 1, '/assets/media/img/moto.webp', 'Motocicleta eléctrica');
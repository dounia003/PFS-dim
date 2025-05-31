-- Création de la base de données
CREATE DATABASE IF NOT EXISTS gestion_stagiaires CHARACTER SET utf8 COLLATE utf8_general_ci;
USE gestion_stagiaires;

-- Création de la table filiere
CREATE TABLE IF NOT EXISTS filiere (
  idFiliere INT(11) NOT NULL AUTO_INCREMENT,
  nomFiliere VARCHAR(50) NOT NULL,
  niveau VARCHAR(50) NOT NULL,
  PRIMARY KEY (idFiliere)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Création de la table stagiaire
CREATE TABLE IF NOT EXISTS stagiaire (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nom VARCHAR(50) NOT NULL,
  prenom VARCHAR(50) NOT NULL,
  civilite VARCHAR(1) NOT NULL,
  photo VARCHAR(100) DEFAULT NULL,
  idFiliere INT(11) NOT NULL,
  PRIMARY KEY (id),
  KEY idFiliere (idFiliere),
  CONSTRAINT stagiaire_ibfk_1 FOREIGN KEY (idFiliere) REFERENCES filiere (idFiliere) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Création de la table utilisateur
CREATE TABLE IF NOT EXISTS utilisateur (
  iduser INT(11) NOT NULL AUTO_INCREMENT,
  login VARCHAR(50) NOT NULL,
  email VARCHAR(255) NOT NULL,
  pwd VARCHAR(255) NOT NULL,
  role VARCHAR(50) NOT NULL DEFAULT 'VISITEUR',
  etat INT(11) NOT NULL DEFAULT 1,
  avatar VARCHAR(100) DEFAULT NULL,
  PRIMARY KEY (iduser),
  UNIQUE KEY login (login)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Insertion des données dans filiere
INSERT INTO filiere (nomFiliere, niveau) VALUES
('Développement Digital', 'Technicien Spécialisé'),
('Développement Web', 'Technicien'),
('Réseaux Informatiques', 'Technicien Spécialisé'),
('Systèmes et Réseaux', 'Technicien');

-- Insertion des données dans stagiaire
INSERT INTO stagiaire (nom, prenom, civilite, photo, idFiliere) VALUES
('Alaoui', 'Fatima', 'F', 'img1.jpg', 1),
('Bennani', 'Amal', 'F', 'img2.jpg', 1),
('Chaoui', 'Samira', 'F', 'img5.jpg', 2),
('Daoudi', 'Leila', 'F', 'img4.jpg', 2),
('El Fassi', 'Karim', 'M', 'img3.jpg', 3),
('Fathi', 'Sanaa', 'F', 'img6.jpg', 3),
('Ghali', 'Nadia', 'F', 'img7.jpg', 4),
('Hassani', 'Amina', 'F', 'img8.jpg', 4);

-- Insertion des données dans utilisateur (mot de passe '123' en MD5)
INSERT INTO utilisateur (login, email, pwd, role, etat, avatar) VALUES
('admin', 'admin@example.com', '202cb962ac59075b964b07152d234b70', 'ADMIN', 1, 'img3.jpg'),
('user1', 'user1@example.com', '202cb962ac59075b964b07152d234b70', 'VISITEUR', 1, 'img6.jpg');

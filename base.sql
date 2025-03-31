CREATE TABLE COMPANIES(
   ID INT AUTO_INCREMENT,
   NAME VARCHAR(255) NOT NULL,
   DESCRIPTION TEXT,
   MAIL VARCHAR(255),
   PHONE VARCHAR(15),
   PRIMARY KEY(ID)
);

CREATE TABLE ROLES(
   ID INT AUTO_INCREMENT,
   NAME VARCHAR(20) NOT NULL,
   PRIMARY KEY(ID)
);

CREATE TABLE PERMISSIONS(
   ID INT AUTO_INCREMENT,
   DESCRIPTION TEXT NOT NULL,
   PRIMARY KEY(ID)
);

CREATE TABLE OFFERS(
   ID INT AUTO_INCREMENT,
   TITLE VARCHAR(255) NOT NULL,
   RELEASE_DATE DATE NOT NULL,
   CITY VARCHAR(255),
   GRADE VARCHAR(15),
   BEGIN_DATE DATE,
   DURATION INT,
   RENUMBER DECIMAL(10,2),
   DESCRIPTION TEXT NOT NULL,
   ID_COMPANY INT NOT NULL,
   PRIMARY KEY(ID),
   FOREIGN KEY(ID_COMPANY) REFERENCES COMPANIES(ID)
);

CREATE TABLE USERS(
   ID INT AUTO_INCREMENT,
   LASTNAME VARCHAR(255) NOT NULL,
   FIRSTNAME VARCHAR(255) NOT NULL,
   PASSWORD VARCHAR(255) NOT NULL,
   MAIL VARCHAR(255) NOT NULL,
   ID_ROLE INT NOT NULL,
   PRIMARY KEY(ID),
   FOREIGN KEY(ID_ROLE) REFERENCES ROLES(ID)
);

CREATE TABLE APPLICATIONS(
   ID INT AUTO_INCREMENT,
   RELEASE_DATE DATE NOT NULL,
   CV VARCHAR(255) NOT NULL,
   LETTER TEXT,
   ID_OFFER INT NOT NULL,
   ID_USER INT NOT NULL,
   PRIMARY KEY(ID),
   FOREIGN KEY(ID_OFFER) REFERENCES OFFERS(ID),
   FOREIGN KEY(ID_USER) REFERENCES USERS(ID)
);

CREATE TABLE NOTE(
   ID_COMPANY INT,
   ID_USER INT,
   GRADE INT,
   PRIMARY KEY(ID_COMPANY, ID_USER),
   FOREIGN KEY(ID_COMPANY) REFERENCES COMPANIES(ID),
   FOREIGN KEY(ID_USER) REFERENCES USERS(ID)
);

CREATE TABLE LOVE(
   ID_OFFER INT,
   ID_USER INT,
   PRIMARY KEY(ID_OFFER, ID_USER),
   FOREIGN KEY(ID_OFFER) REFERENCES OFFERS(ID),
   FOREIGN KEY(ID_USER) REFERENCES USERS(ID)
);

CREATE TABLE OBTAIN(
   ID_ROLE INT,
   ID_PERMISSION INT,
   PRIMARY KEY(ID_ROLE, ID_PERMISSION),
   FOREIGN KEY(ID_ROLE) REFERENCES ROLES(ID),
   FOREIGN KEY(ID_PERMISSION) REFERENCES PERMISSIONS(ID)
);

-- Insertion de données dans COMPANIES
INSERT INTO COMPANIES (NAME, DESCRIPTION, MAIL, PHONE) VALUES
('TechCorp', 'Entreprise leader en technologie', 'contact@techcorp.com', '1234567890'),
('HealthPlus', 'Fournisseur de solutions de santé', 'info@healthplus.com', '0987654321'),
('EduSmart', 'Plateforme d''apprentissage en ligne', 'support@edusmart.com', '1122334455'),
('FinBank', 'Services financiers et bancaires', 'contact@finbank.com', '2233445566'),
('GreenEnergy', 'Fournisseur d''énergie renouvelable', 'info@greenenergy.com', '3344556677'),
('AutoTech', 'Innovation en automobile', 'contact@autotech.com', '4455667788'),
('BioHealth', 'Recherche médicale et biotechnologie', 'info@biohealth.com', '5566778899');

-- Insertion de données dans ROLES
INSERT INTO ROLES (NAME) VALUES
('Étudiant'),
('Pilote'),
('Administrateur');

-- Insertion de données dans PERMISSIONS
INSERT INTO PERMISSIONS (DESCRIPTION) VALUES
('Voir les candidatures'),
('Modifier les offres'),
('Gérer les utilisateurs'),
('Approuver les candidats'),
('Supprimer les offres'),
('Créer des rapports'),
('Accéder aux statistiques');

-- Insertion de données dans OFFERS
INSERT INTO OFFERS (TITLE, RELEASE_DATE, CITY, GRADE, BEGIN_DATE, DURATION, RENUMBER, DESCRIPTION, ID_COMPANY) VALUES
('Ingénieur Logiciel', '2025-03-01', 'Paris', 'Master', '2025-06-01', 6, 3500.00, 'Développement et maintenance des logiciels.', 1),
('Analyste de Données', '2025-02-15', 'Berlin', 'Licence', '2025-07-01', 12, 3200.50, 'Analyse des tendances et des rapports de données.', 2),
('Spécialiste Marketing', '2025-01-20', 'Londres', 'Licence', '2025-04-01', 6, 2800.00, 'Création de stratégies et campagnes marketing.', 3),
('Analyste Financier', '2025-04-10', 'New York', 'Master', '2025-07-15', 12, 4500.00, 'Analyse financière pour investissements.', 4),
('Consultant Environnemental', '2025-05-05', 'Amsterdam', 'Licence', '2025-08-01', 6, 3800.00, 'Conseil en durabilité environnementale.', 5),
('Développeur IA', '2025-06-15', 'Toronto', 'Master', '2025-09-01', 12, 5000.00, 'Développement d''intelligences artificielles avancées.', 6),
('Chercheur en Biotechnologie', '2025-07-20', 'Genève', 'Doctorat', '2025-10-01', 24, 6000.00, 'Recherche et innovation en biotechnologie.', 7);

-- Insertion de données dans USERS
INSERT INTO USERS (LASTNAME, FIRSTNAME, PASSWORD, MAIL, ID_ROLE) VALUES
('Dupont', 'Jean', 'hashed_password1', 'jean.dupont@example.com', 1),
('Martin', 'Sophie', 'hashed_password2', 'sophie.martin@example.com', 2),
('Bernard', 'Paul', 'hashed_password3', 'paul.bernard@example.com', 3);

-- Insertion de données dans APPLICATIONS
INSERT INTO APPLICATIONS (RELEASE_DATE, CV, LETTER, ID_OFFER, ID_USER) VALUES
('2025-03-05', 'cv_jean.pdf', 'Lettre de motivation.', 1, 3),
('2025-02-20', 'cv_sophie.pdf', 'Intéressée par le poste.', 2, 3),
('2025-04-15', 'cv_paul.pdf', 'Très motivé pour ce rôle.', 4, 3),
('2025-05-01', 'cv_claire.pdf', 'Passionnée par la durabilité.', 5, 3),
('2025-06-10', 'cv_camille.pdf', 'Expérience en intelligence artificielle.', 6, 3),
('2025-07-05', 'cv_thomas.pdf', 'Expert en recherche et innovation.', 7, 3);

-- Insertion de données dans NOTE
INSERT INTO NOTE (ID_COMPANY, ID_USER, GRADE) VALUES
(1, 3, 5),
(2, 3, 4),
(3, 3, 3),
(4, 3, 5),
(5, 3, 4),
(6, 3, 5),
(7, 3, 5);

-- Insertion de données dans LOVE
INSERT INTO LOVE (ID_OFFER, ID_USER) VALUES
(1, 3),
(2, 3),
(3, 3),
(4, 3),
(5, 3),
(6, 3),
(7, 3);

-- Insertion de données dans OBTAIN
INSERT INTO OBTAIN (ID_ROLE, ID_PERMISSION) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 1),
(2, 2),
(2, 4),
(2, 5),
(3, 1),
(3, 2),
(3, 3),
(3, 4),
(3, 5),
(3, 6),
(3, 7);
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nom` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `telephone` VARCHAR(30) DEFAULT NULL,
  `role` ENUM('annonceur','acheteur','moderateur') NOT NULL DEFAULT 'annonceur',
  `date_inscription` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nom` VARCHAR(80) NOT NULL,
  `icone` VARCHAR(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `annonces`;
CREATE TABLE `annonces` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `titre` VARCHAR(200) NOT NULL,
  `description` TEXT NOT NULL,
  `prix` DECIMAL(12,0) NOT NULL DEFAULT 0,
  `categorie_id` INT NOT NULL,
  `auteur_id` INT NOT NULL,
  `localisation` VARCHAR(100) NOT NULL,
  `date_creation` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `statut` ENUM('en_attente','active','rejetee','vendue') NOT NULL DEFAULT 'en_attente',
  CONSTRAINT `fk_annonce_categorie` FOREIGN KEY (`categorie_id`) REFERENCES `categories`(`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_annonce_auteur`    FOREIGN KEY (`auteur_id`)   REFERENCES `users`(`id`)      ON DELETE CASCADE,
  INDEX (`categorie_id`), INDEX (`auteur_id`), INDEX (`statut`), INDEX (`localisation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `annonce_photos`;
CREATE TABLE `annonce_photos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `annonce_id` INT NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  CONSTRAINT `fk_photo_annonce` FOREIGN KEY (`annonce_id`) REFERENCES `annonces`(`id`) ON DELETE CASCADE,
  INDEX (`annonce_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `expediteur_id` INT NOT NULL,
  `destinataire_id` INT NOT NULL,
  `annonce_id` INT NOT NULL,
  `contenu` TEXT NOT NULL,
  `date_envoi` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lu` TINYINT(1) NOT NULL DEFAULT 0,
  CONSTRAINT `fk_msg_exp`  FOREIGN KEY (`expediteur_id`)   REFERENCES `users`(`id`)    ON DELETE CASCADE,
  CONSTRAINT `fk_msg_dest` FOREIGN KEY (`destinataire_id`) REFERENCES `users`(`id`)    ON DELETE CASCADE,
  CONSTRAINT `fk_msg_ann`  FOREIGN KEY (`annonce_id`)      REFERENCES `annonces`(`id`) ON DELETE CASCADE,
  INDEX (`expediteur_id`), INDEX (`destinataire_id`), INDEX (`annonce_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `favoris`;
CREATE TABLE `favoris` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `utilisateur_id` INT NOT NULL,
  `annonce_id` INT NOT NULL,
  `date_ajout` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uniq_user_annonce` (`utilisateur_id`, `annonce_id`),
  CONSTRAINT `fk_fav_user`    FOREIGN KEY (`utilisateur_id`) REFERENCES `users`(`id`)    ON DELETE CASCADE,
  CONSTRAINT `fk_fav_annonce` FOREIGN KEY (`annonce_id`)     REFERENCES `annonces`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO `categories` (`nom`, `icone`) VALUES
('Vehicules','car'),('Immobilier','home'),('Electronique','phone'),
('Mode','shirt'),('Maison & Jardin','sofa'),('Emploi','briefcase'),
('Services','tools'),('Loisirs','bike'),('Animaux','paw'),('Autres','box');

-- admin123 / user123 / user123
INSERT INTO `users` (`nom`, `email`, `password`, `telephone`, `role`) VALUES
('Admin Gabon',  'admin@gabon-annonces.ga', '$2y$10$G7jbmTlm8BdwXgl7kJGSt.KXldxRRd49vg23mGeKYmV56rAMcOYui', '+241 06 00 00 00', 'moderateur'),
('Jean Mboumba', 'jean@example.com',        '$2y$10$PsBllYs9LRFVu53N7T5sM.k3mmx1CTprh6c3n/EQPRPkuOayxMV9e', '+241 07 11 22 33', 'annonceur'),
('Marie Ondo',   'marie@example.com',       '$2y$10$PsBllYs9LRFVu53N7T5sM.k3mmx1CTprh6c3n/EQPRPkuOayxMV9e', '+241 06 44 55 66', 'acheteur');

INSERT INTO `annonces` (`titre`, `description`, `prix`, `categorie_id`, `auteur_id`, `localisation`, `statut`) VALUES
('Toyota RAV4 2018 en tres bon etat', 'Vehicule entretenu, climatisation, 4x4, 85 000 km. Visible a Libreville.', 12500000, 1, 2, 'Estuaire (Libreville)', 'active'),
('Appartement F3 a louer a Akanda',   'Appartement neuf, 2 chambres, salon, cuisine equipee, parking securise.', 350000, 2, 2, 'Estuaire (Libreville)', 'active'),
('iPhone 13 Pro 256 Go',              'Comme neuf, chargeur et coque inclus. Garantie 3 mois.', 480000, 3, 2, 'Ogooue-Maritime (Port-Gentil)', 'active'),
('Canape 3 places en cuir',           'Tres bon etat, couleur marron. A retirer sur place.', 175000, 5, 2, 'Estuaire (Libreville)', 'active'),
('Velo VTT adulte',                   'VTT 21 vitesses, peu utilise. Ideal balades Libreville.', 95000, 8, 2, 'Estuaire (Libreville)', 'en_attente');


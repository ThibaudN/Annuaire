CREATE TABLE `Prefix_AntiRobots` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `Question` varchar(250) NOT NULL,
  `Reponse` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `Prefix_Categories` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `Titre` varchar(250) NOT NULL,
  `TitreUrl` varchar(250) NOT NULL,
  `DescriptionH2` varchar(250) NOT NULL,
  `Description` text NOT NULL,
  `SitesH2` varchar(250) NOT NULL,
  `Sites` mediumint(8) UNSIGNED NOT NULL,
  `ParPage` smallint(4) UNSIGNED NOT NULL,
  `MetaTitle` varchar(250) NOT NULL,
  `MetaDescription` varchar(250) NOT NULL,
  `Online` tinyint(1) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `Prefix_Config` (
  `id` tinyint(1) UNSIGNED NOT NULL,
  `NomSite` varchar(250) NOT NULL,
  `TagLine` varchar(250) NOT NULL,
  `MailDestinataire` varchar(250) NOT NULL,
  `MailLorsPaiement` tinyint(1) UNSIGNED NOT NULL,
  `HomeMetaTitle` varchar(250) NOT NULL,
  `HomeMetaDescription` varchar(250) NOT NULL,
  `HomeDescriptionH2` varchar(250) NOT NULL,
  `HomeDescription` text NOT NULL,
  `HomeSitesH2` varchar(250) NOT NULL,
  `HomeSitesAffiches` smallint(5) UNSIGNED NOT NULL,
  `SidebarCategorieH2` varchar(250) NOT NULL,
  `SidebarSitesH2` varchar(250) NOT NULL,
  `SidebarNavigationH2` varchar(250) NOT NULL,
  `GoogleAnalytics` varchar(250) NOT NULL,
  `Regles` text NOT NULL,
  `CaracteresMinDescription1` mediumint(8) UNSIGNED NOT NULL,
  `CaracteresMaxDescription1` mediumint(8) UNSIGNED NOT NULL,
  `CaracteresMinDescription2` mediumint(8) UNSIGNED NOT NULL,
  `CaracteresMaxDescription2` mediumint(8) UNSIGNED NOT NULL,
  `PaypalPrix` varchar(9) NOT NULL,
  `PaypalMonnaie` varchar(5) NOT NULL,
  `PaypalMail` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `Prefix_Fiches` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `idCategorie` smallint(5) UNSIGNED NOT NULL,
  `Url` varchar(250) NOT NULL,
  `Titre` varchar(250) NOT NULL,
  `TitreUrl` varchar(250) NOT NULL,
  `Image` varchar(250) NOT NULL,
  `Description1` text NOT NULL,
  `Description2` text NOT NULL,
  `Mail` varchar(250) NOT NULL,
  `MetaTitle` varchar(250) NOT NULL,
  `MetaDescription` varchar(250) NOT NULL,
  `Date` datetime NOT NULL,
  `DateMail` datetime NOT NULL,
  `DatePaiement` datetime NOT NULL,
  `DateValidation` datetime NOT NULL,
  `Ip` varchar(250) NOT NULL,
  `KeyGen` varchar(32) NOT NULL,
  `KeyGenMail` varchar(5) NOT NULL,
  `RefusRaison` tinyint(1) UNSIGNED NOT NULL,
  `Etat` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `Prefix_Paypal` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `idFiche` mediumint(8) UNSIGNED NOT NULL,
  `idTransaction` varchar(250) NOT NULL,
  `Data` text NOT NULL,
  `Date` datetime NOT NULL,
  `Etat` tinyint(1) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `Prefix_Sidebar` (
  `id` smallint(8) UNSIGNED NOT NULL,
  `Lien` varchar(250) NOT NULL,
  `Titre` varchar(250) NOT NULL,
  `Unfollow` tinyint(1) UNSIGNED NOT NULL,
  `Externe` tinyint(1) UNSIGNED NOT NULL,
  `Ordre` tinyint(1) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE `Prefix_AntiRobots` ADD PRIMARY KEY (`id`);
ALTER TABLE `Prefix_Categories` ADD PRIMARY KEY (`id`);
ALTER TABLE `Prefix_Config` ADD PRIMARY KEY (`id`);
ALTER TABLE `Prefix_Fiches` ADD PRIMARY KEY (`id`);
ALTER TABLE `Prefix_Paypal` ADD PRIMARY KEY (`id`);
ALTER TABLE `Prefix_Sidebar` ADD PRIMARY KEY (`id`);
ALTER TABLE `Prefix_AntiRobots` MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
ALTER TABLE `Prefix_Categories` MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
ALTER TABLE `Prefix_Config` MODIFY `id` tinyint(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `Prefix_Fiches` MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `Prefix_Paypal` MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `Prefix_Sidebar` MODIFY `id` smallint(8) UNSIGNED NOT NULL AUTO_INCREMENT;
INSERT INTO `Prefix_Config` (`id`, `NomSite`, `TagLine`, `MailDestinataire`, `MailLorsPaiement`, `HomeMetaTitle`, `HomeMetaDescription`, `HomeDescriptionH2`, `HomeDescription`, `HomeSitesH2`, `HomeSitesAffiches`, `SidebarCategorieH2`, `SidebarSitesH2`, `SidebarNavigationH2`, `GoogleAnalytics`, `Regles`, `CaracteresMinDescription1`, `CaracteresMaxDescription1`, `CaracteresMinDescription2`, `CaracteresMaxDescription2`, `PaypalPrix`, `PaypalMonnaie`, `PaypalMail`) VALUES (1, 'Annuaire', 'Un annuaire de référence pour un réferencement de référence', '', 1, 'Annuaire', 'Un annuaire qui te fera naviguer sur inter', 'H2 Description Home', 'Description sur la home', 'Les derniers sites référencés', 20, 'L\'annuaire', 'A voir', 'Navigation', '', '<ol>\r\n<li>Le titre et la description doivent être écrit en français</li>\r\n<li>Son contenu doit être de qualité.</li>\r\n<li>Pas plus d\'une pop-up en page d\'accueil</li>\r\n<li>Pas de blogs miroirs (un même contenu pour plusieurs urls)</li>\r\n<li>Pas de sites internet en construction</li>\r\n<li>Le site web doit avoir son propre nom de domaine</li>\r\n<li>Pas de titre ni de description en majuscules</li>\r\n<li>Pas de site au contenu illicite et/ou interdit.</li>\r\n<li>Pas de description et de titre au style commercial ou une suite de mots clefs</li>\r\n</ol>', 300, 350, 1500, 3000, '5.00', 'EUR', '');
INSERT INTO `Prefix_AntiRobots` (`id`, `Question`, `Reponse`) VALUES (1, 'La capitale de la France', 'Paris'),(2, 'La capitale de l\'Allemagne', 'Berlin'),(3, 'la capitale des USA', 'Washington'),(4, 'La couleur de l\'herbe', 'Verte'),(5, 'La couleur du soleil', 'Jaune'),(6, 'La couleur des pompiers', 'Rouge'),(7, 'La couleur du ciel', 'Bleu'),(8, 'La capitale de l\'Angleterre', 'Londres'),(9, 'La capitale de la Belgique', 'Bruxelles'),(10, 'Louis XIV était le roi ?', 'Soleil'),(11, 'Mistral Gagnant est une chanson de ?', 'Renaud'),(12, 'La 205 est une voiture de marque ?', 'Peugeot'),(13, 'La clio est une voiture de marque ?', 'Renault');
INSERT INTO `Prefix_Categories` (`id`, `Titre`, `TitreUrl`, `DescriptionH2`, `Description`,`SitesH2`,  `Sites`, `ParPage`, `MetaTitle`, `MetaDescription`, `Online`) VALUES (1, 'Annuaire', 'annuaire', 'La catégorie Annuaire', 'Description de la catégorie Annuaire', 'Les sites de la catégorie Annuaire', 0, 20, '', '', 1),(2, 'Boutiques en ligne', 'boutique-en-ligne', 'La catégorie Boutiques en ligne', 'Description de la catégorie Boutiques en ligne', 'Les sites de la catégorie Boutiques en ligne', 0, 20, '', '', 1),(3, 'Actualités', 'actualites', 'La catégorie Actualités', 'Description de la catégorie Actualités', 'Les sites de la catégorie Actualités', 0, 20, '', '', 1),(4, 'Blogs', 'blogs', 'La catégorie Blogs', 'Description de la catégorie Blogs', 'Les sites de la catégorie Blogs', 0, 20, '', '', 1),(5, 'Jeux & Loisirs', 'jeux-loisirs', 'La catégorie Jeux & Loisirs', 'Description de la catégorie Jeux & Loisirs', 'Les sites de la catégorie Jeux & Loisirs', 0, 20, '', '', 1),(6, 'Alimentation', 'alimentation', 'La catégorie Alimentation', 'Description de la catégorie Alimentation', 'Les sites de la catégorie Alimentation', 0, 20, '', '', 1),(7, 'Mode', 'mode', 'La catégorie Mode', 'Description de la catégorie Mode', 'Les sites de la catégorie Mode', 0, 20, '', '', 1),(8, 'Immobilier', 'immobilier', 'La catégorie Immobilier', 'Description de la catégorie Immobilier', 'Les sites de la catégorie Immobilier', 0, 20, '', '', 1),(9, 'Finance', 'finance', 'La catégorie Finance', 'Description de la catégorie Finance', 'Les sites de la catégorie Finance', 0, 20, '', '', 1),(10, 'Santé & Bien-être', 'sante-bien-etre', 'La catégorie Santé & Bien-être', 'Description de la catégorie Santé & Bien-être', 'Les sites de la catégorie Santé & Bien-être', 0, 20, '', '', 1),(11, 'Automobile', 'automobile', 'La catégorie Automobile', 'Description de la catégorie Automobile', 'Les sites de la catégorie Automobile', 0, 20, '', '', 1),(12, 'Communication', 'communication', 'La catégorie Communication', 'Description de la catégorie Communication', 'Les sites de la catégorie Communication', 0, 20, '', '', 1),(13, 'High-Tech', 'high-tech', 'La catégorie High-Tech', 'Description de la catégorie High-Tech', 'Les sites de la catégorie High-Tech', 0, 20, '', '', 1),(14, 'Sciences', 'sciences', 'La catégorie Sciences', 'Description de la catégorie Sciences', 'Les sites de la catégorie Sciences', 0, 20, '', '', 1),(15, 'Sports', 'sports', 'La catégorie Sports', 'Description de la catégorie Sports', 'Les sites de la catégorie Sports', 0, 20, '', '', 1),(16, 'Toursime', 'toursime', 'La catégorie Toursime', 'Description de la catégorie Toursime', 'Les sites de la catégorie Toursime', 0, 20, '', '', 1);
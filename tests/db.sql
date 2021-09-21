DROP TABLE IF EXISTS `test_repository`;
CREATE TABLE `test_repository` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sample` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `test_repository` (`id`, `sample`) VALUES (1, 'Hello');

DROP TABLE IF EXISTS `test_entity`;
CREATE TABLE `test_entity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sample` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `test_entity` (`id`, `sample`) VALUES (1, 'Hello');


CREATE TABLE IF NOT EXISTS `#__custom_relatedproducts` (
  `linked_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `diff_description` text NOT NULL,
  UNIQUE KEY `linked_id` (`linked_id`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

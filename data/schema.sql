CREATE TABLE `subscription` (
     `id` int(11) NOT NULL AUTO_INCREMENT,
     `email` varchar(255) NOT NULL,
     `type_enum` int(11) NOT NULL DEFAULT '0',
     `type_id` int(11) DEFAULT '0',
     `subscribe_date` int(11) DEFAULT '0',
     `unsubscribe_date` int(11) NOT NULL DEFAULT '0',
     PRIMARY KEY (`id`)
) ENGINE=InnoDB

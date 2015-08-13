DROP TABLE `actor`, `category`, `customers`, `employees`, `film`, `film_actor`, `film_category`, `offices`, `orderdetails`, `orders`, `payments`, `productlines`, `products`;

-- ----------------------------
--  Table structure for `t_image`
-- ----------------------------
DROP TABLE IF EXISTS `t_image`;
CREATE TABLE `t_image` (
  `imageid` int(11) NOT NULL AUTO_INCREMENT,
  `image_url` varchar(500) NOT NULL,
  `description` text NOT NULL,
  `priority` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`imageid`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
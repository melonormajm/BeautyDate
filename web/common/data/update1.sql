

CREATE TABLE IF NOT EXISTS `imagenes` (
`id`  int(20) NOT NULL AUTO_INCREMENT ,
`nombre`  varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`descripcion`  varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
`url`  varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`principal`  tinyint(1) NULL DEFAULT NULL ,
`salonid`  int(10) NOT NULL ,
PRIMARY KEY (`id`),
FOREIGN KEY (`salonid`) REFERENCES `salon` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
INDEX `salonid` (`salonid`) USING BTREE
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=3
ROW_FORMAT=COMPACT
;
CREATE DATABASE  IF NOT EXISTS `dbproject` ;
USE `dbproject`;

-- Table structure for table `user`;

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `e_mail` varchar(255) DEFAULT NULL,
  `num_of_playlists` int(11) DEFAULT '0',
  PRIMARY KEY (`userID`),
  UNIQUE KEY `user_name_UNIQUE` (`user_name`)
) AUTO_INCREMENT=1000;

INSERT INTO `user` VALUES(1, 'admin1234', 'mdfc', 'admin', 'admin', 'admin1234@hertz.com', 0);

-- Table structure for table `song`;

DROP TABLE IF EXISTS `song`;

CREATE TABLE `song` (
  `SongID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(255) DEFAULT NULL,
  `Album` varchar(255) DEFAULT NULL,
  `Artist` varchar(255) DEFAULT NULL,
  `Composer` varchar(255) DEFAULT NULL,
  `Genre` varchar(255) DEFAULT NULL,
  `Filepath` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`SongID`)
) AUTO_INCREMENT = 1;

INSERT INTO `song` VALUES(1, 'Happy', 'Girl', 'Pharrell Williams', 'Pharrell Williams', 'Pop', 'Music/Happy.mp3');
INSERT INTO `song` VALUES(2, 'Radioactive', 'Night Visions', 'Imagine Dragons', 'Imagine Dragons', 'Rock', 'Music/Radioactive.mp3');
INSERT INTO `song` VALUES(3, 'Blinding Lights', 'After Hours', 'Weekend', 'Weekend', 'Pop', 'Music/Blinding_Lights.mp3');
INSERT INTO `song` VALUES(4, 'Despacito', 'Despacito', 'Luis Fonsi', 'Luis Fonsi', 'Pop', 'Music/Despacito.mp3');
INSERT INTO `song` VALUES(5, 'Industry Baby', 'Montero', 'Lil Nas X', 'Lil Nas X', 'Pop', 'Music/Industry_Baby.mp3');

-- Table structure for table `history`;

DROP TABLE IF EXISTS `history`;

CREATE TABLE `history` (
  `userID` int(11) DEFAULT NULL,
  `SongID` int(11) DEFAULT NULL,
  `played_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `userID_history_idx` (`userID`),
  KEY `SongID_history_idx` (`SongID`),
  CONSTRAINT `fk_SongID_history` FOREIGN KEY (`SongID`) REFERENCES `song` (`SongID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_userID_history` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE
) ;


-- Table structure for table `likes`;

DROP TABLE IF EXISTS `likes`;

CREATE TABLE `likes` (
  `userID` int(11) DEFAULT NULL,
  `SongID` int(11) DEFAULT NULL,
  UNIQUE KEY `songID_UserId_idx` (`userID`,`SongID`),
  KEY `SongID_idx` (`SongID`),
  KEY `useid_idx` (`userID`),
  CONSTRAINT `fk_SongID_like` FOREIGN KEY (`SongID`) REFERENCES `song` (`SongID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_userID_like` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE
) ;


-- Table structure for table `playlist_info`;

DROP TABLE IF EXISTS `playlist_info`;

CREATE TABLE `playlist_info` (
  `playlist_ID` int(11) NOT NULL AUTO_INCREMENT,
  `playlist_name` varchar(255) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  PRIMARY KEY (`playlist_ID`),
  KEY `user_id_idx` (`userID`),
  CONSTRAINT `fk_userID_playlist_info` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE
) AUTO_INCREMENT = 1;


-- Table structure for table `playlist`;

DROP TABLE IF EXISTS `playlist`;

CREATE TABLE `playlist` (
  `playlist_ID` int(11) DEFAULT NULL,
  `SongID` int(11) DEFAULT NULL,
  UNIQUE KEY `pl_id_user_id_indx` (`playlist_ID`,`SongID`),
  KEY `playlistID_playlist_idx` (`playlist_ID`),
  KEY `songID_playlist_idx` (`SongID`),
  CONSTRAINT `fk_playlistID_playlist` FOREIGN KEY (`playlist_ID`) REFERENCES `playlist_info` (`playlist_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_songID_playlist` FOREIGN KEY (`SongID`) REFERENCES `song` (`SongID`) ON DELETE CASCADE ON UPDATE CASCADE
) ;
USE OperationDoge;

DROP TABLE IF EXISTS `Admin`;
DROP TABLE IF EXISTS `News`;
DROP TABLE IF EXISTS `ImageGallery`;
DROP TABLE IF EXISTS `Post`;
DROP TABLE IF EXISTS `GuestbookThread`;
DROP TABLE IF EXISTS `Participant`;
DROP TABLE IF EXISTS `DogCourse`;
DROP TABLE IF EXISTS `Puppy`;
DROP TABLE IF EXISTS `PuppyLitter`;
DROP TABLE IF EXISTS `NewsFeed`;
DROP VIEW IF EXISTS `ParticipantsPerCourse`;
DROP VIEW IF EXISTS `ComingCourses`;

CREATE TABLE Admin (
    `Username` varchar(255) NOT NULL,
    `Password` varchar(255) NOT NULL,
    PRIMARY KEY (`Username`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE News (
    `Title` varchar(255) NOT NULL,
    `DateTime` DATETIME NOT NULL,
    `NewsText` varchar(255),
    `NewsImagePath` varchar(255),
    PRIMARY KEY (`Title`,`DateTime`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE ImageGallery (
    `ImagePath` varchar(255) NOT NULL,
    `ImageTitle` varchar(255) NOT NULL,
    PRIMARY KEY (`ImagePath`, `ImageTitle`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE GuestbookThread (
    `Title` varchar(255) NOT NULL,
    `DateTime` DATETIME NOT NULL,
    `Username` varchar(255),
    `Description` varchar(255),
    PRIMARY KEY (`Title`, `DateTime`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE Post (
    `Username` varchar(255) NOT NULL,
    `DateTime` DATETIME NOT NULL,
    `PostText` varchar(255),
    `PostImagePath` varchar(255),
    `Thread_Title` varchar(255) NOT NULL,
    `Thread_DateTime` DATETIME,
    PRIMARY KEY (`Username`,`DateTime`),
    FOREIGN KEY  (`Thread_Title`, `Thread_DateTime`) REFERENCES GuestbookThread (`Title`, `DateTime`) ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE DogCourse (
    `CourseName` varchar(255) NOT NULL,
    `CourseTeacher` varchar(255) NOT NULL,
    `CourseDate` date NOT NULL,
    `AgeOfDog` int,
    `Gender` varchar(255),
    `PriorKnowledge` varchar(255),
    `CourseText` varchar(255),
    PRIMARY KEY (`CourseName`, `CourseTeacher`, `CourseDate`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE Participant (
    `DogName` varchar(255) NOT NULL,
    `OwnerName` varchar(255) NOT NULL,
    `Email` varchar(255) NOT NULL,
    `AgeOfDog` int NOT NULL,
    `Gender` varchar(255) NOT NULL,
    `ExtraInfo` varchar(255),
    `DogCourse_CourseName` varchar(255) NOT NULL,
    `DogCourse_CourseTeacher` varchar(255) NOT NULL,
    `DogCourse_CourseDate` DATE NOT NULL,
    `RegisterDate` datetime NOT NULL,
    PRIMARY KEY (`DogName`, `Email`, `DogCourse_CourseName`, `DogCourse_CourseTeacher`, `DogCourse_CourseDate`),
    FOREIGN KEY (`DogCourse_CourseName`,`DogCourse_CourseTeacher`,`DogCourse_CourseDate`) REFERENCES DogCourse (`CourseName`, `CourseTeacher`, `CourseDate`) ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE PuppyLitter (
    `LitterTitle` varchar(255) NOT NULL,
    `Upcomming` BOOLEAN NOT NULL DEFAULT 1,
    `LiterInfo` varchar(255),
    PRIMARY KEY (`LitterTitle`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE Puppy (
    `DogName` varchar(255) NOT NULL,
    `Gender` varchar(255) NOT NULL,
    `Price` varchar(255) NOT NULL,
    `Available` BOOLEAN,
    `BirthDate` VARCHAR(45),
    `PuppyLitter_LitterTitle` varchar(255) NOT NULL,
    PRIMARY KEY (`DogName`),
    FOREIGN KEY (`PuppyLitter_LitterTitle`) REFERENCES PuppyLitter (`LitterTitle`) ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE NewsFeed (
    `NewsTitle` varchar(255) NOT NULL,
    `DateTime` DATETIME NOT NULL,
    `Description` varchar(255),
    `NewsImagePath` varchar(255),
    `NewsLink` varchar(255),
    PRIMARY KEY (`NewsTitle`,`DateTime`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE VIEW `ParticipantsPerCourse`
AS SELECT
   `P`.`DogCourse_CourseName` AS `DogCourse_CourseName`,
   `P`.`DogCourse_CourseTeacher` AS `DogCourse_CourseTeacher`,
   `P`.`DogCourse_CourseDate` AS `DogCourse_CourseDate`,count(0) AS `NumOfParticipants`
FROM `participant` `P` group by `P`.`DogCourse_CourseName`,`P`.`DogCourse_CourseTeacher`,`P`.`DogCourse_CourseDate`;

CREATE VIEW `ComingCourses`
AS SELECT
   `DC`.`CourseName` AS `CourseName`,
   `DC`.`CourseTeacher` AS `CourseTeacher`,
   `DC`.`CourseDate` AS `CourseDate`,
   `DC`.`AgeOfDog` AS `AgeOfDog`,
   `DC`.`Gender` AS `Gender`,
   `DC`.`PriorKnowledge` AS `PriorKnowledge`,
   `DC`.`CourseText` AS `CourseText`,IF((`p`.`NumOfParticipants` > 0),`p`.`NumOfParticipants`,0) AS `Participants`
FROM (`dogcourse` `DC` left join `participantspercourse` `P` on(((`DC`.`CourseName` = `p`.`DogCourse_CourseName`) and (`DC`.`CourseTeacher` = `p`.`DogCourse_CourseTeacher`) and (`DC`.`CourseDate` = `p`.`DogCourse_CourseDate`)))) where (`DC`.`CourseDate` > now()) order by `DC`.`CourseDate`;
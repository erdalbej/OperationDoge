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

CREATE TABLE Admin (
    `Username` varchar(255) CHARACTER SET latin1 NOT NULL,
    `Password` varchar(255) CHARACTER SET latin1 NOT NULL,
    PRIMARY KEY (`Username`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE News (
    `Title` varchar(255) CHARACTER SET latin1 NOT NULL,
    `DateTime` DATETIME NOT NULL,
    `NewsText` varchar(255) CHARACTER SET latin1,
    `NewsImagePath` varchar(255) CHARACTER SET latin1,
    PRIMARY KEY (`Title`,`DateTime`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE ImageGallery (
    `ImagePath` varchar(255) CHARACTER SET latin1 NOT NULL,
    `ImageTitle` varchar(255) CHARACTER SET latin1 NOT NULL,
    PRIMARY KEY (`ImagePath`, `ImageTitle`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE GuestbookThread (
    `Title` varchar(255) CHARACTER SET latin1 NOT NULL,
    `DateTime` DATETIME NOT NULL,
    `Username` varchar(255) CHARACTER SET latin1,
    `Description` varchar(255) CHARACTER SET latin1,
    PRIMARY KEY (`Title`, `DateTime`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE Post (
    `Username` varchar(255) CHARACTER SET latin1 NOT NULL,
    `DateTime` DATETIME NOT NULL,
    `PostText` varchar(255) CHARACTER SET latin1,
    `PostImagePath` varchar(255) CHARACTER SET latin1,
    `Thread_Title` varchar(255) CHARACTER SET latin1 NOT NULL,
    `Thread_DateTime` DATETIME,
    PRIMARY KEY (`Username`,`DateTime`),
    FOREIGN KEY  (`Thread_Title`, `Thread_DateTime`) REFERENCES GuestbookThread (`Title`, `DateTime`) ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE DogCourse (
    `CourseName` varchar(255) CHARACTER SET latin1 NOT NULL,
    `CourseTeacher` varchar(255) CHARACTER SET latin1 NOT NULL,
    `DateTime` DATETIME NOT NULL,
    `AgeOfDog` int,
    `Gender` varchar(255) CHARACTER SET latin1,
    `PriorKnowledge` varchar(255) CHARACTER SET latin1,
    `CourseText` varchar(255) CHARACTER SET latin1,
    PRIMARY KEY (`CourseName`, `CourseTeacher`, `DateTime`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE Participant (
    `DogName` varchar(255) CHARACTER SET latin1 NOT NULL,
    `OwnerName` varchar(255) CHARACTER SET latin1 NOT NULL,
    `AgeOfDog` int NOT NULL,
    `Gender` varchar(255) CHARACTER SET latin1 NOT NULL,
    `ExtraInfo` varchar(255) CHARACTER SET latin1,
    `DogCourse_CourseName` varchar(255) CHARACTER SET latin1 NOT NULL,
    `DogCourse_CourseTeacher` varchar(255) CHARACTER SET latin1 NOT NULL,
    `DogCourse_DateTime` DATETIME NOT NULL,
    PRIMARY KEY (`DogName`, `OwnerName`),
    FOREIGN KEY (`DogCourse_CourseName`,`DogCourse_CourseTeacher`,`DogCourse_DateTime`) REFERENCES DogCourse (`CourseName`, `CourseTeacher`, `DateTime`) ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE PuppyLitter (
    `LitterTitle` varchar(255) CHARACTER SET latin1 NOT NULL,
    `Upcomming` BOOLEAN NOT NULL DEFAULT 1,
    `LiterInfo` varchar(255) CHARACTER SET latin1,
    PRIMARY KEY (`LitterTitle`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE Puppy (
    `DogName` varchar(255) CHARACTER SET latin1 NOT NULL,
    `Gender` varchar(255) CHARACTER SET latin1 NOT NULL,
    `Price` varchar(255) CHARACTER SET latin1 NOT NULL,
    `Available` BOOLEAN,
    `PuppyLitter_LitterTitle` varchar(255) CHARACTER SET latin1 NOT NULL,
    PRIMARY KEY (`DogName`),
    FOREIGN KEY (`PuppyLitter_LitterTitle`) REFERENCES PuppyLitter (`LitterTitle`) ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

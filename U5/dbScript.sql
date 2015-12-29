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
DROP TABLE IF EXISTS `MyDog`;
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
    `CreatedAt` DATETIME NOT NULL,
    `Username` varchar(255),
    `Description` varchar(255),
    PRIMARY KEY (`Title`, `CreatedAt`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE Post (
    `Username` varchar(255) NOT NULL,
    `CreatedAt` DATETIME NOT NULL,
    `PostText` varchar(255),
    `PostImagePath` varchar(255),
    `Thread_Title` varchar(255) NOT NULL,
    `Thread_CreatedAt` DATETIME,
    PRIMARY KEY (`Username`,`CreatedAt`),
    FOREIGN KEY  (`Thread_Title`, `Thread_CreatedAt`) REFERENCES GuestbookThread (`Title`, `CreatedAt`) ON DELETE CASCADE
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
    `PuppyImagePath` varchar(255),
    `Available` BOOLEAN NOT NULL DEFAULT 1,
    `BirthDate` VARCHAR(45),
    `PuppyLitter_LitterTitle` varchar(255) NOT NULL,
    PRIMARY KEY (`DogName`, `PuppyLitter_LitterTitle`),
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

CREATE TABLE MyDog (
    `OfficialName` varchar(255) NOT NULL,
    `Name` varchar(255) NOT NULL,
    `Birthdate` DATE,
    `Desciption` varchar(500),
    `Color` varchar(255),
    `Height` decimal,
    `Weight` decimal,
    `Teeth` varchar(255),
    `MentalStatus` varchar(255),
    `Breader` varchar(255),
    `GenImagePath`varchar(255),
    `DogImagePath`varchar(255),
     constraint PK_MyDog PRIMARY KEY (`OfficialName`, `Name`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


/* DATA */
INSERT INTO `OperationDoge`.`PuppyLitter` (`LitterTitle`, `LiterInfo`) VALUES ('A-Kull', 'None');
INSERT INTO `OperationDoge`.`PuppyLitter` (`LitterTitle`, `LiterInfo`) VALUES ('B-Kull', 'None');
INSERT INTO `OperationDoge`.`DogCourse` (`CourseName`, `CourseTeacher`, `CourseDate`, `AgeOfDog`, `Gender`, `PriorKnowledge`, `CourseText`) VALUES ('Kommunikation med hunden', 'Sven-Göran', NOW() + INTERVAL 1 DAY, '2', 'B', 'Inga', 'Det är nödvändigt att du kan kommunicera med din hund, så att ni förstår varandra. Det är du som är den begåvade i detta, även om hunden inte ska underskattas. Det är du som valt hunden - hunden har inte valt dig.');
INSERT INTO `OperationDoge`.`DogCourse` (`CourseName`, `CourseTeacher`, `CourseDate`, `AgeOfDog`, `Gender`, `PriorKnowledge`, `CourseText`) VALUES ('God relation med din hund', 'Magnus', NOW() + INTERVAL 2 DAY, '3', 'B', 'Inga', 'Om du och din hund tycker om varandra får ni en god relation. Och relationen är grunden för kommunikation. Relationen bygger i hundvärlden på samma premisser som i människovärlden.');
INSERT INTO `OperationDoge`.`DogCourse` (`CourseName`, `CourseTeacher`, `CourseDate`, `AgeOfDog`, `Gender`, `PriorKnowledge`, `CourseText`) VALUES ('Lydnad för kontroll på hunden', 'Zlatan', NOW() + INTERVAL 3 DAY, '0', 'B', 'Inga', 'När din hund lärt sig ordet och innebörden av "sitt", att den sätter sig omedelbart och sitter kvar tills du säger att den får resa sig, då har du kontroll på din hund i en rad olika situationer.');
INSERT INTO `OperationDoge`.`DogCourse` (`CourseName`, `CourseTeacher`, `CourseDate`, `AgeOfDog`, `Gender`, `PriorKnowledge`, `CourseText`) VALUES ('Agility', 'Zlatan', NOW() + INTERVAL 4 DAY, '4', 'B', 'Lydnad för kontroll på hunden', 'Agility handlar om att på kortast möjliga tid och utan fel eller vägringar ta sig igenom en hinderbana. Agility är roligt för alla, och när det är som bäst är det också en uppvisning i avancerat samarbete.');
INSERT INTO `OperationDoge`.`DogCourse` (`CourseName`, `CourseTeacher`, `CourseDate`, `AgeOfDog`, `Gender`, `PriorKnowledge`, `CourseText`) VALUES ('Bruksprov', 'Stefan', NOW()  + INTERVAL 5 DAY, '7', 'B', 'Kommunikation med hunden, god relation med din hund', 'Bruksprov innehåller klassiska arbetsuppgifter för hundar och förare. Du kan träna och tävla i både lydnad och specialgrenarna: spår, sök, rapport, patrull, skydd och IPO.');
INSERT INTO `OperationDoge`.`DogCourse` (`CourseName`, `CourseTeacher`, `CourseDate`, `AgeOfDog`, `Gender`, `PriorKnowledge`, `CourseText`) VALUES ('Patrullhund', 'Batman', NOW() + INTERVAL 6 DAY, '6', 'B', 'Kommunikation med hunden', 'På uppdrag av Armén/Hemvärnet, Flygvapnet och Marinen kan ni utbilda er inom Brukshundklubben till patrullhund och förare. Du placeras i något av Försvarsmaktens olika förband.');

INSERT INTO `OperationDoge`.`Puppy` (`DogName`, `Gender`, `Price`,  `PuppyImagePath`, `BirthDate`, `PuppyLitter_LitterTitle`) VALUES ('Batuulis Aeter', 'Hane', '2000', 'noimage.jpg', '2006-05-14', 'A-Kull');
INSERT INTO `OperationDoge`.`Puppy` (`DogName`, `Gender`, `Price`, `PuppyImagePath`,`BirthDate`, `PuppyLitter_LitterTitle`) VALUES ('Batuulis Afrodite', 'Tik', '2500', 'noimage.jpg', '2006-05-14  ', 'A-Kull');
INSERT INTO `OperationDoge`.`Puppy` (`DogName`, `Gender`, `Price`, `PuppyImagePath`,`BirthDate`, `PuppyLitter_LitterTitle`) VALUES ('Batuulis Aiolos', 'Hane', '2650', 'noimage.jpg', '2006-05-14', 'A-Kull');
INSERT INTO `OperationDoge`.`Puppy` (`DogName`, `Gender`, `Price`, `PuppyImagePath`,`BirthDate`, `PuppyLitter_LitterTitle`) VALUES ('Batuulis Ajas', 'Hane', '2650', 'noimage.jpg', '2006-05-14', 'A-Kull');
INSERT INTO `OperationDoge`.`Puppy` (`DogName`, `Gender`, `Price`, `PuppyImagePath`,`BirthDate`, `PuppyLitter_LitterTitle`) VALUES ('Batuulis Alectrona', 'Tik', '3000', 'noimage.jpg', '2006-05-14', 'A-Kull');

INSERT INTO `OperationDoge`.`Puppy` (`DogName`, `Gender`, `Price`,  `PuppyImagePath`, `BirthDate`, `PuppyLitter_LitterTitle`) VALUES ('Batuulis Aeter', 'Hane', '2000', 'noimage.jpg', '2006-05-14', 'B-Kull');
INSERT INTO `OperationDoge`.`Puppy` (`DogName`, `Gender`, `Price`, `PuppyImagePath`,`BirthDate`, `PuppyLitter_LitterTitle`) VALUES ('Batuulis Afrodite', 'Tik', '2500', 'noimage.jpg', '2006-05-14  ', 'B-Kull');
INSERT INTO `OperationDoge`.`Puppy` (`DogName`, `Gender`, `Price`, `PuppyImagePath`,`BirthDate`, `PuppyLitter_LitterTitle`) VALUES ('Batuulis Aiolos', 'Hane', '2650', 'noimage.jpg', '2006-05-14', 'B-Kull');
INSERT INTO `OperationDoge`.`Puppy` (`DogName`, `Gender`, `Price`, `PuppyImagePath`,`BirthDate`, `PuppyLitter_LitterTitle`) VALUES ('Batuulis Ajas', 'Hane', '2650', 'noimage.jpg', '2006-05-14', 'B-Kull');
INSERT INTO `OperationDoge`.`Puppy` (`DogName`, `Gender`, `Price`, `PuppyImagePath`,`BirthDate`, `PuppyLitter_LitterTitle`) VALUES ('Batuulis Alectrona', 'Tik', '3000', 'noimage.jpg', '2006-05-14', 'B-Kull');

INSERT INTO `OperationDoge`.`MyDog` (`OfficialName`, `Name`, `Birthdate`, `Desciption`, `Color`, `Height`, `Weight`, `Teeth`, `MentalStatus`, `Breader`) VALUES (' RidgeBow´s Estelle of Canello\n\n RidgeBow´s Estelle of Canello', 'Thea', '2001-06-04', 'Thea är född som valp nr.2. \n Thea har 2 bröder och 1 systrar. Tillsammans är de RidgeBow´s E-kull. \n Thea´s far är S NUCh, LP1 Corleo´s Mosheshwe \"Canello\" och \n mor är INT NORDUCH Lövfällan`s Gucci', 'Vete', '65', '37', 'Fulltandad, korrekt saxbett', 'Känd mentalstatus', 'RidgeBow´s Kennel, Sven & Bitte Stjärnfeldt');
INSERT INTO `OperationDoge`.`MyDog` (`OfficialName`, `Name`, `Birthdate`, `Desciption`, `Color`, `Height`, `Weight`, `Teeth`, `MentalStatus`, `Breader`) VALUES ('INT&NORDUCH, S VCH, LP1, LP2 Stenänga Ghali Batuuli', 'Mira', '2002-09-15', 'Mira är född som valp nr.12. \n  Mira har 5 bröder och 7 systrar. Tillsammans är de Stenänga´s  \n  B-kull. Miras far är INTUCH, VDHCH Lionhunt Dayimane Umvuma och mor är Madahiro´s   \n  GoodEnough', 'Rödvete', '68', '41', 'Fulltandad', 'Känd mentalstatus, Skottsäker', 'Kennel Stenänga, Margaretha & Lars-Gunnar Lantz');
INSERT INTO `OperationDoge`.`MyDog` (`OfficialName`, `Name`, `Birthdate`, `Desciption`, `Color`, `Height`, `Weight`, `Teeth`) VALUES ('Batuulis Albion', 'Nino', '2006-05-14', 'Nino är född som valp nr.8. Nino har 9  bröder och 4  systrar. Tillsammans är de Batuulis A-kull. \nNino´s far är Malozi Charaza och  mor är Stenänga Ghali Batuuli.', 'Rödvete', '69', '43', 'Fulltandad');
INSERT INTO `OperationDoge`.`MyDog` (`OfficialName`, `Name`, `Birthdate`) VALUES (' Batuulis Peak Performance', 'Diva', '2007-07-27');

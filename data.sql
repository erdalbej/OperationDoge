INSERT INTO `OperationDoge`.`PuppyLitter` (`LitterTitle`, `LiterInfo`) VALUES ('A-Kull', 'None');
INSERT INTO `OperationDoge`.`PuppyLitter` (`LitterTitle`, `LiterInfo`) VALUES ('B-Kull', 'None');
INSERT INTO `OperationDoge`.`DogCourse` (`CourseName`, `CourseTeacher`, `CourseDate`, `AgeOfDog`, `Gender`, `PriorKnowledge`, `CourseText`) VALUES ('Kommunikation med hunden', 'Sven-Göran', NOW() + INTERVAL 1 DAY, '2', 'B', 'Inga', 'Det är nödvändigt att du kan kommunicera med din hund, så att ni förstår varandra. Det är du som är den begåvade i detta, även om hunden inte ska underskattas. Det är du som valt hunden - hunden har inte valt dig.');
INSERT INTO `OperationDoge`.`DogCourse` (`CourseName`, `CourseTeacher`, `CourseDate`, `AgeOfDog`, `Gender`, `PriorKnowledge`, `CourseText`) VALUES ('God relation med din hund', 'Magnus', NOW() + INTERVAL 2 DAY, '3', 'B', 'Inga', 'Om du och din hund tycker om varandra får ni en god relation. Och relationen är grunden för kommunikation. Relationen bygger i hundvärlden på samma premisser som i människovärlden.');
INSERT INTO `OperationDoge`.`DogCourse` (`CourseName`, `CourseTeacher`, `CourseDate`, `AgeOfDog`, `Gender`, `PriorKnowledge`, `CourseText`) VALUES ('Lydnad för kontroll på hunden', 'Zlatan', NOW() + INTERVAL 3 DAY, '0', 'B', 'Inga', 'När din hund lärt sig ordet och innebörden av "sitt", att den sätter sig omedelbart och sitter kvar tills du säger att den får resa sig, då har du kontroll på din hund i en rad olika situationer.');
INSERT INTO `OperationDoge`.`DogCourse` (`CourseName`, `CourseTeacher`, `CourseDate`, `AgeOfDog`, `Gender`, `PriorKnowledge`, `CourseText`) VALUES ('Agility', 'Zlatan', NOW() + INTERVAL 4 DAY, '4', 'B', 'Lydnad för kontroll på hunden', 'Agility handlar om att på kortast möjliga tid och utan fel eller vägringar ta sig igenom en hinderbana. Agility är roligt för alla, och när det är som bäst är det också en uppvisning i avancerat samarbete.');
INSERT INTO `OperationDoge`.`DogCourse` (`CourseName`, `CourseTeacher`, `CourseDate`, `AgeOfDog`, `Gender`, `PriorKnowledge`, `CourseText`) VALUES ('Bruksprov', 'Stefan', NOW()  + INTERVAL 5 DAY, '7', 'B', 'Kommunikation med hunden, god relation med din hund', 'Bruksprov innehåller klassiska arbetsuppgifter för hundar och förare. Du kan träna och tävla i både lydnad och specialgrenarna: spår, sök, rapport, patrull, skydd och IPO.');
INSERT INTO `OperationDoge`.`DogCourse` (`CourseName`, `CourseTeacher`, `CourseDate`, `AgeOfDog`, `Gender`, `PriorKnowledge`, `CourseText`) VALUES ('Patrullhund', 'Batman', NOW() + INTERVAL 6 DAY, '6', 'B', 'Kommunikation med hunden', 'På uppdrag av Armén/Hemvärnet, Flygvapnet och Marinen kan ni utbilda er inom Brukshundklubben till patrullhund och förare. Du placeras i något av Försvarsmaktens olika förband.');

INSERT INTO `OperationDoge`.`Puppy` (`DogName`, `Gender`, `Price`, `BirthDate`, `PuppyLitter_LitterTitle`) VALUES ('Batuulis Aeter', 'Hane', '2000', '2006-05-14', 'A-Kull');

drop table ADDEDGAME cascade constraints;
drop table ADMINS cascade constraints;
drop table DEVELOPER cascade constraints;
drop table GAME cascade constraints;
drop table GAMECOLLECTION cascade constraints;
drop table GAMESTUDIO cascade constraints;
drop table NEWSSTORY cascade constraints;
drop table REVIEW cascade constraints;
drop table SCORES cascade constraints;
drop table USERS cascade constraints;
drop table VERIFIED cascade constraints;
drop table WORKEDWITH cascade constraints;

-- Use these commands in their given order to drop all tables


create table users
    (userID INTEGER not null,
    username VARCHAR(100) not null,
    email VARCHAR(100) not null,
    password VARCHAR(100) not null,
    PRIMARY KEY (userID));

grant select on users to public;

create table verified
    (userID integer NOT NULL,
    PRIMARY KEY (userID),
    FOREIGN KEY (userID) REFERENCES users ON DELETE CASCADE);

grant select on verified to public;

create table admins
    (userID integer not null,
    primary key (userID),
    foreign key (userID) references users ON DELETE CASCADE);

grant select on admins to public;

create table gameStudio
    (studioName varchar(100) NOT NULL,
    image varchar(100),
    PRIMARY KEY (studioName));

grant select on gameStudio to public;

create table developer
    (devID int NOT NULL,
    devName varchar(100),
    bio varchar(1000),
    PRIMARY KEY (devID));

grant select on developer to public;

create table workedWith
    (studioName varchar(100) NOT NULL,
    devID int NOT NULL,
    PRIMARY KEY (studioName, devID),
    FOREIGN KEY (studioName) REFERENCES gameStudio ON DELETE CASCADE,
    FOREIGN KEY (devID) REFERENCES developer ON DELETE CASCADE);

grant select on workedWith to public;

create table game
    (gameID int NOT NULL,
    adminID int NOT NULL,
    studioName varchar(100) NOT NULL,
    gameName varchar(100) NOT NULL,
    genre varchar(100),
    avgScore float,
    PRIMARY KEY (gameID),
    FOREIGN KEY (adminID) REFERENCES admins ON DELETE CASCADE,
    FOREIGN KEY (studioName) REFERENCES gameStudio ON DELETE CASCADE,
    CONSTRAINT game_score CHECK (avgScore <= 5 AND avgScore >= 0));

grant select on game to public;

create table gameCollection
    (collectionID int NOT NULL,
    userID int NOT NULL,
    collectionName varchar(100),
    PRIMARY KEY (collectionID),
    FOREIGN KEY (userID) REFERENCES users ON DELETE CASCADE);

grant select on gameCollection to public;

create table review
    (reviewID int NOT NULL,
    gameID int NOT NULL,
    userID int NOT NULL,
    text varchar(1000),
    PRIMARY KEY (reviewID, gameID),
    FOREIGN KEY (gameID) REFERENCES game ON DELETE CASCADE,
    FOREIGN KEY (userID) REFERENCES users ON DELETE CASCADE);

grant select on review to public;

create table scores
    (gameID int NOT NULL,
    userID int NOT NULL,
    score float,
    isLiked char(1),
    PRIMARY KEY (gameID, userID),
    FOREIGN KEY (gameID) REFERENCES game ON DELETE CASCADE,
    FOREIGN KEY (userID) REFERENCES users ON DELETE CASCADE);

grant select on scores to public;

create table newsStory
    (newsID int NOT NULL,
    verifiedUserID int NOT NULL,
    adminUserID int NOT NULL,
    text varchar(1000),
    image varchar(100),
    newsDate date,
    PRIMARY KEY (newsID),
    FOREIGN KEY (verifiedUserID) REFERENCES verified ON DELETE CASCADE,
    FOREIGN KEY (adminUserID) REFERENCES admins ON DELETE CASCADE);

grant select on newsStory to public;

create table addedGame
    (collectionID int NOT NULL,
    gameID int NOT NULL,
    PRIMARY KEY (collectionID, gameID),
    FOREIGN KEY (collectionID) REFERENCES gameCollection ON DELETE CASCADE,
    FOREIGN KEY (gameID) REFERENCES game ON DELETE CASCADE);

grant select on addedGame to public;


insert into users values(1,'user1', 'user1@gmail.com', 'pwd1');
insert into users values(2,'user2', 'user2@gmail.com', 'pwd2');
insert into users values(3,'user3', 'user3@gmail.com', 'pwd3');

insert into admins values(1);
insert into admins values(2);
insert into admins values(3);
insert into admins values(4);
insert into admins values(5);

insert into verified values(2);
insert into verified values(3);

insert into gameStudio values('Ubisoft', 'ubi.jpg');
insert into gameStudio values('Relic Entertainment', 'relic.jpg');
insert into gameStudio values('Electronic Arts', 'ea.jpg');
insert into gameStudio values('id Software', 'id.jpg');
insert into gameStudio values('Activision Blizzard', 'actibliz.jpg');
insert into gameStudio values('Riot Games', 'riot.jpg');
insert into gameStudio values('Valve', 'valve.jpg');
insert into gameStudio values('Hasbro', 'hasbro.jpg');
insert into gameStudio values('Konami', 'konami.jpg');
insert into gameStudio values('Mojang', 'mojang.jpg');
insert into gameStudio values('343 Industries', '343.jpg');

insert into developer values(1, 'Aiden', 'Passionate and chill developer');
insert into developer values(2, 'Depressed Boi', 'Depressed and over-crunched developer who dived first into the industry hoping to monetize his passion but rather found only darkness');
insert into developer values(3, 'George', 'Inexperienced but passionate developer that is ready to do menial tasks');
insert into developer values(4, 'Jade', 'Senior experienced physics coder and passionate about incorporating game mechanics to the physics engine');
insert into developer values(5, 'Yasmine', 'Senior Project Lead. Passionate about bringing in different components such as sound, coding, design, and writing');
insert into developer values(6, 'Stacy', 'Junior story designer and writer. Particularly interested in ghost stories and spooky castles');

insert into workedWith values ('Relic Entertainment', 1);
insert into workedWith values ('Relic Entertainment', 4);
insert into workedWith values ('Relic Entertainment', 5);
insert into workedWith values ('Electronic Arts', 2);
insert into workedWith values ('Activision Blizzard', 3);
insert into workedWith values ('Ubisoft', 6);

insert into game values(1, 1, 'Valve', 'CSGO', 'FPS', 5);
insert into game values(2, 1, 'Riot Games', 'League of Legends', 'MOBA', 4);
insert into game values(3, 1, 'Mojang', 'Minecraft', 'Sandbox Survival', 4.5);
insert into game values(4, 1, 'Hasbro','Monopoly', 'Board Game', 2);
insert into game values(5, 1, 'Valve', 'Team Fortress 2', 'FPS', 2.2);
insert into game values(6, 1, 'Konami','Yu-Gi-Oh!', 'Card Game', 3.98);
insert into game values(7, 1, 'Electronic Arts','FIFA', 'Sports', 2.6);
insert into game values(8, 1, 'Ubisoft', 'Rainbox Six Siege', 'FPS', 3.65);
insert into game values(9, 1, 'Riot Games', 'Valorant', 'FPS', 1.4);
insert into game values(10, 1, 'Mojang', 'Minecraft Story Mode', 'Interactive Movie', 3.7);
insert into game values(11, 1, 'id Software', 'Wolfenstein', 'FPS', 3.8);
insert into game values(12, 1, 'Relic Entertainment', 'Age of Empires IV', 'Strategy', 2.456);
insert into game values(13, 1, 'Activision Blizzard', 'Call of Duty: Modern Warfare', 'FPS', 3.9);
insert into game values(14, 1, '343 Industries', 'Halo Infinite', 'FPS', 3.3);

insert into review values(1, 1, 1, 'Nice game');
insert into review values(1, 2, 2, 'Okay game');
insert into review values(1, 3, 3, 'Counter-Strike: Global Offensive is a multiplayer first-person shooter developed 
										by Valve and Hidden Path Entertainment. It is the fourth game in the Counter-Strike 
										series. Developed for over two years, Global Offensive was released for Windows, macOS, 
										Xbox 360, and PlayStation 3 in August 2012, and for Linux in 2014.');
insert into review values(5, 1, 1, 'Meh');
insert into review values(6, 1, 2, 'Alright game');
insert into review values(9, 1, 3, 'Nice game for noobs who cant get kills in csgo loool');

COMMIT WORK;
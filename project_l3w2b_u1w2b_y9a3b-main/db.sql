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
    (userID varchar(100) not null,
    username VARCHAR(100) not null,
    email VARCHAR(100) not null,
    password VARCHAR(100) not null,
    PRIMARY KEY (userID));

grant select on users to public;

create table verified
    (userID varchar(100) NOT NULL,
    PRIMARY KEY (userID),
    FOREIGN KEY (userID) REFERENCES users ON DELETE CASCADE);

grant select on verified to public;

create table admins
    (userID varchar(100) not null,
    primary key (userID),
    foreign key (userID) references users ON DELETE CASCADE);

grant select on admins to public;

create table gameStudio
    (studioName varchar(100) NOT NULL,
    image varchar(100),
    PRIMARY KEY (studioName));

grant select on gameStudio to public;

create table developer
    (devID varchar(100) NOT NULL,
    devName varchar(100),
    bio varchar(1000),
    PRIMARY KEY (devID));

grant select on developer to public;

create table workedWith
    (studioName varchar(100) NOT NULL,
    devID varchar(100) NOT NULL,
    PRIMARY KEY (studioName, devID),
    FOREIGN KEY (studioName) REFERENCES gameStudio ON DELETE CASCADE,
    FOREIGN KEY (devID) REFERENCES developer ON DELETE CASCADE);

grant select on workedWith to public;

create table game
    (gameID varchar(100) NOT NULL,
    adminID varchar(100) NOT NULL,
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
    (collectionID varchar(100) NOT NULL,
    userID varchar(100) NOT NULL,
    collectionName varchar(100),
    PRIMARY KEY (collectionID),
    FOREIGN KEY (userID) REFERENCES users ON DELETE CASCADE);

grant select on gameCollection to public;

create table review
    (reviewID varchar(100) NOT NULL,
    gameID varchar(100) NOT NULL,
    userID varchar(100) NOT NULL,
    text varchar(1000),
    PRIMARY KEY (reviewID, gameID),
    FOREIGN KEY (gameID) REFERENCES game ON DELETE CASCADE,
    FOREIGN KEY (userID) REFERENCES users ON DELETE CASCADE);

grant select on review to public;

create table scores
    (gameID varchar(100) NOT NULL,
    userID varchar(100) NOT NULL,
    score float,
    isLiked char(1),
    PRIMARY KEY (gameID, userID),
    FOREIGN KEY (gameID) REFERENCES game ON DELETE CASCADE,
    FOREIGN KEY (userID) REFERENCES users ON DELETE CASCADE);

grant select on scores to public;

create table newsStory
    (newsID int NOT NULL,
    verifiedUserID varchar(100) NOT NULL,
    adminUserID varchar(100) NOT NULL,
    text varchar(1000),
    image varchar(100),
    newsDate date,
    PRIMARY KEY (newsID),
    FOREIGN KEY (verifiedUserID) REFERENCES verified ON DELETE CASCADE,
    FOREIGN KEY (adminUserID) REFERENCES admins ON DELETE CASCADE);

grant select on newsStory to public;

create table addedGame
    (collectionID varchar(100) NOT NULL,
    gameID varchar(100) NOT NULL,
    PRIMARY KEY (collectionID, gameID),
    FOREIGN KEY (collectionID) REFERENCES gameCollection ON DELETE CASCADE,
    FOREIGN KEY (gameID) REFERENCES game ON DELETE CASCADE);

grant select on addedGame to public;


insert into users values('userid1','user1', 'user1@gmail.com', 'pwd1');
insert into users values('userid2','user2', 'user2@gmail.com', 'pwd2');
insert into users values('userid3','user3', 'user3@gmail.com', 'pwd3');

insert into admins values('userid1');

insert into verified values('userid2');
insert into verified values('userid3');

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

insert into developer values('dev1', 'Aiden', 'Passionate and chill developer');
insert into developer values('dev2', 'Depressed Boi', 'Depressed and over-crunched developer who dived first into the industry hoping to monetize his passion but rather found only darkness');
insert into developer values('dev3', 'George', 'Inexperienced but passionate developer that is ready to do menial tasks');
insert into developer values('dev4', 'Jade', 'Senior experienced physics coder and passionate about incorporating game mechanics to the physics engine');
insert into developer values('dev5', 'Yasmine', 'Senior Project Lead. Passionate about bringing in different components such as sound, coding, design, and writing');
insert into developer values('dev6', 'Stacy', 'Junior story designer and writer. Particularly interested in ghost stories and spooky castles');
insert into developer values('dev7', 'EdgeLordQQ', 'Anonymous and Mysterious developer');

insert into workedWith values ('Ubisoft', 'dev5');
insert into workedWith values ('Relic Entertainment', 'dev5');
insert into workedWith values ('Electronic Arts', 'dev5');
insert into workedWith values ('id Software', 'dev5');
insert into workedWith values ('Activision Blizzard', 'dev5');
insert into workedWith values ('Riot Games', 'dev5');
insert into workedWith values ('Valve', 'dev5');
insert into workedWith values ('Hasbro', 'dev5');
insert into workedWith values ('Konami', 'dev5');
insert into workedWith values ('Mojang', 'dev5');
insert into workedWith values ('343 Industries', 'dev5');

insert into workedWith values ('Ubisoft', 'dev2');
insert into workedWith values ('Relic Entertainment', 'dev2');
insert into workedWith values ('Electronic Arts', 'dev2');
insert into workedWith values ('id Software', 'dev2');
insert into workedWith values ('Activision Blizzard', 'dev2');
insert into workedWith values ('Riot Games', 'dev2');
insert into workedWith values ('Valve', 'dev2');
insert into workedWith values ('Hasbro', 'dev2');
insert into workedWith values ('Konami', 'dev2');
insert into workedWith values ('Mojang', 'dev2');
insert into workedWith values ('343 Industries', 'dev2');

insert into workedWith values ('Ubisoft', 'dev7');
insert into workedWith values ('Relic Entertainment', 'dev7');
insert into workedWith values ('Electronic Arts', 'dev7');
insert into workedWith values ('id Software', 'dev7');
insert into workedWith values ('Activision Blizzard', 'dev7');
insert into workedWith values ('Riot Games', 'dev7');
insert into workedWith values ('Valve', 'dev7');
insert into workedWith values ('Hasbro', 'dev7');
insert into workedWith values ('Konami', 'dev7');
insert into workedWith values ('Mojang', 'dev7');
insert into workedWith values ('343 Industries', 'dev7');

insert into workedWith values ('Relic Entertainment', 'dev1');
insert into workedWith values ('Relic Entertainment', 'dev4');
insert into workedWith values ('Activision Blizzard', 'dev3');
insert into workedWith values ('Ubisoft', 'dev6');
insert into workedWith values ('Hasbro', 'dev7');

insert into game values('game1', 'userid1', 'Valve', 'CSGO', 'FPS', 5);
insert into game values('game2', 'userid1', 'Riot Games', 'League of Legends', 'MOBA', 4);
insert into game values('game3', 'userid1', 'Mojang', 'Minecraft', 'Sandbox Survival', 4.5);
insert into game values('game4', 'userid1', 'Hasbro','Monopoly', 'Board Game', 2);
insert into game values('game5', 'userid1', 'Valve', 'Team Fortress 2', 'FPS', 2.2);
insert into game values('game6', 'userid1', 'Konami','Yu-Gi-Oh!', 'Card Game', 3.98);
insert into game values('game7', 'userid1', 'Electronic Arts','FIFA', 'Sports', 2.6);
insert into game values('game8', 'userid1', 'Ubisoft', 'Rainbox Six Siege', 'FPS', 3.65);
insert into game values('game9', 'userid1', 'Riot Games', 'Valorant', 'FPS', 1.4);
insert into game values('game10', 'userid1', 'Mojang', 'Minecraft Story Mode', 'Interactive Movie', 3.7);
insert into game values('game11', 'userid1', 'id Software', 'Wolfenstein', 'FPS', 3.8);
insert into game values('game12', 'userid1', 'Relic Entertainment', 'Age of Empires IV', 'Strategy', 2.456);
insert into game values('game13', 'userid1', 'Activision Blizzard', 'Call of Duty: Modern Warfare', 'FPS', 3.9);
insert into game values('game14', 'userid1', '343 Industries', 'Halo Infinite', 'FPS', 3.3);
insert into game values('game15', 'userid1', 'Relic Entertainment', 'Warhammer 40K: Dawn of War', 'Strategy', 4.2);
insert into game values('game16', 'userid1', 'Relic Entertainment', 'Company of Heroes', 'Strategy', 3.9);
insert into game values('game17', 'userid1', 'Relic Entertainment', 'Company of Heroes 2', 'Strategy', 4.8);
insert into game values('game18', 'userid1', 'Hasbro', 'Risk!', 'Strategy', 3.0);

insert into review values('review1', 'game1', 'userid1', 'Nice game');
insert into review values('review2', 'game2', 'userid1', 'Okay game');
insert into review values('review3', 'game1', 'userid2', 'Counter-Strike: Global Offensive is a multiplayer first-person shooter developed 
										by Valve and Hidden Path Entertainment. It is the fourth game in the Counter-Strike 
										series. Developed for over two years, Global Offensive was released for Windows, macOS, 
										Xbox 360, and PlayStation 3 in August 2012, and for Linux in 2014.');
insert into review values('review4', 'game5', 'userid3', 'Meh');
insert into review values('review5', 'game6', 'userid3', 'Alright game');
insert into review values('review6', 'game2', 'userid3', 'Nice game for noobs who cant get kills in csgo loool');

COMMIT WORK;
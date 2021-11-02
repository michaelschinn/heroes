CREATE TABLE heroes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name varchar(50) UNIQUE NOT NULL,
    about_me varchar(250) NOT null,
    biography text NOT NULL,
    image_url VARCHAR(300)
);

INSERT INTO heroes (name, about_me, biography) VALUES ('Chill Woman', 'The coolest woman you''ll ever meet.', 'In a freak industrial accident, Chill Woman was dunked in toxic waste. After an agonizing transformation, she developed the ability to exhale sub-zero mist that freezes everything it touches.');
INSERT INTO heroes (name, about_me, biography) VALUES ('Mental Mary', 'Her name may be ordinary, but her powers are not!', 'Once a famous medical researcher, Mental Mary performed an experimental procedure on herself - with unexpected results. Her full mental potential was unlocked, giving her powers over the physical world and the minds of those around her.');
INSERT INTO heroes (name, about_me, biography) VALUES ('Muscles McMuscleWoman', 'Brute strength will not solve all problems, but she doesn''t know that.', 'Born on another planet and stranded here during an intergalactic training exercise, Muscles'' muscles expanded to gigantic proportion in Earth''s nitrogen-rich atmosphere, giving her amazing strength. The extra arms don''t hurt, either.');
INSERT INTO heroes (name, about_me, biography) VALUES ('The Hummingbird', 'He flies and he''s really fast.', 'Perhaps the next step in human evolution, The Hummingbird''s unique abilities manifested shortly after birth, when he floated out of the hospital nursery and into the care OF General Allen Fitzpatrick and his Gamma Team. After Fitzpatrick''s death at the hands of Omega Force, The Hummingbird went rogue...FOR REVENGE!');
INSERT INTO heroes (name, about_me, biography) VALUES ('The Seer', 'He can see into your soul. Literally.', 'The Seer leads a normal life, so long AS he wears his specially-shielded glasses. Once he removes them, he can see through walls, mountains, flesh - TO the secrets held within.');
INSERT INTO heroes (name, about_me, biography) VALUES ('Lidar Man', 'Born without the ability to see, Lidar Man learned to use his ears as a child. One day he was hit with an intense ray of gamma radiation and the only way the doctors could fix him was to add nanotech robots into his brain.', 'Because of the gamma radiation and nanotech combo, he now has the ability to see everyday objects using his mind, and with immense control he can even zoom in 1000X away!');

CREATE TABLE relationship_types (
    id INT PRIMARY KEY AUTO_INCREMENT,
    type varchar(20) UNIQUE NOT NULL
);

INSERT INTO relationship_types (type) VALUES ('Friend');
INSERT INTO relationship_types (type) VALUES ('Enemy');

CREATE TABLE relationships (
    id INT PRIMARY KEY AUTO_INCREMENT,
    hero1_id INTEGER NOT NULL,
    FOREIGN KEY (hero1_id) REFERENCES heroes (id) ON DELETE CASCADE,
    hero2_id INTEGER NOT NULL,
    FOREIGN KEY (hero2_id) REFERENCES heroes (id) ON DELETE CASCADE,
    type_id INTEGER NOT NULL,
    FOREIGN KEY (type_id) REFERENCES relationship_types (id) ON DELETE CASCADE
);

INSERT INTO relationships (hero1_id, hero2_id, type_id) VALUES (1, 2, 1);
INSERT INTO relationships (hero1_id, hero2_id, type_id) VALUES (2, 1, 1);
INSERT INTO relationships (hero1_id, hero2_id, type_id) VALUES (2, 3, 2);
INSERT INTO relationships (hero1_id, hero2_id, type_id) VALUES (4, 1, 2);
INSERT INTO relationships (hero1_id, hero2_id, type_id) VALUES (4, 2, 2);
INSERT INTO relationships (hero1_id, hero2_id, type_id) VALUES (4, 3, 2);
INSERT INTO relationships (hero1_id, hero2_id, type_id) VALUES (4, 5, 2);
INSERT INTO relationships (hero1_id, hero2_id, type_id) VALUES (3, 1, 1);
INSERT INTO relationships (hero1_id, hero2_id, type_id) VALUES (3, 2, 1);
INSERT INTO relationships (hero1_id, hero2_id, type_id) VALUES (3, 5, 1);
INSERT INTO relationships (hero1_id, hero2_id, type_id) VALUES (5, 1, 1);
INSERT INTO relationships (hero1_id, hero2_id, type_id) VALUES (5, 3, 1);
INSERT INTO relationships (hero1_id, hero2_id, type_id) VALUES (6, 1, 1);
INSERT INTO relationships (hero1_id, hero2_id, type_id) VALUES (6, 2, 2);
INSERT INTO relationships (hero1_id, hero2_id, type_id) VALUES (6, 5, 1);

CREATE TABLE ability_type (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ability VARCHAR(50)
);

INSERT INTO ability_type (ability) VALUES ('Super Strength');
INSERT INTO ability_type (ability) VALUES ('Flying');
INSERT INTO ability_type (ability) VALUES ('Telekinesis');
INSERT INTO ability_type (ability) VALUES ('Telepathy');
INSERT INTO ability_type (ability) VALUES ('Frost Breath');
INSERT INTO ability_type (ability) VALUES ('Super Speed');
INSERT INTO ability_type (ability) VALUES ('Super Vision');

CREATE TABLE abilities (
    id INT PRIMARY KEY AUTO_INCREMENT,
    hero_id INTEGER NOT NULL,
    FOREIGN KEY (hero_id) REFERENCES heroes (id) ON DELETE CASCADE,
    ability_id INTEGER NOT NULL,
    FOREIGN KEY (ability_id) REFERENCES ability_type (id) ON DELETE CASCADE
);

INSERT INTO abilities (hero_id, ability_id) VALUES (1, 5);
INSERT INTO abilities (hero_id, ability_id) VALUES (2, 3);
INSERT INTO abilities (hero_id, ability_id) VALUES (2, 4);
INSERT INTO abilities (hero_id, ability_id) VALUES (3, 1);
INSERT INTO abilities (hero_id, ability_id) VALUES (4, 2);
INSERT INTO abilities (hero_id, ability_id) VALUES (4, 6);
INSERT INTO abilities (hero_id, ability_id) VALUES (5, 7);
INSERT INTO abilities (hero_id, ability_id) VALUES (6, 7);
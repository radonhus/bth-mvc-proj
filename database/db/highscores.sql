USE riax20;

DROP TABLE IF EXISTS highscores;
CREATE TABLE IF NOT EXISTS highscores
(
	id INT AUTO_INCREMENT NOT NULL,
    player VARCHAR(40) NOT NULL,
	score INT NOT NULL,
    date_played TIMESTAMP,

	PRIMARY KEY (id)
)
;

INSERT INTO highscores
    (player, score)
VALUES
	('Janne', 10),
    ('Kalle', 20),
    ('Holger', 30)
;

SELECT * FROM highscores;
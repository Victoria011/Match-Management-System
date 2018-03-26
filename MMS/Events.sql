CREATE TABLE IF NOT EXISTS `Events`(
    MatchId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    StartTime Time NOT NULL,
    Duration VARCHAR(255) NOT NULL,
    MatchDate Date NOT NULL,
    Info VARCHAR(255),
    Location VARCHAR(255) NOT NULL,
    Capacity VARCHAR(255) NOT NULL
);

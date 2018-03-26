-- user register match

CREATE TABLE IF NOT EXISTS `Register`(
    RegisterId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    UserId INT NOT NULL,
    Username VARCHAR(25) NOT NULL,
    Position VARCHAR(25),
    MatchId INT NOT NULL
);

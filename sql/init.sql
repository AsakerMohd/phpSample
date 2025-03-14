-- sql/init.sql
CREATE TABLE IF NOT EXISTS Users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  FirstName VARCHAR(50),
  LastName VARCHAR(50)
);

INSERT INTO Users (FirstName, LastName)
VALUES
  ('John', 'Doe'),
  ('Jane', 'Smith'),
  ('Alice', 'Johnson');

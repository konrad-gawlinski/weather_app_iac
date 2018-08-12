CREATE DATABASE IF NOT EXISTS weather_app
  CHARACTER SET utf8
  COLLATE utf8_general_ci;

USE weather_app;

CREATE TABLE IF NOT EXISTS region (
  id INT NOT NULL AUTO_INCREMENT UNIQUE,
  timezone VARCHAR(50) NOT NULL,
  country VARCHAR(2) NOT NULL,
  city VARCHAR(50) NOT NULL,
  PRIMARY KEY (country, city)
);

CREATE TABLE IF NOT EXISTS data (
  region_id INT NOT NULL,
  datetime DATE NOT NULL,
  temp FLOAT,
  max_temp FLOAT,
  min_temp FLOAT,
  FOREIGN KEY (region_id) REFERENCES region(id)
);
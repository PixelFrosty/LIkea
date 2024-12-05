CREATE DATABASE likeadb;

USE likeadb;

CREATE TABLE region(
    ID int PRIMARY KEY,
    location varchar(15) NOT NULL UNIQUE
);

CREATE TABLE branch(
    ID int AUTO_INCREMENT PRIMARY KEY,
    regionID int,
    FOREIGN KEY(regionID) REFERENCES region(ID) ON DELETE CASCADE
);

CREATE TABLE item(
    ID int AUTO_INCREMENT PRIMARY KEY,
    name varchar(25) NOT NULL,
    type varchar(30) NOT NULL,
    material varchar(30) NOT NULL,
    brand varchar(50) NOT NULL,
    year int DEFAULT 2024,
    price float NOT NULL,
    sale int DEFAULT 0,
    branchID int NOT NULL,
    FOREIGN KEY(branchID) REFERENCES branch(ID) ON DELETE CASCADE
);

CREATE TABLE user(
    ID int AUTO_INCREMENT PRIMARY KEY,
    name varchar(50) NOT NULL,
    email varchar(50) NOT NULL UNIQUE,
    phone varchar(20) UNIQUE,
    password varchar(60) NOT NULL UNIQUE,
    created timestamp DEFAULT CURRENT_TIMESTAMP,
    regionID int,
    FOREIGN KEY(regionID) REFERENCES region(ID) ON DELETE SET NULL
);

CREATE TABLE cart(
    userID int,
    itemID int,
    time timestamp DEFAULT CURRENT_TIMESTAMP,
    quantity int DEFAULT 0,
    PRIMARY KEY(userID, itemID),
    FOREIGN KEY(userID) REFERENCES user(ID) ON DELETE CASCADE,
    FOREIGN KEY(itemID) REFERENCES item(ID) ON DELETE CASCADE
);

CREATE TABLE list(
    listID int AUTO_INCREMENT PRIMARY KEY,
    userID int,
    time timestamp DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(userID) REFERENCES user(ID) ON DELETE CASCADE
);

CREATE TABLE inlist(
    listID int,
    itemID int,
    time timestamp DEFAULT CURRENT_TIMESTAMP,
    quantity int DEFAULT 0,
    PRIMARY KEY(listID, itemID),
    FOREIGN KEY(listID) REFERENCES list(ID) ON DELETE CASCADE,
    FOREIGN KEY(itemID) REFERENCES item(ID) ON DELETE CASCADE
);

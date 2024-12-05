CREATE DATABASE likeadb;

USE likeadb;

CREATE TABLE region(
    regionID int PRIMARY KEY,
    location varchar(15) NOT NULL UNIQUE
);

CREATE TABLE branch(
    branchID int AUTO_INCREMENT PRIMARY KEY,
    regionID int,
    branchPhoneNumber varchar(20),
    branchAddr varchar(60),
    FOREIGN KEY(regionID) REFERENCES region(regionID) ON DELETE CASCADE
);

CREATE TABLE item(
    itemID int AUTO_INCREMENT PRIMARY KEY,
    name varchar(25) NOT NULL,
    type varchar(30) NOT NULL,
    material varchar(30) NOT NULL,
    brand varchar(50) NOT NULL,
    year int DEFAULT 2024,
    price float NOT NULL,
    sale float DEFAULT 1,
    branchID int NOT NULL,
    FOREIGN KEY(branchID) REFERENCES branch(branchID) ON DELETE CASCADE
);

CREATE TABLE user(
    userID int AUTO_INCREMENT PRIMARY KEY,
    name varchar(50) NOT NULL,
    email varchar(50) NOT NULL UNIQUE,
    phone varchar(20) UNIQUE,
    password varchar(60) NOT NULL UNIQUE,
    created timestamp DEFAULT CURRENT_TIMESTAMP,
    regionID int,
    FOREIGN KEY(regionID) REFERENCES region(regionID) ON DELETE SET NULL,
    is_Admin TINYINT(1) NOT NULL DEFAULT 0
);

CREATE TABLE cart(
    userID int,
    itemID int,
    time timestamp DEFAULT CURRENT_TIMESTAMP,
    quantity int DEFAULT 0,
    PRIMARY KEY(userID, itemID),
    FOREIGN KEY(userID) REFERENCES user(userID) ON DELETE CASCADE,
    FOREIGN KEY(itemID) REFERENCES item(itemID) ON DELETE CASCADE
);

CREATE TABLE list(
    listID int AUTO_INCREMENT PRIMARY KEY,
    listName varchar(25) DEFAULT "New List",
    userID int,
    time timestamp DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(userID) REFERENCES user(userID) ON DELETE CASCADE
);

CREATE TABLE inlist(
    listID int,
    itemID int,
    time timestamp DEFAULT CURRENT_TIMESTAMP,
    quantity int DEFAULT 0,
    PRIMARY KEY(listID, itemID),
    FOREIGN KEY(listID) REFERENCES list(listID) ON DELETE CASCADE,
    FOREIGN KEY(itemID) REFERENCES item(itemID) ON DELETE CASCADE
);
-- ***************FOR FURNITURE PAGE**********************************************************

-- For displaying a total number of items in the store
SELECT COUNT(*) FROM item;


SELECT *
-- Finding furniture in a user's region
FROM (
    SELECT 
        i.itemID, 
        i.name AS item_name, 
        i.type AS item_type, 
        i.material, 
        i.brand, 
        i.year, 
        i.price AS originalPrice,
        ROUND(i.price * i.sale, 2) AS salePrice,
        r.location AS region
    FROM 
        item i
    JOIN 
        branch b ON i.branchID = b.branchID
    JOIN 
        region r ON b.regionID = r.regionID
    JOIN 
        user u ON u.regionID = r.regionID
    WHERE 
        u.userID = <userID>
        AND i.itemID = <itemID>
) AS furnitureAvailableInRegion;

SELECT * FROM (SELECT * FROM item) AS furnitureListings;

-- Display a count of results from a query
SELECT COUNT(*) FROM furnitureListings;


SELECT * FROM
(    SELECT 
    -- Informs a user where an item is available
        i.itemID, 
        i.name AS item_name, 
        i.type AS item_type, 
        r.location AS region, 
        b.branchID AS branch
    FROM 
        item i
    JOIN 
        branch b ON i.branchID = b.branchID
    JOIN 
        region r ON b.regionID = r.regionID
    WHERE 
        i.itemID = <ITEM_ID>) AS itemsInARegion;

SELECT * FROM
(    SELECT 
-- Select based on ID
        itemID, 
        name AS item_name, 
        type AS item_type, 
        material, 
        brand, 
        year, 
        price AS originalPrice,
        ROUND(price * sale, 2) AS salePrice,
        branchID
    FROM item
    WHERE
        itemID = <itemID>) AS furnitureListings;

SELECT * FROM
(    SELECT 
-- Select based on name
        itemID, 
        name AS item_name, 
        type AS item_type, 
        material, 
        brand, 
        year, 
        price AS originalPrice,
        ROUND(price * sale, 2) AS salePrice,
        branchID
    FROM item
    WHERE
        name = <name>) AS furnitureListings;


SELECT * FROM
(    SELECT 
-- Select based on type
        itemID, 
        name AS item_name, 
        type AS item_type, 
        material, 
        brand, 
        year, 
        price AS originalPrice,
        ROUND(price * sale, 2) AS salePrice,
        branchID
    FROM item
    WHERE
        type = <type>) AS furnitureListings;


SELECT * FROM
(    SELECT 
-- Select based on material
        itemID, 
        name AS item_name, 
        type AS item_type, 
        material, 
        brand, 
        year, 
        price AS originalPrice,
        ROUND(price * sale, 2) AS salePrice,
        branchID
    FROM item
    WHERE
        material = <material>) AS furnitureListings;


SELECT * FROM
(    SELECT 
-- Select based on brand
        itemID, 
        name AS item_name, 
        type AS item_type, 
        material, 
        brand, 
        year, 
        price AS originalPrice,
        ROUND(price * sale, 2) AS salePrice,
        branchID
    FROM item
    WHERE
        brand = <brand>) AS furnitureListings;


SELECT * FROM
(    SELECT 
-- Select based on year
        itemID, 
        name AS item_name, 
        type AS item_type, 
        material, 
        brand, 
        year, 
        price AS originalPrice,
        ROUND(price * sale, 2) AS salePrice,
        branchID
    FROM item
    WHERE
        year = <year>) AS furnitureListings;


SELECT * 
FROM (
    SELECT 
        -- Select based on if it is on sale or not
        itemID, 
        name AS item_name, 
        type AS item_type, 
        material, 
        brand, 
        year, 
        price AS originalPrice,
        ROUND(price * sale, 2) AS salePrice,
        branchID
    FROM item
    WHERE sale < 1
) AS furnitureListings
ORDER BY salePrice ASC;


SELECT * FROM
    (SELECT 
        name AS itemName,
        price AS originalPrice,
        ROUND(price * sale, 2) AS salePrice
    FROM 
        item
        ORDER BY salePrice ASC) AS pricesTable;


INSERT INTO cart (userID, itemID, quantity)
-- User adds something to their cart
VALUES (<USER_ID>, <ITEM_ID>, <QUANTITY>)
ON DUPLICATE KEY UPDATE 
    quantity = quantity + <QUANTITY>;


SELECT listName
--Displays the names of all lists a user has
FROM list
WHERE userID = <userID>;


-- %%%%%%%%%% ADDING TO A LIST %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
INSERT INTO list (userID)
-- Step 1: check to see if a list with the chosen ID already exists
VALUES (<USER_ID>)
ON DUPLICATE KEY UPDATE 
    listID = listID;

SELECT listID
-- Step 2: Get the list from the database
FROM list
WHERE userID = <USER_ID>;

INSERT INTO inlist (listID, itemID, quantity)
-- Step 3: add item and quantity of item to list
VALUES (<LIST_ID>, <ITEM_ID>, <QUANTITY>)
ON DUPLICATE KEY UPDATE 
    quantity = quantity + <QUANTITY>;
-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

-- **** FOR LISTS PAGE **********************************************************************

INSERT INTO list (listName, userID)
-- Creates a new list with a user-defined name
VALUES (<LIST_NAME>, <USER_ID>);


SELECT * FROM
    (SELECT 
    -- View all items in a particular list
        i.name AS ItemName,
        il.Quantity,
        l.time AS TimeCreated
    FROM 
        list l
    JOIN 
        inlist il ON l.listID = il.listID
    JOIN 
        item i ON il.itemID = i.itemID
    WHERE 
        l.listID = <listID> 
        AND l.userID = <userID>
        AND l.listName = <listName>) AS listContents;


DELETE FROM list
--Deleting a list
WHERE listID = <specified_ID>;


DELETE FROM inlist
--Remove all items from a list but preserve the list
WHERE listID = <listID>;


DELETE FROM inlist
--Removes a specific item from a list
WHERE listID = <listID> 
AND itemID = <itemID>;

--Displays a count of items in a user's list
SELECT COUNT(*) 
FROM inlist i
JOIN list l ON i.listID = l.listID
WHERE l.userID = <userID>;

-- **************************************************************************

-- **** FOR CART PAGE **********************************************************************

SELECT 
-- View all items in a user's cart
    i.name AS item_name, 
    c.quantity AS item_quantity, 
    ROUND(i.price * i.sale, 2) AS sale_price
FROM 
    cart c
JOIN 
    item i ON c.itemID = i.itemID
WHERE 
    c.userID = <userID>;


DELETE FROM cart 
-- Delete a specific item from the cart
WHERE itemID = <itemID>;


DELETE FROM cart
-- Clear the cart
WHERE userID = <userID>;


UPDATE cart
-- Update the quantity of an item in a cart
SET quantity = <newQuantity>
WHERE userID = <userID> AND itemID = <itemID>;

-- Displays the number of items in a user's cart
SELECT COUNT(*) FROM cart WHERE userID = <userID>;

-- **************************************************************************

-- *** FOR PROFILE PAGE ***********************************************************************

UPDATE user
SET name = <new name>
WHERE userID = <userID>

UPDATE user
SET email = <new email>
WHERE userID = <userID>

UPDATE user
SET phone = <new phone number>
WHERE userID = <userID>

UPDATE user
SET password = <new password>
WHERE userID = <userID>

UPDATE user
SET regionID = <new regionID>
WHERE userID = <userID>


-- **************************************************************************

-- **** FOR BRANCH INFO PAGE **********************************************************************

SELECT 
-- Get branch info based on user region
    b.managerName AS Manager_Name,
    b.branchPhoneNumber AS Branch_Phone_Number,
    b.branchAddr AS Branch_Address
    r.location AS Location
FROM 
    branch b
JOIN 
    region r ON b.regionID = r.regionID
JOIN
    user u ON u.regionID = r.regionID
WHERE
    u.userID = <userID>;

    

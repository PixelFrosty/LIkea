-- ***************FOR FURNITURE PAGE**********************************************************

SELECT 
-- Query to see if an item is available in a user's region
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
    AND i.itemID = <itemID>;


SELECT 
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
    i.itemID = <ITEM_ID>;


    SELECT 
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
    name = <name>;


SELECT 
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
    itemID = <itemID>;


SELECT 
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
    type = <type>;


SELECT 
-- Select based on  material
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
    material = <material>;


    SELECT 
-- Select based on  material
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
    brand = <brand>;


    SELECT 
-- Select based on  material
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
    year = <year>;


SELECT 
    name AS itemName,
    price AS originalPrice,
    ROUND(price * sale, 2) AS salePrice
FROM 
    item
    ORDER BY salePrice ASC;


    INSERT INTO cart (userID, itemID, quantity)
-- User adds something to their cart
VALUES (<USER_ID>, <ITEM_ID>, <QUANTITY>)
ON DUPLICATE KEY UPDATE 
    quantity = quantity + <QUANTITY>;

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
-- **************************************************************************








-- **** FOR LISTS PAGE **********************************************************************

INSERT INTO list (listName, userID)
-- Creates a new list with a user-defined name
VALUES (<LIST_NAME>, <USER_ID>);


SELECT 
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
    AND l.listName = <listName>;


DELETE FROM list
--Deleting a list
WHERE listID = <specified_ID>;


DELETE FROM inlist
--Removes a specific item from a list
WHERE listID = <listID> 
AND itemID = <itemID>;
-- **************************************************************************


DELETE FROM cart 
WHERE itemID = <itemID>;




-- Lisää CREATE TABLE lauseet tähän tiedostoon



CREATE TABLE users (
id INTEGER NOT NULL AUTO_INCREMENT,
account VARCHAR(255) NOT NULL,
first_name VARCHAR(255) NULL,
last_name VARCHAR(255) NULL,
phone VARCHAR(255) NULL,
hash VARCHAR(255) NOT NULL,
KEY lime_users_account (account),
KEY lime_users_last_name (last_name),
KEY lime_users_first_name (first_name),
CONSTRAINT lime_users_PK 
	PRIMARY KEY (id),
CONSTRAINT lime_account_users_U
	UNIQUE (account)
);


CREATE TABLE usergroup (
id INTEGER NOT NULL AUTO_INCREMENT,
name VARCHAR(255) NOT NULL,
KEY lime_usergroup_name(name),
CONSTRAINT lime_usergroup_PK 
	PRIMARY KEY (id)
) 
;


CREATE TABLE product (
id INTEGER NOT NULL AUTO_INCREMENT,
name VARCHAR(200) NOT NULL,
unit VARCHAR(10) NULL,
KEY lime_product_name(name),
CONSTRAINT lime_product_PK 
	PRIMARY KEY (id)
);

CREATE TABLE shop (
id INTEGER NOT NULL AUTO_INCREMENT,
name VARCHAR(200) NOT NULL,
KEY lime_shop_name(name),
CONSTRAINT lime_shop_PK 
	PRIMARY KEY (id)
);



CREATE TABLE shopproduct (
product_id INTEGER NOT NULL,
shop_id INTEGER NOT NULL,
price DECIMAL(18,5) NULL DEFAULT 0,
quantity DECIMAL(18,5) NULL,
quantity_unit VARCHAR(200) NULL,
CONSTRAINT lime_shopproduct_PK 
	PRIMARY KEY (product_id, shop_id)
) 
;


CREATE TABLE shoppinglist (
id INTEGER NOT NULL AUTO_INCREMENT,
name VARCHAR(255) NOT NULL,
CONSTRAINT lime_shoppinglist_PK 
	PRIMARY KEY (id)
) 
;
CREATE TABLE shoppinglistitem (
item_id INTEGER NOT NULL,
shop_id INTEGER NOT NULL,
name VARCHAR(255) NOT NULL,
CONSTRAINT lime_lineitem_PK 
	PRIMARY KEY (item_id, shop_id)
) 
;
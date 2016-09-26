

CREATE TABLE users (
id SERIAL,
account VARCHAR(255) NOT NULL,
first_name VARCHAR(255) NULL,
last_name VARCHAR(255) NULL,
phone VARCHAR(255) NULL,
hash VARCHAR(255) NOT NULL,
CONSTRAINT users_PK 
	PRIMARY KEY (id),
CONSTRAINT account_users_U
	UNIQUE (account)
);


CREATE TABLE usergroup (
id SERIAL,
name VARCHAR(255) NOT NULL,
created_by INTEGER NOT NULL,
CONSTRAINT usergroup_PK 
	PRIMARY KEY (id),
CONSTRAINT usergroup_created_by_FK 
	FOREIGN KEY (created_by)
	REFERENCES users (id)
		ON DELETE CASCADE 
		ON UPDATE CASCADE
) 
;
CREATE TABLE usergroup_users (
usergroup_id INTEGER NOT NULL,
users_id INTEGER NOT NULL,
CONSTRAINT usergroup_user_PK 
	PRIMARY KEY (usergroup_id, users_id),
CONSTRAINT usergroup_user_usergroup_id_FK 
	FOREIGN KEY (usergroup_id)
	REFERENCES usergroup (id)
		ON DELETE CASCADE 
		ON UPDATE CASCADE,
CONSTRAINT usergroup_user_users_id_FK 
	FOREIGN KEY (users_id)
	REFERENCES users (id)
		ON DELETE CASCADE 
		ON UPDATE CASCADE
) 
;

CREATE TABLE product (
id SERIAL,
name VARCHAR(200) NOT NULL,
created_by INTEGER NOT NULL,
CONSTRAINT product_PK 
	PRIMARY KEY (id),
CONSTRAINT product_created_by_FK 
	FOREIGN KEY (created_by)
	REFERENCES users (id)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);

CREATE TABLE shop (
id SERIAL,
name VARCHAR(200) NOT NULL,
created_by INTEGER NOT NULL,
CONSTRAINT shop_PK 
	PRIMARY KEY (id),
CONSTRAINT shop_created_by_FK 
	FOREIGN KEY (created_by)
	REFERENCES users (id)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);

CREATE TABLE shop_usergroup (
usergroup_id INTEGER NOT NULL,
shop_id INTEGER NOT NULL,
created_by INTEGER NOT NULL,
CONSTRAINT shop_usergroup_PK 
	PRIMARY KEY (usergroup_id, shop_id),
CONSTRAINT shop_usergroup_usergroup_id_FK 
	FOREIGN KEY (usergroup_id)
	REFERENCES usergroup (id)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
CONSTRAINT shop_usergroup_shop_id_FK 
	FOREIGN KEY (shop_id)
	REFERENCES shop (id)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
CONSTRAINT shop_usergroup_created_by_FK 
	FOREIGN KEY (created_by)
	REFERENCES users (id)
		ON DELETE CASCADE
		ON UPDATE CASCADE
) 
;


CREATE TABLE shop_product (
product_id INTEGER NOT NULL,
shop_id INTEGER NOT NULL,
price DECIMAL(18,5) NULL DEFAULT 0,
created_by INTEGER NOT NULL,
updated TIMESTAMP NOT NULL DEFAULT NOW(),
CONSTRAINT shop_product_PK 
	PRIMARY KEY (product_id, shop_id),
CONSTRAINT shop_product_product_id_FK 
	FOREIGN KEY (product_id)
	REFERENCES product (id)
		ON DELETE CASCADE 
		ON UPDATE CASCADE,
CONSTRAINT shop_product_shop_id_FK 
	FOREIGN KEY (shop_id)
	REFERENCES shop (id)
		ON DELETE CASCADE 
		ON UPDATE CASCADE,
CONSTRAINT shopproduct_created_by_FK 
	FOREIGN KEY (created_by)
	REFERENCES users (id)
		ON DELETE CASCADE
		ON UPDATE CASCADE
) 
;


CREATE TABLE shoppinglist (
id SERIAL,
name VARCHAR(255) NOT NULL,
created_by INTEGER NOT NULL,
active TIMESTAMP NOT NULL DEFAULT NOW(),
CONSTRAINT shoppinglist_PK 
	PRIMARY KEY (id),
CONSTRAINT shoppinglist_created_by_FK 
	FOREIGN KEY (created_by)
	REFERENCES users (id)
		ON DELETE NO ACTION
		ON UPDATE CASCADE
) 
;

CREATE TABLE shoppinglist_usergroup (
shoppinglist_id INTEGER NOT NULL,
usergroup_id INTEGER NOT NULL,
created_by INTEGER NOT NULL,
CONSTRAINT shoppinglist_usergroup_PK 
	PRIMARY KEY (shoppinglist_id, usergroup_id),
CONSTRAINT shoppinglist_usergroup_shoppinglist_id_FK 
	FOREIGN KEY (shoppinglist_id)
	REFERENCES shoppinglist (id)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
CONSTRAINT shoppinglist_usergroup_usergroup_id_FK 
	FOREIGN KEY (usergroup_id)
	REFERENCES usergroup (id)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
CONSTRAINT shoppinglist_usergroup_created_by_FK 
	FOREIGN KEY (created_by)
	REFERENCES users (id)
		ON DELETE NO ACTION
		ON UPDATE CASCADE
) 
;

CREATE TABLE shoppinglist_product (
shoppinglist_id INTEGER NOT NULL,
product_id INTEGER NOT NULL,
description VARCHAR(255) NOT NULL,
quantity DECIMAL(18,5) NULL,
created_by INTEGER NOT NULL,
created TIMESTAMP NOT NULL DEFAULT NOW(),
CONSTRAINT shoppinglist_product_PK 
	PRIMARY KEY (product_id, shoppinglist_id),
CONSTRAINT shoppinglist_product_product_id_FK 
	FOREIGN KEY (product_id)
	REFERENCES product (id)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
CONSTRAINT shoppinglist_product_shoppinglist_id_FK 
	FOREIGN KEY (shoppinglist_id)
	REFERENCES shoppinglist (id)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
CONSTRAINT shoppinglist_product_created_by_FK 
	FOREIGN KEY (created_by)
	REFERENCES users (id)
		ON DELETE NO ACTION
		ON UPDATE CASCADE
) 
;



CREATE VIEW all_usergroup_users AS 
	(SELECT u.created_by AS users_id, u.id AS usergroup_id FROM usergroup u)
UNION
	(SELECT uu.users_id, uu.usergroup_id FROM usergroup_users uu);

	

CREATE VIEW shoppinglist_users AS 
	(SELECT s.created_by AS users_id, s.id AS shoppinglist_id FROM shoppinglist s)
UNION
	(SELECT uu.users_id, su.shoppinglist_id
	FROM usergroup_users uu
	JOIN shoppinglist_usergroup su ON uu.usergroup_id=su.usergroup_id
	)
UNION
	(SELECT u.created_by AS users_id, slu.shoppinglist_id
	FROM shoppinglist_usergroup slu
	JOIN usergroup u ON u.id=slu.usergroup_id
	)
;

	

CREATE VIEW shop_users AS 
	(SELECT s.created_by AS users_id, s.id AS shop_id FROM shop s)
UNION
	(SELECT uu.users_id, su.shop_id
	FROM usergroup_users uu
	JOIN shop_usergroup su ON uu.usergroup_id=su.usergroup_id
	)
UNION
	(SELECT u.created_by AS users_id, slu.shop_id
	FROM shop_usergroup slu
	JOIN usergroup u ON u.id=slu.usergroup_id
	)
;


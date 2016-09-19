

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

CREATE TABLE unit (
id SERIAL,
name VARCHAR(20) NOT NULL,
CONSTRAINT unit_PK 
	PRIMARY KEY (id)
) 
;

CREATE TABLE product (
id SERIAL,
name VARCHAR(200) NOT NULL,
default_unit_id INTEGER NOT NULL,
created_by INTEGER NOT NULL,
CONSTRAINT product_PK 
	PRIMARY KEY (id),
CONSTRAINT product_default_unit_id_FK 
	FOREIGN KEY (default_unit_id)
	REFERENCES unit (id)
		ON DELETE NO ACTION
		ON UPDATE CASCADE,
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
quantity DECIMAL(18,5) NULL,
unit_id INTEGER NOT NULL,
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
CONSTRAINT shop_product_unit_id_FK 
	FOREIGN KEY (unit_id)
	REFERENCES unit (id)
		ON DELETE NO ACTION
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
unit_id INTEGER NOT NULL,
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


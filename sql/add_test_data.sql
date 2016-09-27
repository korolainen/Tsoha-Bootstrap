

INSERT INTO users(account, first_name, last_name, phone, hash) VALUES('valtteri.korolainen@helsinki.fi', 'Valtteri', 'Korolainen', '0445651000', 'd8df40b23bce40c31cdff73f4fd694b33h589l4ik6ho9689rtx78vdi4r497tnls8ti22sma958yqd9o7hyufa66nk940e1a33fa8b09ef49cdbac3a8c6f908');
INSERT INTO users(account, first_name, last_name, phone, hash) VALUES('familymember@korolainen.fi', 'Family', 'Member', '', 'd8df40b23bce40c31cdff73f4fd694b33h589l4ik6ho9689rtx78vdi4r497tnls8ti22sma958yqd9o7hyufa66nk940e1a33fa8b09ef49cdbac3a8c6f908');
INSERT INTO users(account, first_name, last_name, phone, hash) VALUES('otheruser@korolainen.fi', 'Other', 'User', '', 'd8df40b23bce40c31cdff73f4fd694b33h589l4ik6ho9689rtx78vdi4r497tnls8ti22sma958yqd9o7hyufa66nk940e1a33fa8b09ef49cdbac3a8c6f908');

INSERT INTO usergroup(name, created_by) VALUES('Perhe', 1);

INSERT INTO usergroup_users(usergroup_id, users_id) VALUES(1,1);
INSERT INTO usergroup_users(usergroup_id, users_id) VALUES(1,2);

INSERT INTO product(name, created_by) VALUES('maito', 1);
INSERT INTO product(name, created_by) VALUES('voi', 1);
INSERT INTO product(name, created_by) VALUES('oltermanni', 1);
INSERT INTO product(name, created_by) VALUES('jauheliha', 1);
INSERT INTO product(name, created_by) VALUES('tomaatti', 1);
INSERT INTO product(name, created_by) VALUES('murot', 1);

INSERT INTO shop(name, created_by) VALUES('S-market', 1);
INSERT INTO shop(name, created_by) VALUES('Lidl', 1);
INSERT INTO shop(name, created_by) VALUES('Alepa', 1);
INSERT INTO shop(name, created_by) VALUES('K-kauppa', 1);
INSERT INTO shop(name, created_by) VALUES('R-kioski', 2);

INSERT INTO shop_usergroup(usergroup_id, shop_id, created_by) VALUES(1, 1, 1);
INSERT INTO shop_usergroup(usergroup_id, shop_id, created_by) VALUES(1, 2, 1);

INSERT INTO shop_product(product_id, shop_id, price, created_by) VALUES(1, 1, 0.75, 1);
INSERT INTO shop_product(product_id, shop_id, price, created_by) VALUES(1, 2, 0.55, 1);
INSERT INTO shop_product(product_id, shop_id, price, created_by) VALUES(2, 1, 2.5, 1);
INSERT INTO shop_product(product_id, shop_id, price, created_by) VALUES(2, 2, 2.6, 1);

INSERT INTO shoppinglist(name, created_by, active) VALUES('Eväät 18. päivä', 1, '2016-09-26 12:00:00');

INSERT INTO shoppinglist_product(product_id, shoppinglist_id, description, quantity, created_by) VALUES(6, 1, 'murot', 1, 1);





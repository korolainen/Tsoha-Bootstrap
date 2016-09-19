

INSERT INTO users(id, account, first_name, last_name, phone, hash) VALUES(1, 'valtteri.korolainen@helsinki.fi', 'Valtteri', 'Korolainen', '0445651000', 'd8df40b23bce40c31cdff73f4fd694b33h589l4ik6ho9689rtx78vdi4r497tnls8ti22sma958yqd9o7hyufa66nk940e1a33fa8b09ef49cdbac3a8c6f908');
INSERT INTO users(id, account, first_name, last_name, phone, hash) VALUES(2, 'familymember', 'Family', 'Member', '', 'd8df40b23bce40c31cdff73f4fd694b33h589l4ik6ho9689rtx78vdi4r497tnls8ti22sma958yqd9o7hyufa66nk940e1a33fa8b09ef49cdbac3a8c6f908');
INSERT INTO users(id, account, first_name, last_name, phone, hash) VALUES(3, 'otheruser', 'Other', 'User', '', 'd8df40b23bce40c31cdff73f4fd694b33h589l4ik6ho9689rtx78vdi4r497tnls8ti22sma958yqd9o7hyufa66nk940e1a33fa8b09ef49cdbac3a8c6f908');

INSERT INTO usergroup(id, name, created_by) VALUES(1, 'Perhe', 1);

INSERT INTO usergroup_users(usergroup_id, users_id) VALUES(1,1);
INSERT INTO usergroup_users(usergroup_id, users_id) VALUES(1,2);


INSERT INTO unit(id, name) VALUES(1, 'kpl');
INSERT INTO unit(id, name) VALUES(2, 'l');
INSERT INTO unit(id, name) VALUES(3, 'kg');
INSERT INTO unit(id, name) VALUES(4, 'g');

INSERT INTO product(id, name, default_unit_id, created_by) VALUES(1, 'maito', 2, 1);
INSERT INTO product(id, name, default_unit_id, created_by) VALUES(2, 'voi', 1, 1);
INSERT INTO product(id, name, default_unit_id, created_by) VALUES(3, 'oltermanni', 3, 1);
INSERT INTO product(id, name, default_unit_id, created_by) VALUES(4, 'jauheliha', 3, 1);
INSERT INTO product(id, name, default_unit_id, created_by) VALUES(5, 'tomaatti', 3, 1);
INSERT INTO product(id, name, default_unit_id, created_by) VALUES(6, 'murot', 3, 1);

INSERT INTO shop(id, name, created_by) VALUES(1, 'S-market', 1);
INSERT INTO shop(id, name, created_by) VALUES(2, 'Lidl', 1);
INSERT INTO shop(id, name, created_by) VALUES(3, 'Alepa', 1);
INSERT INTO shop(id, name, created_by) VALUES(4, 'K-kauppa', 1);
INSERT INTO shop(id, name, created_by) VALUES(5, 'R-kioski', 2);

INSERT INTO shop_usergroup(usergroup_id, shop_id, created_by) VALUES(1, 1, 1);
INSERT INTO shop_usergroup(usergroup_id, shop_id, created_by) VALUES(1, 2, 1);

INSERT INTO shop_product(product_id, shop_id, price, quantity, unit_id, created_by) VALUES(1, 1, 0.75, 1, 2, 1);
INSERT INTO shop_product(product_id, shop_id, price, quantity, unit_id, created_by) VALUES(1, 2, 0.55, 1, 2, 1);
INSERT INTO shop_product(product_id, shop_id, price, quantity, unit_id, created_by) VALUES(2, 1, 2.5, 1, 1, 1);
INSERT INTO shop_product(product_id, shop_id, price, quantity, unit_id, created_by) VALUES(2, 2, 2.6, 1, 1, 1);

INSERT INTO shoppinglist(id, name, created_by, active) VALUES(1, 'Eväät 18. päivä', 1, '2016-09-26 12:00:00');

INSERT INTO shoppinglist_product(product_id, shoppinglist_id, description, unit_id, quantity, created_by) VALUES(6, 1, 'murot', 3, 1, 1);





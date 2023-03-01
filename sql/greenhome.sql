-- DDL

CREATE TABLE companies (
  id int(10) NOT NULL AUTO_INCREMENT, 
  name varchar(255) NOT NULL, 
  street varchar(255) NOT NULL, 
  house_nr varchar(10), 
  zipcode varchar(50) NOT NULL, 
  city varchar(255) NOT NULL, 
  country_code char(2) NOT NULL, 
  email_adress varchar(320) NOT NULL UNIQUE, 
  phone_number varchar(22) NOT NULL 
);

CREATE TABLE employees (
  id int(10) NOT NULL AUTO_INCREMENT, 
  first_name char(255) NOT NULL, 
  last_name char(255) NOT NULL, 
  email_adress varchar(255) NOT NULL UNIQUE, 
  password varchar(255) NOT NULL, 
  company_id int(10) NOT NULL, 
  function_name varchar(10) NOT NULL, 
  is_active tinyint(1) DEFAULT 1 
);

CREATE TABLE functions (
  name varchar(10) NOT NULL
);

CREATE TABLE accessibilities (
  function_name varchar(10) NOT NULL, 
  can_acces_orders tinyint(1) NOT NULL DEFAULT 0, 
  can_acces_relations tinyint(1) NOT NULL DEFAULT 0, 
  can_acces_articles tinyint(1) NOT NULL DEFAULT 0, 
  can_acces_employees tinyint(1) NOT NULL DEFAULT 0 
);

CREATE TABLE orders (
  id int(10) NOT NULL AUTO_INCREMENT, 
  order_date date NOT NULL, 
  shipping_date date NOT NULL, 
  order_type tinyint(1) NOT NULL, 
  employee_id int(10) NOT NULL, 
  relation_id int(10) NOT NULL, 
  company_id int(10) NOT NULL,
  is_finalized tinyint(1) NOT NULL DEFAULT 0
);

CREATE TABLE relations (
  id int(10) NOT NULL AUTO_INCREMENT, 
  name varchar(255) NOT NULL, 
  street varchar(255) NOT NULL, 
  house_nr varchar(10), 
  zip_code varchar(50) NOT NULL, 
  city varchar(255) NOT NULL, 
  country_code char(2) NOT NULL, 
  email_adress varchar(255) NOT NULL, 
  phone_number varchar(22) NOT NULL 
);

CREATE TABLE order_lines (
  order_id int(10) NOT NULL REFERENCES orders (id), 
  order_line int(10) NOT NULL, 
  quantity int(2) NOT NULL, 
  article_id int(10) NOT NULL REFERENCES articles (id)
);


CREATE TABLE articles (
  id int(10) NOT NULL AUTO_INCREMENT, 
  name varchar(255) NOT NULL, 
  description varchar(1500),  
  purchase_price float, 
  selling_price float, 
  is_active tinyint(1) NOT NULL
);

-- DML

INSERT INTO companies VALUES
(1, 'GreenHome', 'Sterappel', '18', '4793KE', 'Breda', 'NL', 'info@greenhome.com', '+31 6 24 95 45 37');

INSERT INTO employees VALUES
(1, 'admin', 'instrator', 'admin@greenhome.com', '$2y$10$g76JjQO1tYVpT0zMhxFB4Ocru1nAC/iiDeT/aU5jZ4uYy6kdfxXMa', 1, 'admin', 1),
(2, 'guest', 'gast', 'guest@greenhome.com', '$2y$10$Vt4IaIKLZUP3x6xNL3sDHObo42gAC8TtfbYi3TTxhZNjEOe3a.8Gy', 1, 'admin', 1);

INSERT INTO functions VALUES ('admin');

INSERT INTO accessibilities VALUES ('admin', 1, 1, 1, 1);


INSERT INTO articles VALUES (1, 'Hoekbank ''Future'' Groen', 'Luxe groene 5-zits hoekbank', 500, 789.98, 1);
INSERT INTO articles VALUES (2, 'Hoekbank ''Future'' Zwart', 'Luxe zwarte 5-zits hoekbank', 500, 789.98, 1);

INSERT INTO orders VALUES  (1, '2023-02-20', '2023-02-21', 1, 1, 1, 1, 1), (2, '2023-01-01', '2023-01-10', 0, 1, 1, 1, 1);

INSERT INTO order_lines VALUES (1, 1, 1, 1), (1, 2, 1, 2);

INSERT INTO order_lines VALUES (2, 1, 5, 1), (2, 2, 10, 2);

INSERT INTO relations VALUES (1, 'Furniture Manufacturer B.V.', 'voorstraat', '16A', '1234AB', 'Rotterdam', 'NL', 'info@furniture.com', '+31 10 12 56 77');


--DDL continued

ALTER TABLE companies ADD PRIMARY KEY (id);

ALTER TABLE employees
ADD FOREIGN KEY (company_id) REFERENCES companies (id),
ADD FOREIGN KEY (function_name) REFERENCES functions (name),
ADD PRIMARY KEY (id);

ALTER TABLE functions 
ADD PRIMARY KEY (name);

ALTER TABLE accessibilities
ADD FOREIGN KEY function_name REFERENCES functions (name) ON DELETE CASCADE,
ADD PRIMARY KEY function_name;

ALTER TABLE orders
ADD PRIMARY KEY (id),
ADD FOREIGN KEY (relation_id) REFERENCES relations (id) ON DELETE RESTRICT,
ADD FOREIGN KEY (employee_id) REFERENCES employees (id) ON DELETE RESTRICT,
ADD FOREIGN KEY (company_id) REFERENCES companies (id) ON DELETE RESTRICT;

ALTER TABLE relations
ADD PRIMARY KEY (id);

ALTER TABLE order_lines
ADD PRIMARY KEY (order_id, order_line),
ADD FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE,
ADD FOREIGN KEY (article_id) REFERENCES articles (id) ON DELETE RESTRICT;

ALTER TABLE articles
ADD PRIMARY KEY (id);

DELIMITER $$
CREATE TRIGGER add_accessibility_record
AFTER INSERT ON functions
FOR EACH ROW
BEGIN
  INSERT INTO accessibilities (function_name, can_acces_orders, can_acces_relations, can_acces_articles, can_acces_employees)
  VALUES (NEW.name, 0, 0, 0, 0);
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER check_employee_active
BEFORE INSERT ON orders
FOR EACH ROW
BEGIN
  DECLARE employee_active INT;
  SELECT is_active INTO employee_active FROM employees WHERE id = NEW.employee_id;
  IF employee_active <> 1 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Cannot insert order for inactive employee';
  END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER check_article_active
BEFORE INSERT ON order_lines
FOR EACH ROW
BEGIN
  DECLARE article_active INT;
  SELECT is_active INTO article_active FROM articles WHERE id = NEW.article_id;
  IF article_active <> 1 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Cannot insert order line for inactive article';
  END IF;
END $$
DELIMITER ;


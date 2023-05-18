
CREATE TABLE companies (
  id int(10) NOT NULL, 
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
  id int(10) NOT NULL, 
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
  id int(10) NOT NULL, 
  order_date date NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  shipping_date date NOT NULL, 
  order_type tinyint(1) NOT NULL, 
  employee_id int(10) NOT NULL DEFAULT 1, 
  relation_id int(10) NOT NULL, 
  company_id int(10) NOT NULL,
  is_finalized tinyint(1) NOT NULL DEFAULT 0
);

CREATE TABLE relations (
  id int(10) NOT NULL, 
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
  purchase_price decimal(10,2),
  selling_price decimal(10,2),
  is_active tinyint(1) NOT NULL DEFAULT 1,
  image_data MEDIUMBLOB,
  image_mime varchar(50),
);

CREATE TABLE searches_not_found (
  search_input varchar(255),
  times_searched int(11)
);






INSERT INTO `accessibilities` (`function_name`, `can_acces_orders`, `can_acces_relations`, `can_acces_articles`, `can_acces_employees`) VALUES
('admin', 1, 1, 1, 1),
('sales', 0, 0, 0, 0);


INSERT INTO `articles` (`id`, `name`, `description`, `purchase_price`, `selling_price`, `is_active`) VALUES
(1, 'Hoekbank \'Future\' Groen', 'Luxe groene 5-zits hoekbank', '500.00', '790.00', 1),
(2, 'Hoekbank \'Future\' Zwart', 'Luxe zwarte 5-zits hoekbank', '500.00', '790.00', 1),
(11, 'Test item', 'test omschrijving', '795.05', '999.95', 1),
(12, 'Treeflex', '7-400 - Roofing and Siding Panels', '865.23', '922.86', 1),
(13, 'Stronghold', '2-830 - Retaining Walls', '508.93', '261.19', 1),
(14, 'Hatity', '13-550 - Transportation Control Instrumentation', '552.31', '393.18', 1),
(15, 'Tres-Zap', '15-600 - Refrigeration Equipment', '522.13', '133.82', 1),
(16, 'Greenlam', '3-000 - General', '878.09', '220.76', 1),
(17, 'Alpha', '2-770 - Curb and Gutters', '628.57', '851.58', 1),
(18, 'Bytecard', '9 - Finishes', '667.50', '134.27', 1),
(19, 'Duobam', '2-924 - Sodding', '79.56', '936.87', 1),
(20, 'Y-Solowarm', '8-900 - Glazed Curtain Wall', '234.99', '419.39', 1);


INSERT INTO `companies` (`id`, `name`, `street`, `house_nr`, `zipcode`, `city`, `country_code`, `email_adress`, `phone_number`) VALUES
(1, 'GreenHome', 'Sterappel', '18', '4793KE', 'Breda', 'NL', 'info@greenhome.com', '+31 6 24 95 45 37');


INSERT INTO `employees` (`id`, `first_name`, `last_name`, `email_adress`, `password`, `company_id`, `function_name`, `is_active`) VALUES
(1, 'Admin', 'Instrator', 'admin@greenhome.com', '$2y$10$g76JjQO1tYVpT0zMhxFB4Ocru1nAC/iiDeT/aU5jZ4uYy6kdfxXMa', 1, 'admin', 1);

INSERT INTO `functions` (`name`) VALUES
('admin'),
('sales');

INSERT INTO `orders` (`id`, `order_date`, `shipping_date`, `order_type`, `employee_id`, `relation_id`, `company_id`, `is_finalized`) VALUES
(1, '2023-02-20', '2023-02-21', 1, 1, 1, 1, 1),
(2, '2023-01-01', '2023-01-10', 0, 1, 1, 1, 1);


INSERT INTO `order_lines` (`order_id`, `order_line`, `quantity`, `article_id`) VALUES
(1, 1, 1, 1),
(1, 2, 1, 2);


INSERT INTO `relations` (`id`, `name`, `street`, `house_nr`, `zip_code`, `city`, `country_code`, `email_adress`, `phone_number`) VALUES
(1, 'Furniture Manufacturer B.V.', 'voorstraat', '16A', '1234AB', 'Rotterdam', 'NL', 'info@furniture.com', '+31 10 12 56 77'),
(9, 'Sit Amet Industries', 'P.O. Box 775, 5363 Proin St.', '67', '3487 EV', 'Muiden', '', 'faucibus.morbi@yahoo.ca', '(0450) 56954769'),
(10, 'In Consectetuer LLP', 'Ap #532-8593 Sagittis Road', '46', '3831 GE', 'Sneek', '', 'magna.a@icloud.com', '(038656) 872926');

ALTER TABLE companies MODIFY COLUMN id INT AUTO_INCREMENT PRIMARY KEY;

ALTER TABLE functions ADD PRIMARY KEY (name);

ALTER TABLE relations MODIFY COLUMN id INT AUTO_INCREMENT PRIMARY KEY;

ALTER TABLE articles MODIFY COLUMN id INT AUTO_INCREMENT PRIMARY KEY;

ALTER TABLE employees
MODIFY COLUMN id INT AUTO_INCREMENT PRIMARY KEY,
ADD FOREIGN KEY (company_id) REFERENCES companies (id),
ADD FOREIGN KEY (function_name) REFERENCES functions (name);

ALTER TABLE accessibilities
ADD PRIMARY KEY (function_name),
ADD FOREIGN KEY (function_name) REFERENCES functions (name) ON DELETE CASCADE;


ALTER TABLE orders
MODIFY COLUMN id INT AUTO_INCREMENT PRIMARY KEY,
ADD FOREIGN KEY (relation_id) REFERENCES relations (id) ON DELETE RESTRICT,
ADD FOREIGN KEY (employee_id) REFERENCES employees (id) ON DELETE RESTRICT,
ADD FOREIGN KEY (company_id) REFERENCES companies (id) ON DELETE RESTRICT;

ALTER TABLE order_lines
ADD PRIMARY KEY (order_id, order_line),
ADD FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE,
ADD FOREIGN KEY (article_id) REFERENCES articles (id) ON DELETE RESTRICT;

ALTER TABLE searches_not_found ADD PRIMARY KEY (search_input);

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

DELIMITER $$
CREATE TRIGGER restrict_deleting_orders
BEFORE DELETE ON orders
FOR EACH ROW
BEGIN
    IF OLD.is_finalized = 1 THEN
        SIGNAL SQLSTATE '45000' 
            SET MESSAGE_TEXT = 'Cannot delete order that has been finalized';
    END IF;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER restrict_deleting_order_lines
BEFORE DELETE ON order_lines
FOR EACH ROW
BEGIN
    DECLARE is_finalized INT;
    SELECT is_finalized INTO is_finalized FROM orders WHERE id = OLD.order_id;
    IF is_finalized = 1 THEN
        SIGNAL SQLSTATE '45000' 
            SET MESSAGE_TEXT = 'Cannot delete order line when order is finalized';
    END IF;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER auto_increment_order_line
BEFORE INSERT ON order_lines
FOR EACH ROW
BEGIN
    SET NEW.order_line = (
        SELECT COALESCE(MAX(order_line), 0) + 1
        FROM order_lines
        WHERE order_id = NEW.order_id
    );
END$$
DELIMITER ;




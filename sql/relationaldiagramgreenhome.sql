CREATE TABLE companies (
  id int(10) NOT NULL AUTO_INCREMENT, 
  name varchar(255) NOT NULL, 
  street varchar(255) NOT NULL, 
  house_nr varchar(10), 
  zipcode varchar(50) NOT NULL, 
  city varchar(255) NOT NULL, 
  country_code char(2) NOT NULL, 
  email_adress varchar(320) NOT NULL UNIQUE, 
  phone_number varchar(22) NOT NULL, 
  PRIMARY KEY (id)
);

INSERT INTO companies VALUES
(1, 'GreenHome', 'Sterappel', '18', '4793KE', 'Breda', 'NL', 'info@greenhome.com', '+31 6 24 95 45 37')

CREATE TABLE employees (
  id int(10) NOT NULL AUTO_INCREMENT, 
  first_name char(255) NOT NULL, 
  last_name char(255) NOT NULL, 
  email_adress varchar(255) NOT NULL UNIQUE, 
  password varchar(255) NOT NULL, 
  company_id int(10) NOT NULL REFERENCES companies (id), 
  function_name varchar(10) NOT NULL REFERENCES functions (name), 
  PRIMARY KEY (id)
);

INSERT INTO employees (`id`, `first_name`, `last_name`, `email_adress`, `password`, `company_id`, `function_name`) VALUES
(1, 'admin', 'instrator', 'admin@greenhome.com', '$2y$10$g76JjQO1tYVpT0zMhxFB4Ocru1nAC/iiDeT/aU5jZ4uYy6kdfxXMa', 1, 'admin'),
(2, 'guest', 'gast', 'guest@greenhome.com', '$2y$10$Vt4IaIKLZUP3x6xNL3sDHObo42gAC8TtfbYi3TTxhZNjEOe3a.8Gy', 1, 'admin');


CREATE TABLE functions (
  name varchar(10) NOT NULL,
  PRIMARY KEY (name)
);

INSERT INTO functions VALUES ('admin');


CREATE TABLE accessibilities (
  function_name varchar(10) NOT NULL REFERENCES functions (name), 
  can_acces_orders int(1) NOT NULL, 
  can_acces_relations int(1) NOT NULL, 
  can_acces_articles int(1) NOT NULL, 
  can_acces_employees int(1) NOT NULL, 
  PRIMARY KEY (function_name)
);

INSERT INTO accessibilities VALUES ('admin', 1, 1, 1, 1);

CREATE TABLE orders (
  id int(10) NOT NULL AUTO_INCREMENT, 
  order_date date NOT NULL, 
  shipping_date date NOT NULL, 
  order_type tinyint(1) NOT NULL, 
  employee_id int(10) NOT NULL REFERENCES employees (id), 
  relation_id int(10) NOT NULL REFERENCES relations (id), 
  company_id int(10) NOT NULL REFERENCES companies (id),
  PRIMARY KEY (id)
  -- is_finalized int(1) NOT NULL
);

INSERT INTO orders VALUES  (1, '2023-02-20', '2023-02-21', 1, 1, 1, 1), (2, '2023-01-01', '2023-01-10', 0, 1, 1, 1);


CREATE TABLE relations (
  id int(10) NOT NULL AUTO_INCREMENT, 
  name varchar(255) NOT NULL, 
  street varchar(255) NOT NULL, 
  house_nr varchar(10), 
  zip_code varchar(50) NOT NULL, 
  city varchar(255) NOT NULL, 
  country_code char(2) NOT NULL, 
  email_adress varchar(255) NOT NULL, 
  phone_number varchar(22) NOT NULL, 
  PRIMARY KEY (id)
);

INSERT INTO relations VALUES (1, 'Furniture Manufacturer B.V.', 'voorstraat', '16A', '1234AB', 'Rotterdam', 'NL', 'info@furniture.com', '+31 10 12 56 77');

CREATE TABLE order_lines (
  order_id int(10) NOT NULL REFERENCES orders (id), 
  order_line int(10) NOT NULL, 
  quantity int(2) NOT NULL, 
  article_id int(10) NOT NULL REFERENCES articles (id), 
  PRIMARY KEY (order_id, order_line)
  -- order_type tinyint(1) NOT NULL 
);

-- uitslag orders
INSERT INTO order_lines VALUES (1, 1, 1, 1), (1, 2, 1, 2);

-- inslag orders
INSERT INTO order_lines VALUES (2, 1, 5, 1), (2, 2, 10, 2);

CREATE TABLE articles (
  id int(10) NOT NULL AUTO_INCREMENT, 
  name varchar(255) NOT NULL, 
  description varchar(1500), 
  stock_level int(4) NOT NULL DEFAULT 0, 
  purchase_price float, 
  selling_price float, 
  is_active tinyint(1) NOT NULL, 
  PRIMARY KEY (id)
);

INSERT INTO articles VALUES (1, 'Hoekbank ''Future'' Groen', 'Luxe groene 5-zits hoekbank', 2, 500, 789.98, 1);
INSERT INTO articles VALUES (2, 'Hoekbank ''Future'' Zwart', 'Luxe zwarte 5-zits hoekbank', 2, 500, 789.98, 1);


-- query to show current orders
SELECT orders.id AS `Order id`, orders.order_date AS `Order date`, orders.shipping_date AS `Shipping date`, relations.name AS `Customer`, COUNT(order_lines.order_id) AS `Order lines`, CONCAT(employees.first_name, ' ', employees.last_name) AS `Handled by`, IF(orders.order_type = 1, 'Uitslag', 'Inslag') AS 'Order type'
FROM `orders` 
JOIN relations
	ON relations.id = orders.relation_id
JOIN order_lines
	ON order_lines.order_id = orders.id
 JOIN employees
 	ON employees.id = orders.Employee_id
GROUP BY orders.id;
    
-- query to show current stock
WITH total_incoming AS (
    SELECT articles.id AS article_id, SUM(order_lines.quantity) AS incoming_stock
    FROM order_lines
    JOIN orders
        ON order_lines.order_id = orders.id
    JOIN articles
        ON articles.id = order_lines.article_id
    WHERE orders.order_type = 0 
    GROUP BY articles.id
), total_outgoing AS (
    SELECT articles.id AS article_id, SUM(order_lines.quantity) AS outgoing_stock
    FROM order_lines
    JOIN orders
        ON order_lines.order_id = orders.id
    JOIN articles
        ON articles.id = order_lines.article_id
    WHERE orders.order_type = 1 
    GROUP BY articles.id
)

SELECT articles.id AS 'Article ID', articles.name AS 'Article name', (SUM(total_incoming.incoming_stock) - SUM(total_outgoing.outgoing_stock)) AS 'Stock level'
FROM total_incoming
JOIN articles   
    ON articles.id = total_incoming.article_id
JOIN total_outgoing
	ON articles.id = total_outgoing.article_id
 GROUP BY articles.id;

-- query to show outgoing order lines with article name
-- query to show incoming order lines with article name



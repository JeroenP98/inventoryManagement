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

SELECT articles.id AS 'Article ID', articles.name AS 'Article name', 
       COALESCE(SUM(total_incoming.incoming_stock), 0) - COALESCE(SUM(total_outgoing.outgoing_stock), 0) AS 'Stock level'
FROM articles
LEFT JOIN total_incoming
    ON articles.id = total_incoming.article_id
LEFT JOIN total_outgoing
    ON articles.id = total_outgoing.article_id
GROUP BY articles.id, articles.name;

-- query to show outgoing order lines with article name
SELECT order_lines.order_id AS 'Order id', order_lines.order_line AS 'Order line', articles.name, order_lines.quantity, IF(orders.order_type = 1, 'Uitslag', 'Inslag') AS 'Order type'
FROM order_lines
JOIN articles
	ON order_lines.article_id = articles.id
JOIN orders
	ON order_lines.order_id = orders.id
WHERE orders.order_type = 1;

-- query to show incoming order lines with article name
SELECT order_lines.order_id AS 'Order id', order_lines.order_line AS 'Order line', articles.name, order_lines.quantity, IF(orders.order_type = 1, 'Uitslag', 'Inslag') AS 'Order type'
FROM order_lines
JOIN articles
	ON order_lines.article_id = articles.id
JOIN orders
	ON order_lines.order_id = orders.id
WHERE orders.order_type = 0;

-- show all articles with status
SELECT id AS 'Article ID', name AS 'Name', CONCAT(LEFT(description, 25),'...') AS 'Description', IF(is_active = 1, 'Active', 'Inactive') AS 'Active status'
FROM articles;

-- show all deactivated articles
SELECT id, name, IF(is_active = 1, 'Active', 'Inactive') AS 'active status'
FROM articles
WHERE is_active = 0;

-- show all employees
SELECT employees.id AS 'Employee ID', employees.first_name AS 'First name', employees.last_name AS 'Last name', employees.email_adress AS 'Email address', employees.function_name AS 'Function', companies.name AS 'Active company'
FROM employees
JOIN companies 
	ON employees.company_id  = companies.id;

-- show all employees with amount of orders made per month of the current year
SELECT COUNT(orders.id) AS 'Total orders', orders.employee_id AS 'Employee ID', CONCAT(employees.first_name, ' ', employees.last_name) AS 'Name', MONTHNAME(orders.order_date) AS 'Month' 
FROM orders
JOIN employees
	ON employees.id = orders.employee_id
WHERE YEAR(order_date) = YEAR(CURRENT_DATE()) AND orders.order_type = 1
GROUP BY 2, 4
ORDER BY `Month` ASC;

-- show employees who made the most turnover per month. Only finalized orders are elligable 
SELECT ROUND((order_lines.quantity * articles.selling_price),2) AS 'Total Sales', orders.employee_id AS 'Employee ID', CONCAT(employees.first_name, ' ', employees.last_name) AS 'Name', MONTHNAME(orders.order_date) AS 'Month'
FROM order_lines
JOIN articles
	ON order_lines.article_id = articles.id
JOIN orders
	ON order_lines.order_id = orders.id
JOIN employees
	ON orders.employee_id = employees.id
WHERE orders.order_type = 1 AND orders.is_finalized = 1
GROUP BY 2, 4
ORDER BY `Month` ASC;

-- show customers who generated most turnover all time. Only finalized orders are elligable 
SELECT orders.relation_id AS 'Relation ID', relations.name AS 'Buyer name', SUM(ROUND((order_lines.quantity * articles.selling_price),2)) AS 'Total turnover'
FROM orders
JOIN relations 
	ON orders.relation_id = relations.id
JOIN order_lines
	ON orders.id = order_lines.order_id
JOIN articles
	ON order_lines.article_id = articles.id
WHERE orders.is_finalized = 1
GROUP BY 1
ORDER BY `Total turnover` DESC;

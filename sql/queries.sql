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

-- show all deactivated articles
SELECT id, name, IF(is_active = 1, 'Active', 'Inactive') AS 'active status'
FROM articles
WHERE is_active = 0

-- show all employees

-- show all employees with amount of orders made per month

-- show employees who made the most turnover per month

-- show customers who generated most turnover all time

-- show outstanding balance of ougoing orders which have not been finalized
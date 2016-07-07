SELECT
  s_orders.`id`,
  `name` AS `fullName`,
  `address`,
  `email`,
  `price`,
  `date`,
  `phone`,
  SUM(amount) AS `quantity`
FROM s_orders
LEFT JOIN s_purchases ON s_orders.id = s_purchases.order_id
GROUP BY s_orders.`id`;

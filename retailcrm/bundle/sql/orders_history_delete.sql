DELETE s_purchases, s_orders
FROM s_orders
  INNER JOIN s_purchases ON s_purchases.order_id = s_orders.id
WHERE s_orders.id = :id;
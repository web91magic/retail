SELECT
  s_orders.id                AS `externalId`,
  s_orders.name              AS `fullName`,
  s_orders.address           AS `address`,
  s_orders.phone             AS `phone`,
  s_orders.email             AS `email`,
  s_orders.date              AS `createdAt`,
  s_orders.total_price       AS `total`,
  s_orders.comment           AS `customerComment`,
  s_orders.payment_method_id AS `paymentType`,
  s_orders.status            AS `paymentStatus`,
  s_orders.payment_date      AS `createdAt`,
  (SELECT GROUP_CONCAT(
      CONCAT_WS(';', s_purchases.product_id,
                s_purchases.product_name, s_purchases.amount, s_purchases.price) SEPARATOR '|'
  ))                         AS items,
  (CASE
   s_orders.delivery_id
   WHEN
     0
     THEN
       ''
   WHEN
     1
     THEN
       'courier'
   WHEN
     2
     THEN
       'self-delivery'
   WHEN
     3
     THEN
       'russian-post'
   WHEN
     4
     THEN
       'ems'
   END
  )                          AS `code`
FROM s_orders
  LEFT JOIN s_purchases ON s_purchases.order_id = s_orders.id
  LEFT JOIN s_payment_methods ON s_orders.payment_method_id = s_payment_methods.id
WHERE s_orders.date BETWEEN :lastSync AND NOW()
GROUP BY s_orders.id
ORDER BY externalId;

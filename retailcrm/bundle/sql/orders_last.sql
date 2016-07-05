SELECT
  name AS 'Ф.И.О.', s_purchases.product_name AS 'Товар', s_purchases.price AS 'Цена товара',
  s_purchases.amount AS 'Количество', address AS 'Адрес', phone AS 'Телефон', email AS 'Электронная почта',
  total_price AS 'Сумма'
FROM s_orders
  LEFT JOIN s_purchases ON s_purchases.order_id = s_orders.id
ORDER BY name
LIMIT 1;
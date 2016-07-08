UPDATE s_purchases
  # данные будут обновляться в двух таблицах одновременно
  JOIN s_orders ON s_purchases.order_id = s_orders.id
# устанавливаем общую сумму заказа
SET s_orders.total_price   = IF(:summ IS NOT NULL, :summ, 0),
  # устанавливаем сколько уже заплачено
  s_orders.paid            = IF(:purchaseSumm IS NOT NULL, :purchaseSumm , 0),
  s_purchases.price        = IF(:initialPrice IS NOT NULL, :initialPrice, 0),
  # устанавлииваем скидку
  s_orders.discount        = IF(:discount IS NOT NULL, :discount, 0),
  s_purchases.amount       = IF(:quantity IS NOT NULL, :quantity, 0),
  s_purchases.product_name = IF(:name IS NOT NULL, :name, '')
WHERE id = :externalId;
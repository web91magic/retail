INSERT IGNORE INTO s_purchases (
  `order_id`,
  `product_name`,
  `price`,
  `amount`
) VALUES (
  :externalId,
  IF(:product_name IS NOT NULL, :product_name, ''),
  IF(:price IS NOT NULL, :price, ''),
  IF(:amount IS NOT NULL, :amount, '')
)
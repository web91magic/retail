UPDATE `s_orders`
SET name            = IF(:name IS NOT NULL, :name, ''),
  address           = IF(:address IS NOT NULL, :address, ''),
  phone             = IF(:phone IS NOT NULL, :phone, ''),
  email             = IF(:email IS NOT NULL, :email, ''),
  comment           = IF(:comment IS NOT NULL, :comment, ''),
  delivery_price    = IF(:delivery_price IS NOT NULL, :delivery_price, ''),
  payment_method_id = IF(:payment_method_id IS NOT NULL, :payment_method_id, ''),
  paid              = IF(:paid IS NOT NULL, :paid, '')
WHERE id = :id;
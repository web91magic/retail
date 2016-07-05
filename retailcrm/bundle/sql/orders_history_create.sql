INSERT IGNORE INTO `s_orders` (`name`,
                               `email`,
                               `phone`,
                               `comment`,
                               `address`,
                               `date`,
                               `delivery_price`,
                               `payment_method_id`,
                               `paid`
)
VALUES (IF(:name IS NOT NULL, :name, ''),
        IF(:email IS NOT NULL, :email, ''),
        IF(:phone IS NOT NULL, :phone, ''),
        IF(:comment IS NOT NULL, :comment, ''),
        IF(:address IS NOT NULL, :address, ''),
        IF(:date IS NOT NULL, :date, NOW()),
        IF(:delivery_price IS NOT NULL, :delivery_price, ''),
        IF(:payment_method_id IS NOT NULL, :payment_method_id, ''),
        IF(:paid IS NOT NULL, :paid, '')
);
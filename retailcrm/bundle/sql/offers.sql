SELECT
  s_variants.id AS `id`,
  s_categories.id AS `categoryId`,
  s_categories.name AS `category`,
  s_products.name AS `productName`,
  s_products.name AS `name`,
  s_variants.name AS `variantName`,
  s_variants.stock AS `quantity`,
  s_variants.price AS `price`,
  s_products.created AS `createdAt`,
  (CASE s_products.visible
   WHEN 0
     THEN 'N'
   ELSE 'Y' END) AS `productActivity`,
  s_images.filename AS `picture`,
  s_products.annotation AS `article`
FROM s_products
  LEFT JOIN s_images ON s_images.product_id = s_products.id
  LEFT JOIN s_brands ON s_products.brand_id = s_brands.id
  LEFT JOIN s_variants ON s_products.id = s_variants.product_id
  LEFT JOIN s_products_categories ON s_products_categories.product_id = s_products.id
  LEFT JOIN s_categories ON s_products_categories.category_id = s_categories.id
  GROUP BY id
ORDER BY categoryId;

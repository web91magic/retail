SELECT
  `right`.id                                AS `id`,
  `right`.parent_id                         AS `parent_id`,
  `left`.name                               AS `parentName`,
  `right`.name                              AS `categoryName`,
  CONCAT_WS('/', `left`.name, `right`.name) AS url
FROM s_categories `right`
  LEFT JOIN s_categories `left` ON `right`.parent_id = `left`.`id`;

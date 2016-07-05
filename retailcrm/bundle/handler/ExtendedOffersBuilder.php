<?php

/**
 * Created by PhpStorm.
 * User: Jay7Seven
 * Date: 04.07.2016
 * Time: 0:27
 */
class ExtendedOffersBuilder extends Builder
{
    public function buildCategories()
    {
        $query = $this->rule->getSQL('categories');
        $handler = $this->rule->getHandler('CategoriesHandler');
        $this->sql = $this->container->db->prepare($query);

        return $this->build($handler);
    }
}
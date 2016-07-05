<?php

/**
 * Created by PhpStorm.
 * User: Jay7Seven
 * Date: 03.07.2016
 * Time: 20:33
 */
class CategoriesHandler implements HandlerInterface
{
    public function prepare($data)
    {
        $categories = [];
        foreach($data as $category) {
            $categories[] = [
                'id' => $category['id'],
                'parentId' => $category['parent_id'],
                'url' => $category['url'],
                'name' => $category['categoryName']
            ];
        }

        return $categories;
    }


}
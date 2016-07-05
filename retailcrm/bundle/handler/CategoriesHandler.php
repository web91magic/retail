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
//        $categories = [];

//        var_dump($data);
//
//        foreach($data as $category) {
//            $categories[] = [
//                'id' => $category['id'],
//                'parentId' => $category['parent_id'],
//                'name' => $category['categoryName']
//            ];
//        }


        $categories = ['Мобильные телефоны',
            'Бытовая техника',
            'Пылесосы',
            'Миксеры',
            'Фотоаппараты'
        ];


        return $categories;
    }


}
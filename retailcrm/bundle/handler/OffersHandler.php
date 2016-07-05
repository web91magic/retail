<?php

/**
 * Created by PhpStorm.
 * User: Jay7Seven
 * Date: 03.07.2016
 * Time: 20:34
 */
class OffersHandler implements HandlerInterface
{

    public function prepare($offers)
    {
//        $categories = $this->getCategories();
//        var_dump($offers);

        // позволит нам выбрать корневую директорию, через которую сможем ссылаться на изображения товаров
        $container = Container::getInstance();
        $container->shopUrl;

        foreach ($offers as $k => $v) {
//            $picture = 'upload/shop_1/' .
//                substr($v['id'], 0, 1) . '/' .
//                substr($v['id'], 1, 1) . '/' .
//                substr($v['id'], 2, 1) . '/' .
//                'item_'. $v['id'] .'/' .
//                $v['picture'];
//            $offers[$k]['picture'] = $container->shopUrl . '/' . $picture;
//
//            $categoryId = $v['categoryId'];
//            $offers[$k]['url'] = $container->shopUrl . '/shop/' . $categories[$categoryId]['path'] .'/'. $v['url'];
//            $offers[$k]['categoryId'] = array($categoryId);
            $offers[$k] = array_filter($offers[$k]);
        }

        return ['name' => 'SimplaShop', 'categories' => [
//            Мобильные телефоны
        ]];
    }


    // данный метод предназначен для выбора категорий, которые будут выпадать из списка
    private function getCategories()
    {
        // строим каркас в который будут распределены товары
        $builder = new ExtendedOffersBuilder();
        $data = $builder->buildCategories();



        $categories = [];


        return $categories;
    }
}
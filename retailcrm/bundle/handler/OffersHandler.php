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
        // позволит нам выбрать корневую директорию, через которую сможем ссылаться на изображения товаров
        $container = Container::getInstance();
        $categories = $this->getCategories();

        foreach ($offers AS $key => $value) {
            // построим для каждого наименования url
            $offers[$key]['url'] = $container->shopUrl . $categories[$offers[$key]['categoryId'] - 1]['url'] .
                '/' . $offers[$key]['name'];

            // построим адрес для каждой картинки относительно магазина simpla
            $offers[$key]['picture'] = $container->shopUrl . 'files/products/' . $offers[$key]['picture'];
        }

        return $offers;
    }

    // данный метод предназначен для выбора категорий, которые будут выпадать из списка
    private function getCategories()
    {
        // строим каркас в который будут распределены товары
        $builder = new ExtendedOffersBuilder();
        $data = $builder->buildCategories();

        return $data;
    }
}
<?php

/**
 * Created by PhpStorm.
 * User: Jay7Seven
 * Date: 19.06.2016
 * Time: 13:16
 */
class OrdersHandler implements HandlerInterface
{

    public function prepare($data)
    {
        // создаем новаый массив, который позволяет хранить все заказы
        $orders = [];

        // переменная data хранит в себе все данные, сделанные при помощи
        // выборки из базы данных симплы
        foreach ($data as $record) {
            $nameSeparation = explode(' ', $record['fullName']);
            $order['number'] = $record['externalId'];
            $order['externalId'] = $record['externalId'];
//            echo 'ID' . $record['id'];

            // проверяяем есть ли имя
            $order['firstName'] = array_key_exists(0, $nameSeparation) ? $nameSeparation[0] : '';
            // проверяяем есть ли фамилия
            $order['lastName'] = array_key_exists(1, $nameSeparation) ? $nameSeparation[1] : '';
            // проверяяем есть ли отчество
            $order['patronymic'] = array_key_exists(2, $nameSeparation) ? $nameSeparation[2] : '';
            $order['email'] = $record['email'];
            $order['phone'] = $record['phone'];
//            $order['createdAt'] = $record['createdAt'];

            $order['quantity'] = $record['quantity'];

            // если у покупателя есть несколько заказов, то для удобства их объединяют в одну строку, где каждый
            // отдельный заказ разделен определенным нами разделителем
            $items = explode('|', $record['items']);

            // для того чтобы не генерировалось предупреждения, когда используем функцию array_push, создадим массив
            $order['items'] = [];

            foreach ($items AS $item) {
                $data = explode(';', $item);
                $item = [];
                $item['externalId'] = $data[1];
                $item['productName'] = (isset($data[2])) ? $data[2] : 'no-name';
                $item['quantity'] = (isset($data[3])) ? (int)$data[3] : 0;
                $item['initialPrice'] = (isset($data[4]) && $data[4] != '') ? $data[4] : 0;

                array_push($order['items'], $item);
            }

            // В симпле все данные адреса вводятся в одну строку, что невозможно распарсить
            // город отдельно от улицы, а квартиру от дома, но, к счастью, retailCRM все делает за нас,
            // передавая полный адрес одной строкой crm ее коррекно парсит
            $order['delivery']['address']['text'] = $record['address'];
            // в симпле не выбрана - 0, курьерская доставка - 1, самовывоз - 2
            $order['delivery']['code'] = $record['code'];

            $order['paymentType'] = $record['paymentType'];
            $order['paymentStatus'] = $record['paymentStatus'];
            // К сожалению, симпла не позволяет вводить индекс с формы, поэтому почтовый индекс отображаться не будет

            // Вывод комментария заказа
            $order['customerComment'] = $record['customerComment'];

            $order = DataHelper::filterRecursive($order);

            array_push($orders, $order);
        }


        return $orders;
    }
}
<?php

/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 06.07.2016
 * Time: 14:55
 */
class CustomersHandler implements HandlerInterface
{

    public function prepare($data)
    {
        // создадим массив, который будет содержать клиентов
        $customers = [];

        foreach ($data as $record) {
            $customer['externalId'] = $record['id'];

            // так как в симпле фио вводится в одной строке, то будем парсить таким образом
            $fullName = explode(' ', $record['fullName']);
            $firstName = !empty($fullName[1]) ? $fullName[1] : '';
            $lastName = !empty($fullName[0]) ? $fullName[0] : '';
            $patronymic = !empty($fullName[2]) ? $fullName[2] : '';

            $customer['firstName'] = $firstName;
            $customer['lastName'] = $lastName;
            $customer['patronymic'] = $patronymic;

            $customer['email'] = $record['email'];

            // т к симпла позволяет вводить адрес только в одной строке, то мы используем атрибут text
            $customer['address']['text'] = $record['address'];

            $customer['phones'] = [];
            $phone = [];
            $phone['number'] = $record['phone'];


            // запихиваем наш номер в общий справочник
            array_push($customer['phones'], $phone);
            array_push($customers, $customer);
        }

        return $customers;
    }
}
<?php

/**
 * Created by PhpStorm.
 * User: Jay7Seven
 * Date: 28.06.2016
 * Time: 23:15
 */
class OrdersHistoryHandler implements HandlerInterface
{
    public function prepare($data)
    {
//        класс - обертка для хранения конфигурации(загружается settings . ini, который инициализирует поля класса контайнер)
        $container = Container::getInstance();

        $logger = new Logger();
        
        // класс-обработчик для работы с хендлерами и запросами, которые хранянтся в папке sql
        $rule = new Rule();

        $update = $rule->getSQL('orders_history_update');
        $create = $rule->getSQL('orders_history_create');


        foreach ($data AS $record) {
            if (!empty($record['externalId'])) {
                $sql = $container->db->prepare($update);
                $sql->bindParam(':externalId', $record['externalId']);
            } else {
                $sql = $container->db->prepare($create);
                if (!empty($record['createdAt'])) {
                    $sql->bindParam(':date', $record['createdAt']);
                } else {
                    $sql->bindParam(':date', $var = NULL);
                }
            }

            if (!empty($record['firstName']) || !empty($record['lastName']) || !empty($record['patronymic'])) {
                $fullname = implode(' ', [$record['firstName'], $record['lastName'], $record['patronymic']]);
                $sql->bindParam(':name', $fullname);
                echo 'test';
            } else {
                $sql->bindParam(':name', $fullname = NULL);
            }

            if (!empty($record['email'])) {
                $sql->bindParam(':email', $record['email']);
            } else {
                $sql->bindParam(':email', $var = NULL);
            }

            if (!empty($record['phone'])) {
                $sql->bindParam(':phone', $record['phone']);
            } else {
                $sql->bindParam(':phone', $var = NULL);
            }

            if (!empty($record['customerComment'])) {
                $sql->bindParam(':comment', $record['customerComment']);
            } else {
                $sql->bindParam(':comment', $var = NULL);
            }

            if (!empty($record['delivery'])) {
                $sql->bindParam(':delivery_price', $record['delivery']['cost']);
            } else {
                $sql->bindParam(':delivery_price', $var = NULL);
            }

            if (!empty($record['paymentType'])) {
                $sql->bindParam(':payment_method_id', $record['paymentType']);
            } else {
                $sql->bindParam(':payment_method_id', $var = NULL);
            }

            if (!empty($record['paymentStatus']) && $record['paymentStatus'] == 'paid') {
                $sql->bindParam(':paid', $status = 1);
            } else {
                $sql->bindParam(':paid', $status = 0);
            }

            if (!empty($record['delivery']['address']['text'])) {
                $sql->bindParam(':address', $record['delivery']['address']['text']);
            } else {
                $sql->bindParam(':address', $var = NULL);
            }

        }

        try {
            $sql->execute();
            // получаем последний id из базы данных для того чтобы связать нового пользователя с его покупками
            $lastId = $container->db->lastInsertId();
        } catch (PDOException $e) {
            $logger->write(
                'PDO: ' . $e->getMessage(),
                $container->errorLog
            );
            return false;
        }

        if (!empty($record['items']) && empty($record['externalId'])) {
            foreach ($record['items'] as $item) {
                $items = $rule->getSQL('orders_history_create_items');
                $query = $container->db->prepare($items);
                $query->bindParam(':externalId', $lastId);
                $query->bindParam(':product_name', $item['offer']['name']);
                $query->bindParam(':price', $item['initialPrice']);
                $query->bindParam(':amount', $item['quantity']);

                try {
                    $query->execute();
                } catch (PDOException $e) {
                    $logger->write(
                        'PDO: ' . $e->getMessage(),
                        $container->errorLog
                    );
                    return false;
                }
            }
        }
    }
}
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
        var_dump($data);
//        класс - обертка для хранения конфигурации(загружается settings . ini, который инициализирует поля класса контайнер)
        $container = Container::getInstance();
        $logger = new Logger();
        // класс-обработчик для работы с хендлерами и запросами, которые хранянтся в папке sql
        $rule = new Rule();
        $update = $rule->getSQL('orders_history_update');
        $create = $rule->getSQL('orders_history_create');
        $delete = $rule->getSQL('orders_history_delete');
        foreach ($data AS $record) {
            // в связи с замечаниями, что у нас не удаляются и не обновляются заказы. Будем использовать атрибуты
            // deleted - удалено, created - создано в crm, а в противном случае мы просто обновляемся
            if (!empty($record['deleted']) && $record['deleted'] == true) {
                $sql = $container->db->prepare($delete);
                $sql->bindParam(':id', $record['number']);
            } else {
                if (!empty($record['created']) && $record['created'] == true) {
                    $sql = $container->db->prepare($create);
                    if (!empty($record['createdAt'])) {
                        $sql->bindParam(':date', $record['createdAt']);
                    } else {
                        $sql->bindParam(':date', $var = NULL);
                    }
                } else {
                    $sql = $container->db->prepare($update);
                    $sql->bindParam(':id', $record['number']);
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
        }
        try {
            $sql->execute();
        } catch (PDOException $e) {
            $logger->write(
                'PDO: ' . $e->getMessage(),
                $container->errorLog
            );
            return false;
        }
        if (!empty($record['items'])) {
            foreach ($record['items'] as $item) {
                if (!empty($item['created']) && $item['created'] == true) {
                    $items = $rule->getSQL('orders_history_create_items');
                    $query = $container->db->prepare($items);
                    $query->bindParam(':externalId', $record['externalId']);
                    $query->bindParam(':product_name', $item['offer']['name']);
                    $query->bindParam(':price', $item['initialPrice']);
                    $query->bindParam(':amount', $item['quantity']);
                } elseif (!empty($item['deleted']) && $item['deleted'] == true) {
                    $items = $rule->getSQL('delete_item');
                    $query = $container->db->prepare($items);
                    $query->bindParam(':id', $item['externalId']);
                } else {
                    // то тогда мы можеи обновить свойства
                    $items = $rule->getSQL('update_item');
                    $query = $container->db->prepare($items);
                    $query->bindParam(':externalId', $item['externalId']);
                    $query->bindParam(':summ', $record['summ']);
                    $query->bindParam(':price', $record['initialPrice']);
                    $query->bindParam(':purchaseSumm', $record['purchaseSumm']);
                    $query->bindParam(':discount', $item['discount']);
                    $query->bindParam(':name', $item['offer']['name']);
                    $query->bindParam(':quantity', $item['quantity"']);
                }
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
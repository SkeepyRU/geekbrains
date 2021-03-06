<?php


namespace models;


/**
 * Class OrderModels
 * @package models
 */
class OrderModels extends BaseModel
{
    /**
     * Имя таблицы
     */
    const TABLE_NAME = 'orders';

    /**
     * Возвращаем заказы и пользователей
     * @return array|bool|mixed
     */
    public static function getAllOrdersWithUsers()
    {
        $query = '
                SELECT
                    o.id,
                    o.status,
                    o.amount,
                    o.created_at,
                    os.status_name,
                    os.css,
                    u.email
                FROM
                    `' . self::TABLE_NAME . '` o
                LEFT JOIN `orders_statuses` os ON
                    o.status = os.id
                LEFT JOIN `users` u ON
                    u.id = o.user_id
                ORDER BY
                    o.created_at
                DESC
                LIMIT 10';

        $result = self::execute($query, [], true);
        return $result;
    }

    /**
     * Устанавливаем статус заказа
     * @param $orderId
     * @param $statusId
     */
    public static function setOrderStatus($orderId, $statusId)
    {
        $query = '
            UPDATE
                `orders`
            SET
                `status` = :status_id
            WHERE
                `id` = :order_id;';

        $props = [
            'status_id' => $statusId,
            'order_id' => $orderId,
        ];
        self::execute($query, $props);
    }
}
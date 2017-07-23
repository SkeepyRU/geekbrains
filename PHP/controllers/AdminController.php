<?php

namespace controllers;


use models\OrderModels;
use models\OrderStatusModel;
use models\UserModel;

class AdminController extends BaseController
{
    public function actionIndex()
    {
        $user = (new UserModel())->getUser();

        if (!(new UserModel())->isAuth() || !$user['is_admin'])
        {
            header("Location: /");
            return;
        }

        $orders = (new OrderModels())->getAllOrdersWithUsers();
        $statuses = (new OrderStatusModel())->findAll();

        $this->render("admin/index", ['orders' => $orders, 'statuses' => $statuses], 'layouts/admin');

    }

    public function actionStatusChange()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if (!empty($_POST['data']))
            {
                $data = json_decode($_POST['data'], true);

                $orderId    = $data['order_id'];
                $statusId   = $data['status_id'];

                (new OrderModels())->setOrderStatus($orderId, $statusId);

                switch ($statusId)
                {
                    case 1: echo "danger";  break;
                    case 2: echo "warning"; break;
                    case 3: echo "info";    break;
                    case 4: echo "success"; break;
                }
            }

        }
    }
}
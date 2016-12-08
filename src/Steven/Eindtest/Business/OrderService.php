<?php

namespace Steven\Eindtest\Business;

use Steven\Eindtest\Data\OrderDAO;
use Steven\Eindtest\Exceptions\DateOutOfBoundsException;

class OrderService {

    public function confirm($cart, $email, $date) {
        $dateOrder = date_create(date($date));
        $dateNow = date_create(date('Y-m-d'));
        
        print_r($dateOrder);
        print_r($dateNow);
        print date_diff($dateOrder, $dateNow)->format('%R%a');
        $dateDiff = date_diff($dateOrder, $dateNow)->format('%R%a');
        if ($dateDiff >= 0 || $dateDiff < -3) {
            
            throw new DateOutOfBoundsException();
        }
        $cartlines = $cart->getCart();
        $orderDao = new OrderDAO();
        $userSvc = new UserService();
        $userId = $userSvc->getByEmail($email)->getId();
        $orderDao->add($cartlines, $userId, $date);
    }

    public function getOrders($email) {
        $userSvc = new UserService();
        $userId = $userSvc->getByEmail($email)->getId();
        //print $userId;
        $orderDao = new OrderDAO();
        $orderList = $orderDao->getByUserId($userId);
        return $orderList;
    }
    
    public function cancel($orderId){
        $orderDao = new OrderDAO();
        $order = $orderDao->getById($orderId);
        $orderDao->delete($order);
    }

}

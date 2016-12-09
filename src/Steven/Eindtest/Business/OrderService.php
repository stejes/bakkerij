<?php

namespace Steven\Eindtest\Business;

use Steven\Eindtest\Data\OrderDAO;
use Steven\Eindtest\Exceptions\DateOutOfBoundsException;

class OrderService {

    public function confirm($cart, $email, $date) {
        /* regex voor datum (yyyy-mm-dd) */
        if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)){
            throw new DateOutOfBoundsException();
        }
        /* check dat datum na vandaag is en max 3 dagen in de toekomst */
        $dateOrder = date_create(date($date));
        $dateNow = date_create(date('Y-m-d'));
        $dateDiff = date_diff($dateOrder, $dateNow)->format('%R%a');
        if ($dateDiff >= 0 || $dateDiff < -3) {
            
            throw new DateOutOfBoundsException();
        }
        /* creeer nieuwe bestelling */
        $cartlines = $cart->getCart();
        $orderDao = new OrderDAO();
        $userSvc = new UserService();
        $userId = $userSvc->getByEmail($email)->getId();
        $orderDao->add($cartlines, $userId, $date);
    }

    public function getOrders($email) {
        $userSvc = new UserService();
        $userId = $userSvc->getByEmail($email)->getId();
        $orderDao = new OrderDAO();
        $orderList = $orderDao->getByUserId($userId);
        return $orderList;
    }
    
    public function cancel($orderId){
        $orderDao = new OrderDAO();
        $order = $orderDao->getById($orderId);
        
        /* check of datum van te annuleren bestelling pas voor morgen of later is */
        $date = date_create(date('Y-m-d'));
        $dateOrder = date_create(date($order->getDate()));
        $dateDiff = date_diff($dateOrder, $date)->format('%R%a');
        if($dateDiff > -1){
            throw new DateOutOfBoundsException();
        }
        $orderDao->delete($order);
    }

}

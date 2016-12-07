<?php



namespace Steven\Eindtest\Business;
use Steven\Eindtest\Data\OrderDAO;
use Steven\Eindtest\Exceptions\DateOutOfBoundsException;

class OrderService {
    public function confirm($cart, $email, $date){
        $dateNow = date_create(date('Y-m-d'));
        $dateLate = date_add($dateNow, date_interval_create_from_date_string("3days"));
        if($date <= $dateNow || $date > $dateLate){
            throw new DateOutOfBoundsException();
        }
        $cartlines = $cart->getCart();
        $orderDao = new OrderDAO();
        $userSvc = new UserService();
        $userId = $userSvc->getByEmail($email)->getId();
        $orderDao->add($cartlines, $userId, $date);
    }
    
    public function getOrders($email){
        $userSvc = new UserService();
        $userId = $userSvc->getByEmail($email)->getId();
        //print $userId;
        $orderDao = new OrderDAO();
        $orderList = $orderDao->getByUserId($userId);
        return $orderList;
    }
}

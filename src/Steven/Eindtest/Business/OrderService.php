<?php



namespace Steven\Eindtest\Business;
use Steven\Eindtest\Data\OrderDAO;

class OrderService {
    public function confirm($cart, $email, $date){
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

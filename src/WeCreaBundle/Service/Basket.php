<?php
namespace WeCreaBundle\Service;


class Basket
{
    public function countBasket($session)
    {
        $count = 0;

        $basket = $session->get('basket');
        if (isset($basket)){
            foreach ($basket as $value) {
                $count += $value;
            }
        }

        return $count;
    }
}
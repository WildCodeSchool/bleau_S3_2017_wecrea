<?php
namespace WeCreaBundle\Service;


class Basket
{
    public function countBasket($session)
    {
        $count = 0;

        $baskets = $session->get('basket');
        if (isset($baskets)){
            foreach ($baskets as $works) {
                foreach ($works as $caract) {
                    $count += $caract;
                }
            }
        }

        return $count;
    }
}
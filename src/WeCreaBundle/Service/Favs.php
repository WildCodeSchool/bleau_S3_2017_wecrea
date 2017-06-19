<?php
namespace WeCreaBundle\Service;


class Favs
{
    public function countFavs($session)
    {
        $favs = $session->get('favs');

        $count = count($favs);

        return $count;
    }
}
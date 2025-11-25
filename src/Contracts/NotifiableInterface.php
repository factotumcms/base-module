<?php

namespace Wave8\Factotum\Base\Contracts;

interface NotifiableInterface
{
    public function notifications();

    public function readNotifications();

    public function unreadNotifications();
}

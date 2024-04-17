<?php

namespace Ajaxy\Forms\Inc\Notifications;

interface NotificationInterface
{
    public function send($form, $data);
}

<?php

namespace NW\WebService\References\Operations\Notification;

class NotificationManager
{
    /**
     * @param int $resellerId
     * @param int $clientId
     * @param string $status
     * @param int $differences
     * @param array $templateData
     *
     * @return void
     */
    public static function send(int $resellerId, int $clientId, string $status, int $differences, array $templateData)
    {
        //fakes send method
    }
}
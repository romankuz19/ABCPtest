<?php

namespace NW\WebService\References\Messages;

class MessagesClient
{
    /**
     * @param array $array
     * @param int $resellerId
     * @param string $status
     * @param int|null $clientId
     * @param int|null $differences
     *
     * @return void
     */
    public static function sendMessage(array $array, int $resellerId, string $status, int $clientId = null, int $differences = null)
    {
        //fakes sendMessage method
    }
}
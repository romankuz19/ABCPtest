<?php

namespace NW\WebService\References\Operations\Notification;

class Status
{
    private const STATUS_MAP = [
        0 => 'Completed',
        1 => 'Pending',
        2 => 'Rejected'
    ];

    /**
     * @param int $id
     *
     * @return string
     */
    public static function getStatusName(int $id): string
    {
        return self::STATUS_MAP[$id];
    }
}
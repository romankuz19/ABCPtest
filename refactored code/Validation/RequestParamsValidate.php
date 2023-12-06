<?php

namespace NW\WebService\References\Validation;

class RequestParamsValidate
{
    /**
     * @param array $data
     * @return void
     *
     * @throws \Exception
     */
    public static function validate(array $data): void
    {
        if (!isset($data['resellerId']) || !is_int($data['resellerId'])) {
            throw new \Exception('Empty or not int resellerId', 400);
        }

        if (!isset($data['complaintId']) || !is_int($data['complaintId'])) {
            throw new \Exception('Empty or not int complaintId', 400);
        }

        if (!isset($data['complaintNumber']) || !is_string($data['complaintNumber'])) {
            throw new \Exception('Empty or not string complaintNumber', 400);
        }

        if (!isset($data['notificationType']) || !is_int($data['notificationType'])) {
            throw new \Exception('Empty or not int notificationType', 400);
        }

        if (!isset($data['creatorId']) || !is_int($data['creatorId'])) {
            throw new \Exception('Empty or not int creatorId', 400);
        }

        if (!isset($data['expertId']) || !is_int($data['expertId'])) {
            throw new \Exception('Empty or not int expertId', 400);
        }

        if (!isset($data['clientId']) || !is_int($data['clientId'])) {
            throw new \Exception('Empty or not int clientId', 400);
        }

        if (!isset($data['consumptionId']) || !is_int($data['consumptionId'])) {
            throw new \Exception('Empty or not int consumptionId', 400);
        }

        if (!isset($data['consumptionNumber']) || !is_string($data['consumptionNumber'])) {
            throw new \Exception('Empty or not string consumptionNumber', 400);
        }

        if (!isset($data['agreementNumber']) || !is_string($data['agreementNumber'])) {
            throw new \Exception('Empty or not string agreementNumber', 400);
        }

        if (!isset($data['differences']['from']) || !is_int($data['differences']['from'])) {
            throw new \Exception('Empty or not int differences-from', 400);
        }

        if (!isset($data['differences']['to']) || !is_int($data['differences']['to'])) {
            throw new \Exception('Empty or not int differences-to', 400);
        }

        if (!isset($data['date'])) {
            throw new \Exception('Empty date', 400);
        }

    }
}
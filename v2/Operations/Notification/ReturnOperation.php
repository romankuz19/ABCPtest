<?php

namespace NW\WebService\References\Operations\Notification;

use NW\WebService\References\Messages\MessagesClient;
use NW\WebService\References\Messages\MessageTypes;
use NW\WebService\References\Operations\BaseOperation;
use NW\WebService\References\Roles\{CounterAgent, Seller};
use NW\WebService\References\Roles\Employee\{Creator, Expert,};
use NW\WebService\References\Validation\RequestParamsValidate;
use NW\WebService\References\Validation\RolesValidate;

class ReturnOperation extends BaseOperation
{
    public const TYPE_NEW    = 1;
    public const TYPE_CHANGE = 2;

    /**
     * @throws \Exception
     */
    public function doOperation(): array
    {
        $result = [
            'notificationEmployeeByEmail' => false,
            'notificationClientByEmail'   => false,
            'notificationClientBySms'     => [
                'isSent'  => false,
                'message' => '',
            ],
        ];

        $data = (array)$this->getRequest('data');

        RequestParamsValidate::validate($data);

        $resellerId = $data['resellerId'];
        $clientId = $data['clientId'];
        $notificationType = $data['notificationType'];
        $creatorId = $data['creatorId'];
        $expertId = $data['expertId'];

        RolesValidate::validateOthers(Seller::class, $resellerId);

        $client = CounterAgent::getById($clientId);
        RolesValidate::validateClient($client, $resellerId);

        RolesValidate::validateOthers(Creator::class, $creatorId);
        $creator = Creator::getById($creatorId);

        RolesValidate::validateOthers(Expert::class, $expertId);
        $expert = Expert::getById($expertId);

        $differences = [];
        if ($notificationType === self::TYPE_NEW) {
            $differences = ['NewPositionAdded', null, $resellerId];
        } elseif ($notificationType === self::TYPE_CHANGE) {
            if (isset($data['differences']['from']) && isset($data['differences']['to'])) {
                $differences = [
                    'PositionStatusHasChanged',
                    [
                        'FROM' => Status::getStatusName((int)$data['differences']['from']),
                        'TO' => Status::getStatusName((int)$data['differences']['to']),
                    ],
                    $resellerId
                ];
            }
        } else {
            throw new \Exception('Unknown notification type', 500);
        }

        $templateData = [
            'COMPLAINT_ID'       => (int)$data['complaintId'],
            'COMPLAINT_NUMBER'   => (string)$data['complaintNumber'],
            'CREATOR_ID'         => $creatorId,
            'CREATOR_NAME'       => $creator->getFullName(),
            'EXPERT_ID'          => $expertId,
            'EXPERT_NAME'        => $expert->getFullName(),
            'CLIENT_ID'          => $clientId,
            'CLIENT_NAME'        => $client->getFullName(),
            'CONSUMPTION_ID'     => (int)$data['consumptionId'],
            'CONSUMPTION_NUMBER' => (string)$data['consumptionNumber'],
            'AGREEMENT_NUMBER'   => (string)$data['agreementNumber'],
            'DATE'               => (string)$data['date'],
            'DIFFERENCES'        => $differences,
        ];

        // Если хоть одна переменная для шаблона не задана, то не отправляем уведомления
        foreach ($templateData as $key => $templateDataItem) {
            if (empty($templateDataItem)) {
                throw new \Exception("Template Data ($key) is empty!", 500);
            }
        }

        $emailFrom = $this->getResellerEmailFrom($resellerId);
        // Получаем email сотрудников из настроек
        $emails = $this->getEmailsByPermit($resellerId, 'tsGoodsReturn');
        if (!empty($emailFrom) && count($emails) > 0) {
            try {
                foreach ($emails as $email) {
                    MessagesClient::sendMessage([
                        MessageTypes::EMAIL => [
                            'emailFrom' => $emailFrom,
                            'emailTo' => $email,
                            'subject' => ['complaintEmployeeEmailSubject', $templateData, $resellerId],
                            'message' => ['complaintEmployeeEmailBody', $templateData, $resellerId],
                        ],
                    ], $resellerId, NotificationEvents::NEW_RETURN_STATUS);
                }
            } catch (\Exception $exception) {
                throw new \Exception($exception->getMessage(), $exception->getCode());
            }

            $result['notificationEmployeeByEmail'] = true;
        }

        // Шлём клиентское уведомление, только если произошла смена статуса
        if ($notificationType === self::TYPE_CHANGE && isset($data['differences']['to'])) {
            if (!empty($emailFrom) && !is_null($client->getEmail())) {
                try {
                    MessagesClient::sendMessage([
                        MessageTypes::EMAIL => [
                            'emailFrom' => $emailFrom,
                            'emailTo'   => $client->getEmail(),
                            'subject'   => ['complaintClientEmailSubject', $templateData, $resellerId],
                            'message'   => ['complaintClientEmailBody', $templateData, $resellerId],
                        ],
                    ], $resellerId, NotificationEvents::CHANGE_RETURN_STATUS, $clientId, (int)$data['differences']['to']);
                } catch (\Exception $exception) {
                    throw new \Exception($exception->getMessage(), $exception->getCode());
                }

                $result['notificationClientByEmail'] = true;
            }

            if (!is_null($client->getMobile())) {
                try {
                    NotificationManager::send(
                        $resellerId,
                        $clientId,
                        NotificationEvents::CHANGE_RETURN_STATUS,
                        (int)$data['differences']['to'],
                        $templateData
                    );
                } catch (\Exception $exception) {
                    $result['notificationClientBySms']['message'] = $exception->getMessage();
                }

                $result['notificationClientBySms']['isSent'] = true;
            }
        }

        return $result;
    }
}

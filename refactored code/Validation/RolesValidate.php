<?php

namespace NW\WebService\References\Validation;

use NW\WebService\References\Roles\CounterAgent;
use NW\WebService\References\Roles\Employee\Creator;
use NW\WebService\References\Roles\Employee\Expert;
use NW\WebService\References\Roles\Seller;

class RolesValidate
{
    /**
     * @param CounterAgent|null $client
     * @param int $resellerId
     * @return void
     *
     * @throws \Exception
     */
    public static function validateClient(?CounterAgent $client, int $resellerId): void
    {
        if (is_null($client) || $client->getType() !== CounterAgent::TYPE_CUSTOMER
            || $client->getSeller()->getId() !== $resellerId
        ) {
            throw new \Exception('Client not found!', 400);
        }
    }

    /**
     * @param $agentClassName
     * @param int $id
     * @return void
     *
     * @throws \Exception
     */
    public static function validateOthers($agentClassName, int $id): void
    {
        if (is_null($agentClassName::getById($id))) {
            if ($agentClassName === Seller::class) {
                throw new \Exception('Seller not found!', 400);
            }
            if ($agentClassName === Creator::class) {
                throw new \Exception('Creator not found!', 400);
            }
            if ($agentClassName === Expert::class) {
                throw new \Exception('Expert not found!', 400);
            }

            throw new \Exception("Class '$agentClassName' don't exist");
        }
    }
}


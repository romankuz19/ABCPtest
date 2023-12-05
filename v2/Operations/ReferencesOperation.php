<?php

namespace NW\WebService\References\Operations;

abstract class ReferencesOperation
{
    abstract public function doOperation(): array;

    /**
     * @param string $pName
     * @return mixed
     */
    public function getRequest(string $pName): mixed
    {
        return $_REQUEST[$pName];
    }

    /**
     * @return string
     */
    function getResellerEmailFrom(): string
    {
        return 'contractor@example.com';
    }

    /**
     * @param $resellerId
     * @param $event
     *
     * @return string[]
     */
    function getEmailsByPermit($resellerId, $event): array
    {
        // fakes the method
        return ['someEmail@example.com', 'someEmail2@example.com'];
    }
}
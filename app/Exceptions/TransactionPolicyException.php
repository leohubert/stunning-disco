<?php

namespace App\Exceptions;

use Exception;

class TransactionPolicyException extends Exception
{
    public static function cannotBuyFromHimself(): static
    {
        return new static('Transaction prohibited, buyer and seller cannot be the same.');
    }

    public static function clientCannotBuyToProvider(): static
    {
        return new static('Transaction prohibited, client cannot buy product form provider.');
    }

    public static function companyCannotBuyProductFromAnotherCompany(): static
    {
        return new static('Prohibited transaction, companies cannot buy products from another company..');
    }
}

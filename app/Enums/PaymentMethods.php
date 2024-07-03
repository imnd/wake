<?php

namespace App\Enums;

enum PaymentMethods: string
{
    case APPLE_PAY = 'applepay';
    case CASH_APP = 'cashapp';
    case CARD = 'card';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

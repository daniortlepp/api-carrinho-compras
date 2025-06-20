<?php

namespace App\Domain\Enums;

enum PaymentMethod: string
{
    case PIX = 'pix';
    case CREDIT_CARD_CASH = 'credit_card_cash';
    case CREDIT_CARD_INSTALLMENT = 'credit_card_installment';
}
<?php

namespace App\Infrastructure\Factories;

use App\Domain\Enums\PaymentMethod;
use App\Domain\Payments\PaymentCalculator;
use App\Infrastructure\Payments\CreditCardInstallmentPayment;
use App\Infrastructure\Payments\CreditCardCashPayment;
use App\Infrastructure\Payments\PixPayment;
use InvalidArgumentException;

class PaymentCalculatorFactory
{
    public function make(string $method): PaymentCalculator
    {
        return match ($method) {
            PaymentMethod::PIX->value => new PixPayment(),
            PaymentMethod::CREDIT_CARD_CASH->value => new CreditCardCashPayment(),
            PaymentMethod::CREDIT_CARD_INSTALLMENT->value => new CreditCardInstallmentPayment(),
            default => throw new InvalidArgumentException("Método de pagamento não suportado: {$method}"),
        };
    }
}
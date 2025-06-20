<?php

namespace App\Infrastructure\Payments;

use App\Domain\Payments\PaymentCalculator;

class CreditCardCashPayment implements PaymentCalculator
{
    public function calculate(float $totalAmount, int $installments = 1): array
    {
        $discountValue = $totalAmount * config('payments.discount_rate');
        $finalAmount = $totalAmount - $discountValue;

        return [
            'final_amount' => round($finalAmount, 2),
            'discount_value' => round($discountValue, 2),
        ];
    }
}
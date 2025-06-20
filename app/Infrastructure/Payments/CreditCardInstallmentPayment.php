<?php

namespace App\Infrastructure\Payments;

use App\Domain\Payments\PaymentCalculator;
use InvalidArgumentException;

class CreditCardInstallmentPayment implements PaymentCalculator
{
    public function calculate(float $totalAmount, int $installments = 1): array
    {
        if ($installments < 2 || $installments > 12) {
            throw new InvalidArgumentException('O número de parcelas para este método deve estar entre 2 e 12.');
        }

        $finalAmount = $totalAmount * pow((1 + config('payments.interest_rate')), $installments);
        $interestValue = $finalAmount - $totalAmount;
        $installmentValue = $finalAmount / $installments;

        return [
            'final_amount' => round($finalAmount, 2),
            'interest_value' => round($interestValue, 2),
            'installments' => $installments,
            'installment_value' => round($installmentValue, 2),
        ];
    }
}
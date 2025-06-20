<?php

namespace App\Domain\Payments;

interface PaymentCalculator
{
    /**
     * Calcula o valor final da compra considerando o total e o número de parcelas.
     *
     * @param float $totalAmount
     * @param int $installments
     * @return array
     */
    public function calculate(float $totalAmount, int $installments = 1): array;
}
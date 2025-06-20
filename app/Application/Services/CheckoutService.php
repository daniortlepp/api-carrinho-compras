<?php

namespace App\Application\Services;

use App\Domain\Entities\Cart;
use App\Domain\Entities\Item;
use App\Infrastructure\Factories\PaymentCalculatorFactory;

class CheckoutService
{
    public function __construct(private readonly PaymentCalculatorFactory $PaymentCalculatorFactory) {}

    public function process(array $itemsData, string $paymentMethod, int $installments): array
    {
        $cart = new Cart();
        foreach ($itemsData as $itemData) {
            $cart->addItem(new Item(
                name: $itemData['name'],
                unitPrice: $itemData['price'],
                quantity: $itemData['quantity']
            ));
        }

        $initialTotal = $cart->getInitialTotal();
        $paymentCalculator = $this->PaymentCalculatorFactory->make($paymentMethod);
        $paymentResult = $paymentCalculator->calculate($initialTotal, $installments);

        return [
            'initial_amount' => $initialTotal,
            ...$paymentResult,
        ];
    }
}
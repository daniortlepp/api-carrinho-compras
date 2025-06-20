<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutApiTest extends TestCase
{
    private function getBasePayload(): array
    {
        return [
            'items' => [
                ['name' => 'Produto A', 'price' => 50.00, 'quantity' => 2],
                ['name' => 'Produto B', 'price' => 100.00, 'quantity' => 1],
            ],
            'payment' => [
                'card_details' => [
                    'holder_name' => 'Maria da Silva',
                    'number' => '1234123412341234',
                    'expiry_date' => '12/29',
                    'cvv' => '198',
                ],
            ],
        ];
    }

    public function test_checkout_with_pix_applies_10_percent_discount(): void
    {
        $payload = $this->getBasePayload();
        $payload['payment']['method'] = 'pix';

        $response = $this->postJson('/api/checkout', $payload);

        $response->assertOk()
            ->assertJson([
                'initial_amount' => 200.00,
                'final_amount' => 180.00,
                'discount_value' => 20.00
            ]);
    }

    public function test_checkout_with_installments_applies_compound_interest(): void
    {
        $payload = $this->getBasePayload();
        $payload['payment']['method'] = 'credit_card_installment';
        $payload['payment']['installments'] = 3;

        $response = $this->postJson('/api/checkout', $payload);

        $response->assertOk()
            ->assertJson([
                'initial_amount' => 200.00,
                'final_amount' => 206.06,
                'installments' => 3,
                'installment_value' => 68.69,
            ]);
    }

    public function test_validation_fails_for_invalid_payment_method(): void
    {
        $payload = $this->getBasePayload();
        $payload['payment']['method'] = 'invalid_method';

        $response = $this->postJson('/api/checkout', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('payment.method');
    }
}
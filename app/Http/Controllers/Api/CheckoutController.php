<?php

namespace App\Http\Controllers\Api;

use App\Application\Services\CheckoutService;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use Illuminate\Http\JsonResponse;
use Throwable;

class CheckoutController extends Controller
{
    public function __construct(private readonly CheckoutService $checkoutService) {}

    public function __invoke(CheckoutRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            
            $result = $this->checkoutService->process(
                itemsData: $data['items'],
                paymentMethod: $data['payment']['method'],
                installments: $data['payment']['installments'] ?? 1
            );

            return response()->json($result);

        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
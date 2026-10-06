<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$order = \App\Models\Order::latest()->first();
$paymentResult = app(\App\Services\MidtransPaymentService::class)->createPayment($order);
$order->update([
    'payment_reference' => $paymentResult['payment_reference'], 
    'payment_url' => $paymentResult['payment_url']
]);
echo "Updated!";

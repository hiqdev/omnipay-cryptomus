<?php
declare(strict_types=1);

namespace Omnipay\Cryptomus\Tests;

use Omnipay\Cryptomus\Gateway;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    /** @var Gateway */
    public $gateway;

    protected string $merchantUuid = '788de464-d530-4444-9ecb-2254411b35ad';
    protected string $paymentKey = 'QKF0FiDuzsO6imz6egMTowYNV1YfjmRdtTMWugDqhCtW8AttHZpXeea9wh0uLZp3VJrEQ1L1pR25gl7o2CDpzuE1DTrQgSmEX02tMrt6QiiNXlMXoEQYw860YfZlCLyZ';
    protected string $transactionId = 'rGrIxUZFVknrFwbYvB';
    protected string $description = 'Test transaction';
    protected string $currency = 'USD';
    protected string $amount = '10.00';

    public function setUp(): void
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setMerchantUUID($this->merchantUuid);
        $this->gateway->setPaymentKey($this->paymentKey);
    }

    public function testGateway()
    {
        $this->assertSame($this->merchantUuid, $this->gateway->getMerchantUUID());
        $this->assertSame($this->paymentKey, $this->gateway->getPaymentKey());
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase([
            'transactionId' => $this->transactionId,
            'description' => $this->description,
            'currency' => $this->currency,
            'amount' => $this->amount,
        ]);

        $this->assertSame($this->transactionId, $request->getTransactionId());
        $this->assertSame($this->description, $request->getDescription());
        $this->assertSame($this->currency, $request->getCurrency());
        $this->assertSame($this->amount, $request->getAmount());
    }

    public function testCompletePurchase()
    {
        $request = $this->gateway->completePurchase([
            'transactionId' => $this->transactionId,
        ]);

        $this->assertSame($this->merchantUuid, $request->getMerchantUUID());
        $this->assertSame($this->paymentKey, $request->getPaymentKey());
        $this->assertSame($this->transactionId, $request->getTransactionId());
    }
}

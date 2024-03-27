<?php
declare(strict_types=1);

namespace Omnipay\Cryptomus\Tests\Message;

use Omnipay\Cryptomus\Message\PurchaseRequest;
use Omnipay\Cryptomus\Message\PurchaseResponse;
use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    protected PurchaseRequest $request;

    protected array $parameters = [
        'merchantUuid' => '788de464-d530-4444-9ecb-2254411b35ad',
        'paymentKey' => 'QKF0FiDuzsO6imz6egMTowYNV1YfjmRdtTMWugDqhCtW8AttHZpXeea9wh0uLZp3VJrEQ1L1pR25gl7o2CDpzuE1DTrQgSmEX02tMrt6QiiNXlMXoEQYw860YfZlCLyZ',
        'transactionId' => 'rGrIxUZFVknrFwbYvB',
        'description' => 'Test transaction',
        'currency' => 'USD',
        'amount' => '10.00',
        'returnUrl' => 'https://www.example.com/success',
        'cancelUrl' => 'https://www.example.com/failure',
        'notifyUrl' => 'https://www.example.com/notify',
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize($this->parameters);
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame($this->parameters['amount'], $data['amount']);
        $this->assertSame($this->parameters['currency'], $data['currency']);
        $this->assertSame($this->parameters['transactionId'], $data['order_id']);
        $this->assertSame($this->parameters['cancelUrl'], $data['url_return']);
        $this->assertSame($this->parameters['returnUrl'], $data['url_success']);
        $this->assertSame($this->parameters['notifyUrl'], $data['url_callback']);
        $this->assertSame($this->parameters['description'], $data['additional_data']);
    }

    public function testSendData()
    {
        $this->setMockHttpResponse('PurchaseRequest_success.txt');
        $response = $this->request->send();
        $this->assertInstanceOf(PurchaseResponse::class, $response);
        $this->assertSame('https://pay.cryptomus.com/pay/e1bf6e4a-4465-40b4-849e-60dfe7bced49', $response->getRedirectUrl());
        $this->assertSame('GET', $response->getRedirectMethod());
    }
}

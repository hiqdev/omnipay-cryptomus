<?php
declare(strict_types=1);

namespace Omnipay\Cryptomus\Tests\Message;

use DateTime;
use InvalidArgumentException;
use Omnipay\Cryptomus\Message\CompletePurchaseRequest;
use Omnipay\Cryptomus\Message\CompletePurchaseResponse;
use Omnipay\Tests\TestCase;

class CompletePurchaseResponseTest extends TestCase
{
    protected array $parameters = [
        'merchantUuid' => '788de464-d530-4444-9ecb-2254411b35ad',
        'paymentKey' => 'QKF0FiDuzsO6imz6egMTowYNV1YfjmRdtTMWugDqhCtW8AttHZpXeea9wh0uLZp3VJrEQ1L1pR25gl7o2CDpzuE1DTrQgSmEX02tMrt6QiiNXlMXoEQYw860YfZlCLyZ',
        'transactionId' => 'rGrIxUZFVknrFwbYvB',
    ];

    private function getRequest(string $mockFileName): CompletePurchaseRequest
    {
        $json = file_get_contents(__DIR__ . '/Mock/' . $mockFileName);
        $inputs = json_decode($json,true);
        $this->getHttpRequest()->request->replace($inputs);

        $request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize($this->parameters);

        return $request;
    }

    public function testResponseProperties(): void
    {
        $request = $this->getRequest('CompletePurchaseRequest_success.json');

        $this->setMockHttpResponse('CompletePurchaseResponse_info.txt');
        $response = $request->send();

        $this->assertInstanceOf(CompletePurchaseResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertSame('e1bf6e4a-4465-40b4-849e-60dfe7bced49', $response->getTransactionId());
        $this->assertSame('rGrIxUZFVknrFwbYvB', $response->getTransactionReference());
        $this->assertSame('paid', $response->getTransactionStatus());
        $this->assertEquals(10, $response->getAmount());
        $this->assertEquals(10, $response->getRequestedAmount());
        $this->assertEquals(0.06, $response->getFee());
        $this->assertSame('USD', $response->getCurrency());
        $this->assertSame('THgEWubVc8tPKXLJ4VZ5zbiiAK7AgqSeGH, txid: 6f0d9c8374db57cac0d806251473de754f361c83a03cd805f74aa9da3193486b', $response->getPayer());
        $this->assertEquals(new DateTime('2024-03-27 10:51:48'), $response->getTime());
    }

    public function testWrongSignature()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Failed to validate signature');
        $this->getRequest('CompletePurchaseRequest_wrong_sign.json')->send();
    }
}

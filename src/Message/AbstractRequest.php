<?php

declare(strict_types=1);

/*
 * Cryptomus driver for Omnipay PHP payment library
 *
 * @link      https://github.com/hiqdev/omnipay-cryptomus
 * @package   omnipay-cryptomus
 * @license   MIT
 * @copyright Copyright (c) 2024, HiQDev (http://hiqdev.com/)
 */

namespace Omnipay\Cryptomus\Message;


use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Cryptomus Abstract Request.
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /**
     * @var string
     */
    protected $endpoint = 'https://api.cryptomus.com/';

    public function getPaymentKey(): string
    {
        return $this->getParameter('paymentKey');
    }

    public function setPaymentKey(string $value)
    {
        return $this->setParameter('paymentKey', $value);
    }

    public function getMerchantUUID(): string
    {
        return $this->getParameter('merchantUUID');
    }

    public function setMerchantUUID(string $value)
    {
        return $this->setParameter('merchantUUID', $value);
    }

    /**
     * @param string $uri
     * @param array $data
     * @return array
     * @throws InvalidResponseException
     */
    public function sendRequest(string $uri, array $data)
    {
        $body = json_encode($data, JSON_UNESCAPED_UNICODE);
        $httpResponse = $this->httpClient->request('POST', $this->endpoint . $uri, [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json;charset=UTF-8',
            'merchant' => $this->getMerchantUUID(),
            'sign' => md5(base64_encode($body) . $this->getPaymentKey()),
        ], $body);

        $responseCode = $httpResponse->getStatusCode();
        $response = json_decode($httpResponse->getBody()->getContents(), true);

        if ($responseCode !== 200 || (!is_null($response['state']) && $response['state'] !== 0)) {
            if (!empty($response['message'])) {
                throw new InvalidResponseException('Error occurred: ' . $response['message']);
            }

            if (!empty($response['errors'])) {
                throw new InvalidResponseException('Validation error: ' . implode(',', $response['errors']));
            }
        }

        if (empty($response['result']) || $response['state'] ?? null !== 0) {
            throw new InvalidResponseException('Invalid response: ' . json_encode($response));
        }

        return $response['result'];
    }
}

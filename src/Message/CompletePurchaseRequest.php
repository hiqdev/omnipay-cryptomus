<?php

declare(strict_types=1);

namespace Omnipay\Cryptomus\Message;

use JsonException;
use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Cryptomus Complete Purchase Request.
 */
class CompletePurchaseRequest extends AbstractRequest
{
    /**
     * Get the data for this request.
     *
     * @return array request data
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate(
            'paymentKey',
            'merchantUUID',
        );

        try {
            $jsonDecodedBody = $this->httpRequest->toArray();
        } catch (\Symfony\Component\HttpFoundation\Exception\JsonException $e) {
        }

        return array_merge($jsonDecodedBody ?? [], $this->httpRequest->request->all());
    }

    /**
     * Send the request with specified data.
     *
     * @param mixed $data The data to send
     *
     * @return CompletePurchaseResponse
     */
    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $this->getData());
    }
}

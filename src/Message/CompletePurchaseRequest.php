<?php

declare(strict_types=1);

namespace Omnipay\Cryptomus\Message;

use BitPaySDKLight\Exceptions\BitPayException;
use BitPaySDKLight\Exceptions\InvoiceQueryException;
use Cryptomus\Api\Payment;
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
            'transactionId',
            'paymentKey',
            'merchantUUID',
        );

        return $this->httpRequest->request->all();
    }

    /**
     * Send the request with specified data.
     *
     * @param mixed $data The data to send
     *
     * @return CompletePurchaseResponse
     * @throws InvoiceQueryException
     */
    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $this->getData());
    }
}

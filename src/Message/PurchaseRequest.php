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

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Cryptomus Purchase Request.
 */
class PurchaseRequest extends AbstractRequest
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
            'amount',
            'currency',
            'paymentKey',
            'merchantUUID',
            'transactionId',
            'returnUrl',
            'notifyUrl'
        );

        return [
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
            'order_id' => $this->getTransactionId(),
            'url_return' => $this->getCancelUrl(),
            'url_success' => $this->getReturnUrl(),
            'url_callback' => $this->getNotifyUrl(),
            'additional_data' => $this->getDescription(),
        ];
    }

    /**
     * Send the request with specified data.
     *
     * @param mixed $data The data to send
     *
     * @return PurchaseResponse
     */
    public function sendData($data)
    {
        $response = $this->sendRequest('v1/payment', $data);

        return $this->response = new PurchaseResponse($this, $response);
    }
}

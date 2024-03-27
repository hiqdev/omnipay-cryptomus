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

use InvalidArgumentException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Cryptomus Complete Purchase Response.
 */
class CompletePurchaseResponse extends AbstractResponse
{
    /**
     * @var CompletePurchaseRequest
     */
    public $request;

    /**
     * @var array{
     *     type: string,
     *     uuid: string,
     *     order_id: string,
     *     amount: string,
     *     payment_amount: string,
     *     payment_amount_usd: string,
     *     merchant_amount: string,
     *     commission: string,
     *     from: string,
     *     is_final: string,
     *     status: "confirm_check"|"paid"|"paid_over"|"fail"|"wrong_amount"|"cancel"|"system_fail"|"refund_process"|"refund_fail"|"refund_paid",
     *     wallet_address_uuid: string,
     *     network: string,
     *     currency: string,
     *     payer_currency: string,
     *     additional_data: string,
     *     convert: array{
     *         to_currency: string,
     *         commission: string|null,
     *         rate: string,
     *         amount: string,
     *     },
     *     sign: string,
     *     txid: string,
     * }
     * @see https://doc.cryptomus.com/payments/webhook
     */
    protected $data;

    /**
     * @var array{
     *     uuid: string,
     *     order_id: string,
     *     amount: string,
     *     payment_amount: string,
     *     payer_amount: string,
     *     discount_percent: string,
     *     discount: string,
     *     payer_currency: string,
     *     currency: string,
     *     merchant_amount: string,
     *     network: string,
     *     address: string,
     *     from: string,
     *     txid: string,
     *     payment_status: string,
     *     url: string,
     *     expired_at: int,
     *     is_final: string,
     *     additional_data: string,
     *     created_at: string,
     *     updated_at: string, // Timezone is UTC+3
     * }
     */
    protected $info;

    public function __construct(RequestInterface $request, array $data)
    {
        parent::__construct($request, $data);

        if ($this->data['type'] !== 'payment') {
            throw new InvalidArgumentException('Only payment type is supported, got ' . $this->data['type']);
        }

        $this->validate();

        $this->info = $this->request->sendRequest('v1/payment/info', ['uuid' => $this->data['uuid']]);
    }

    /**
     * Whether the payment is successful.
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        $successStatuses = ['paid', 'paid_over'];
        return in_array($this->getTransactionStatus(), $successStatuses, true);
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getTransactionId()
    {
        return $this->data['uuid'];
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getTransactionReference()
    {
        return $this->data['order_id'];
    }

    /**
     * Retruns the transatcion status.
     *
     * @return string
     */
    public function getTransactionStatus()
    {
        return $this->data['status'];
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getAmount()
    {
        return $this->data['payment_amount'];
    }

    /**
     * @return string
     */
    public function getRequestedAmount()
    {
        return $this->data['amount'];
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getFee()
    {
        return $this->data['commission'];
    }

    /**
     * Returns the currency.
     *
     * @return string
     */
    public function getCurrency()
    {
        return strtoupper($this->data["currency"]);
    }

    /**
     * Returns the payer "name/email".
     *
     * @return string
     */
    public function getPayer()
    {
        $buyer = $this->data['from'];
        $txid = $this->data['txid'];

        return "$buyer, txid: $txid";
    }

    /**
     * Returns the ISO 8601 payment time.
     *
     * @return string
     */
    public function getTime()
    {
        return strtotime($this->info['updated_at']);
    }

    private function validate()
    {
        $data = $this->data;
        unset($data['sign']);
        $hash = md5(base64_encode(json_encode($data, JSON_UNESCAPED_UNICODE)) . $this->request->getPaymentKey());

        if ($this->data['sign'] !== $hash) {
            throw new InvalidArgumentException('Failed to validate signature');
        }
    }
}

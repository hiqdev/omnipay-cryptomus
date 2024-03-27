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

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * cryptomusComplete Purchase Response.
 */
class PurchaseResponse extends AbstractResponse
{
    /**
     * @var CompletePurchaseRequest
     */
    public $request;

    /**
     * @var array{
     *     url: string,
     *     uuid: string,
     *     order_id: string
     * }
     */
    protected $data;

    public function __construct(RequestInterface $request, array $data)
    {
        parent::__construct($request, $data);
    }

    /**
     * Whether the payment is successful.
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return true;
    }

    public function getRedirectUrl()
    {
        return $this->data['url'];
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return [];
    }
}

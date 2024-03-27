<?php
declare(strict_types=1);
/**
 * Cryptomus driver for Omnipay PHP payment library
 *
 * @link      https://github.com/hiqdev/omnipay-cryptomus
 * @package   omnipay-cryptomus
 * @license   MIT
 * @copyright Copyright (c) 2024, HiQDev (http://hiqdev.com/)
 */

namespace Omnipay\Cryptomus;

use Omnipay\Cryptomus\Message\CompletePurchaseRequest;
use Omnipay\Cryptomus\Message\PurchaseRequest;
use Omnipay\Common\AbstractGateway;

/**
 * @method \Omnipay\Common\Message\RequestInterface authorize(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface capture(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface refund(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface void(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface createCard(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = [])
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Cryptomus';
    }

    public function getDefaultParameters()
    {
        return [
            'paymentKey' => '',
            'merchantUUID' => '',
        ];
    }

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
     * @param array $parameters
     * @return PurchaseRequest|\Omnipay\Common\Message\AbstractRequest
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return CompletePurchaseRequest|\Omnipay\Common\Message\AbstractRequest
     */
    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }
}

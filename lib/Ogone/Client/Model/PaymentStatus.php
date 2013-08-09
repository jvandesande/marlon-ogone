<?php

namespace Ogone\Client\Model;

use Guzzle\Service\Command\OperationCommand;
use Guzzle\Service\Command\ResponseClassInterface;

/**
 * OrderStatus
 *
 * @author  Joris van de Sande <joris.van.de.sande@freshheads.com>
 */
class PaymentStatus implements ResponseClassInterface
{
    /**
     * @var string
     */
    protected $orderId;
    /**
     * @var string
     */
    protected $payId;
    /**
     * @var string
     */
    protected $payIdSub;
    /**
     * @var string
     */
    protected $ncStatus;
    /**
     * @var string
     */
    protected $ncError;
    /**
     * @var string
     */
    protected $ncErrorPlus;
    /**
     * @var string
     */
    protected $status;
    /**
     * @var array
     */
    protected $additionalFields = array();

    public function __construct($payId, $payIdSub)
    {
        $this->payId = $payId;
        $this->payIdSub = $payIdSub;
    }

    /**
     * @return array
     */
    public function getAdditionalFields()
    {
        return $this->additionalFields;
    }

    /**
     * @param string $fieldName
     * @return mixed
     */
    public function getAdditionalField($fieldName)
    {
        return isset($this->additionalFields[$fieldName]) ? $this->additionalFields[$fieldName] : null;
    }

    /**
     * @return string
     */
    public function getNcError()
    {
        return $this->ncError;
    }

    /**
     * @return string
     */
    public function getNcErrorPlus()
    {
        return $this->ncErrorPlus;
    }

    /**
     * @return string
     */
    public function getNcStatus()
    {
        return $this->ncStatus;
    }

    /**
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @return string
     */
    public function getPayId()
    {
        return $this->payId;
    }

    /**
     * @return string
     */
    public function getPayIdSub()
    {
        return $this->payIdSub;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return bool
     */
    public function hasError()
    {
        return $this->getNcError() > 0;
    }

    /**
     * Create a response model object from a completed command
     *
     * @param OperationCommand $command That serialized the request
     *
     * @return self
     */
    public static function fromCommand(OperationCommand $command)
    {
        $simpleXml = $command->getResponse()->xml();
        $attributes = $simpleXml->attributes();

        $paymentStatus = new static((string) $attributes['PAYID'], (string) $attributes['PAYIDSUB']);
        $paymentStatus->orderId = (string) $attributes['orderID'];
        $paymentStatus->status = (string) $attributes['STATUS'];
        $paymentStatus->ncStatus = (string) $attributes['NCSTATUS'];
        $paymentStatus->ncError = (string) $attributes['NCERROR'];
        $paymentStatus->ncErrorPlus = (string) $attributes['NCERRORPLUS'];

        unset(
            $attributes['PAYID'], $attributes['PAYIDSUB'], $attributes['orderID'], $attributes['STATUS'],
            $attributes['NCSTATUS'], $attributes['NCERROR'], $attributes['NCERRORPLUS']
        );

        foreach ($attributes as $key => $value) {
            $paymentStatus->additionalFields[$key] = (string) $value;
        }

        return $paymentStatus;
    }
}

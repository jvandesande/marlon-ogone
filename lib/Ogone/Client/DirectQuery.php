<?php

namespace Ogone\Client;

use Guzzle\Service\Builder\ServiceBuilder;
use Guzzle\Service\Client;
use Guzzle\Common\Collection;
use Guzzle\Service\Description\ServiceDescription;
use Ogone\Client\Model\PaymentStatus;

/**
 * DirectQuery client.
 *
 * <code>
 * DirectQuery
 * </code>
 *
 * @author  Joris van de Sande <joris.van.de.sande@freshheads.com>
 */
class DirectQuery extends Client
{
    /**
     * @var string
     */
    const TEST_URI = 'https://secure.ogone.com/ncol/test/querydirect.asp';
    /**
     * @var string
     */
    const PRODUCTION_URI = 'https://secure.ogone.com/ncol/prod/querydirect.asp';

    /**
     * @param array $config
     * @param string $baseUrl
     */
    public function __construct($config = null, $baseUrl = self::TEST_URI)
    {
        $required = array('pspid', 'username', 'password');
        $config = Collection::fromConfig($config, array(), $required);

        parent::__construct($baseUrl, $config);

        $this->initDescription();
    }

    /**
     * @param array $config
     * @return DirectQuery
     */
    public static function factory($config = array())
    {
        return new static($config, isset($config['base_url']) ? $config['base_url'] : static::TEST_URI);
    }


    /**
     * @param string|int $payId
     * @param string|int $payIdSub
     *
     * @return PaymentStatus
     */
    public function getPaymentStatusByPayId($payId, $payIdSub = null)
    {
        $args = array('PAYID' => $payId);

        if ($payIdSub !== null) {
            $args['PAYIDSUB'] = $payIdSub;
        }
        $command = $this->getCommand('getPaymentStatus', $this->addDefaultParameters($args));

        return $command->getResult();
    }

    /**
     * @param string|int $orderId
     * @return PaymentStatus
     */
    public function getPaymentStatusByOrderId($orderId)
    {
        $command = $this->getCommand('getPaymentStatus', $this->addDefaultParameters(array('ORDERID' => $orderId)));

        return $command->getResult();
    }

    protected function initDescription()
    {
        $serviceDescription = ServiceDescription::factory(
            array(
                'operations' => array(
                    'getPaymentStatus' => array(
                        'httpMethod' => "GET",
                        'parameters' => array(
                            'PSPID' => array(
                                'type' => "string",
                                'location' => "query",
                                'required' => true,
                            ),
                            'USERID' => array(
                                'type' => "string",
                                'location' => "query",
                                'required' => true,
                            ),
                            'PSWD' => array(
                                'type' => "string",
                                'location' => "query",
                                'required' => true,
                            ),
                            'ORDERID' => array(
                                'type' => array("string", "integer"),
                                'location' => "query",
                                'required' => false,
                            ),
                            'PAYID' => array(
                                'type' => array("string", "integer"),
                                'location' => "query",
                                'required' => false,
                            ),
                            'PAYIDSUB' => array(
                                'type' => array("string", "integer"),
                                'location' => "query",
                                'required' => false,
                            ),
                        ),
                        'responseClass' => 'Ogone\\Client\\Model\\PaymentStatus'
                    )
                )
            )
        );

        $this->setDescription($serviceDescription);
    }

    /**
     * Adds the default parameters to the arguments array.
     *
     * @param array $args
     * @return array
     */
    protected function addDefaultParameters(array $args = array())
    {
        $args['PSPID'] = $this->getConfig('pspid');
        $args['USERID'] = $this->getConfig('username');
        $args['PSWD'] = $this->getConfig('password');

        return $args;
    }
}

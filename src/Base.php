<?php
/*
 *
 *  * -------------------------------------------------------------
 *  * Copyright (c) 2020
 *  * -created by Ariful Islam
 *  * -All Rights Preserved By
 *  *    Ariful Islam
 *  *    www.phpdark.com
 *  * -If you have any query then knock me at
 *  * arif98741@gmail.com
 *  * See my profile @ https://github.com/arif98741
 *  * ----------------------------------------------------------------
 *
 */

namespace NagadApi;


use NagadApi\lib\Key;

/**
 * Class Base
 * This is the decision maker where request will go, generate url and also
 * decide the environment according to .env data
 * @package NagadApi
 */
class Base
{

    /**
     * environment
     */
    public $environment = 'development';

    /**
     * @var string
     */
    private $base_url = 'http://sandbox.mynagad.com:10080/remote-payment-gateway-1.0/';
    /**
     * @var string
     */
    private $timezone;
    /**
     * @var mixed
     */
    private $amount;
    /**
     * @var mixed
     */
    private $invoice;
    /**
     * @var mixed
     */
    private $merchantID;

    /**
     * public key object
     */
    public $keyObject;

    /**
     * CallBack Url for merchant
     */
    public $merchantCallback;

    /**
     * Base constructor
     * @param $config
     * @param $params
     * @since v1.3.2
     */
    public function __construct($config, $params)
    {
        $this->keyObject = new Key($config);
        $this->amount = $params['amount'];
        $this->invoice = $params['invoice'];
        $this->merchantID = $this->keyObject->getAppMerchantID();
        $this->merchantCallback = $params['merchantCallback'];
        $this->setTimeZone($this->keyObject->getTimeZone());
        date_default_timezone_set($this->timezone);

        /**
         * Before activating production environment be confirm that your system is ok and out of bug
         * it is highly recommended to test your environment using development environment
         * your ip,domain and callback_url should be whitelisted in Nagad end
         */
        if ($this->keyObject->getAppEnv() == 'production') {
            $this->base_url = 'https://api.mynagad.com/';
            $this->environment = $this->keyObject->getAppEnv();
        }

    }

    /**
     * Final Send Request to Nagad
     * @param Base $base
     * @return array
     * @throws \Exception
     * @since v1.6.0
     */
    public function payNow(Base $base)
    {
        $request = new RequestHandler($base);

        return $request->sendRequest();
    }

    /**
     * @return string
     * @since v1.3.1
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * @param $timeZone
     * @since v1.3.1
     */
    public function setTimeZone($timeZone)
    {
        if (!empty($timeZone)) {
            $this->timezone = $timeZone;
        } else {
            $this->timezone = 'Asia/Dhaka';
        }

    }

    /**
     * @return mixed
     * @since v1.3.1
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return mixed
     * @since v1.3.1
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * @return mixed
     * @since v1.3.1
     */
    public function getMerchantID()
    {
        return $this->merchantID;
    }

    /**
     * @return string
     * @since v1.3.1
     */
    public function getBaseUrl()
    {
        return $this->base_url;
    }

    /**
     * @return $this
     * @since v1.3.1
     */
    public function getVariables()
    {
        return $this;
    }

}
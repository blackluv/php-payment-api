<?php
namespace Heidelpay\PhpApi\PaymentMethodes;

/**
 * This classe is the basic payment method trait
 *
 * It contains the main properties and functions for
 * every payment method
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  https://dev.heidelpay.de/PhpApi
 *
 * @author  Jens Richter
 *
 * @package  Heidelpay
 * @subpackage PhpApi
 * @category PhpApi
 */
use Heidelpay\PhpApi\Exceptions\UndefinedTransactionModeException;
use Heidelpay\PhpApi\Request as HeidelpayRequest;

trait BasicPaymentMethodTrait
{

    /**
     * Payment Url of the live payment server
     *
     * @var string url for heidelpay api connection real or live system
     */
    protected $_liveUrl       = 'https://heidelpay.hpcgw.net/ngw/post';

    /**
     * Payment Url of the sandbox payment server
     *
     * @var string url for heidelpay api connection sandbox system
     */
    protected $_sandboxUrl     = 'https://test-heidelpay.hpcgw.net/ngw/post';

    /**
     * HTTP Adapter for payment connection
     *
     * @var \Heidelpay\PhpApi\Adapter\CurlAdapter
     */
    protected $_adapter = null;

    /**
     * Heidelpay request object
     *
     * @var \Heidelpay\PhpApi\Request
     */
    protected $_request = null;

    /**
     * Heidelpay request array
     *
     * @var array request
     */
    protected $_requestArray = null;

    /**
     * Heidelpay response object
     *
     * @var \Heidelpay\PhpApi\Response
     */
    protected $_response = null;

    /**
     * Heidelpay response array
     *
     * @var array response
     */
    protected $_responseArray = null;

    /**
     * Dry run
     *
     * If set to true request will be generated but not send to payment api.
     * This is use full for testing.
     *
     * @var boolean dry run
     */
    public $_dryRun = false;

    /**
     * Return the name of the used class
     *
     * @return string class name
     */
    public static function getClassName()
    {
        return substr(strrchr(get_called_class(), '\\'), 1);
    }

    /**
     * Set a new payment request object
     *
     * @param \Heidelpay\PhpApi\Request $Request
     */
    public function setRequest(HeidelpayRequest $Request)
    {
        $this->_request = $Request;
    }

    /**
     * Get payment request object
     *
     * @return \Heidelpay\PhpApi\Request
     */
    public function getRequest()
    {
        if ($this->_request === null) {
            return $this->_request = new HeidelpayRequest();
        }

        return  $this->_request;
    }

    /**
     * Get response object
     *
     * @return \Heidelpay\PhpApi\Response
     */
    public function getResponse()
    {
        return $this->_response;
    }

    /**
     * Set a HTTP Adapter for payment communication
     *
     * @param \Heidelpay\PhpApi\Adapter\CurlAdapter
     * @param mixed $adapter
     */
    public function setAdapter($adapter)
    {
        $this->_adapter = $adapter;
    }

    /**
     * Get HTTP Adapter for payment communication
     *
     * @return object
     */
    public function getAdapter()
    {
        return  $this->_adapter;
    }

    /**
     * Get url of the used payment api
     *
     * @throws \Exception mode not set
     *
     * @return boolean|string url of the payment api
     */
    public function getPaymentUrl()
    {
        $mode = $this->getRequest()->getTransaction()->getMode();

        if ($mode === null) {
            throw new UndefinedTransactionModeException('Transaction mode is not set');
        } elseif ($mode == 'LIVE') {
            return $this->_liveUrl;
        }

        return $this->_sandboxUrl;
    }
}

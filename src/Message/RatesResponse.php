<?php

namespace Omnipay\Coinpayments\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Response
 */
class RatesResponse extends AbstractResponse
{

    public function isSuccessful()
    {
        return isset($this->data['error']) && $this->data['error'] == 'ok';
    }

    public function getCoins()
    {
        if (isset($this->data['result'])) {
            return $this->data['result'];
        }
    }

    public function getAccepted()
    {
      if (isset($this->data['result'])) {
        $acceptedCoins = array();

        foreach ($this->data['result'] as $currency=>$detail) {
          if ($detail['status'] != 'online') {
            // coin status is not online, can not accept payments at this time
            continue;
          }
          if ($detail['accepted'] != 1) {
            // coin not accepted
            continue;
          }
          $acceptedCoins[$currency] = $detail;
        }

        return $acceptedCoins;
      }
    }

    /**
     * Does the response require a redirect?
     *
     * @return boolean
     */
    public function isRedirect()
    {
        return false;
    }

    /**
     * Get the response data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}

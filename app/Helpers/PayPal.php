<?php
/**
 * Created by PhpStorm.
 * User: adriannatabio
 * Date: 30/04/2019
 * Time: 3:25 AM
 */

namespace App\Helpers;

use Omnipay\Common\CreditCard;
use Omnipay\Common\GatewayInterface;
use Omnipay\Omnipay;

/**
 * Class PayPal
 * @package App
 */
class PayPal
{

    /** @var GatewayInterface $gateway */
    protected $gateway;

    /** @var CreditCard $card */
    protected $card;

    public function __construct()
    {
        $this->gateway = Omnipay::create('PayPal_Rest');

        $this->gateway->initialize([
            'clientId' => config('services.paypal.client_id'),
            'secret'   => config('services.paypal.client_secret'),
            'testMode' => config('services.paypal.test_mode')
        ]);
    }

    public function setCard(array $cardDetails) : PayPal
    {
        $this->card = new CreditCard($cardDetails);

        return $this;
    }

    public function pay(float $amount) : array
    {
        try {
            $transaction = $this->gateway->purchase([
                'amount'      => $amount,
                'currency'    => 'USD',
                'description' => config('app.name'),
                'card'        => $this->card,
            ]);

            $response = $transaction->send();
            $data     = $response->getData();

            return [
                'status'  => $response->isSuccessful(),
                'details' => $data
            ];

        } catch (\Exception $e) {
            throw new \ErrorException($e->getMessage());
        }
    }


}
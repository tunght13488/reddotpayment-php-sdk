<?php
/**
 * Client
 *
 * @copyright Copyright © 2019 COURTS (SINGAPORE) PTE LTD. All rights reserved.
 * @author    Tung Ha <tung@courts.com.sg>
 */

namespace Reddot\Redirect;

use Respect\Validation\Validator as v;

class Client
{
    const TEST_BASE_URL = 'https://secure-dev.reddotpayment.com/service/payment-api';
    const PRODUCTION_BASE_URL = 'https://secure.reddotpayment.com/service/payment-api';

    const API_MODE_HOP = 'redirection_hosted';
    const API_MODE_SOP = 'redirection_sop';

    const PAYMENT_TYPE_SALE = 'S';
    const PAYMENT_TYPE_AUTHORISATION = 'A';
    const PAYMENT_TYPE_INSTALLMENT = 'I';

    /**
     * @var string
     */
    private $mid;
    /**
     * @var string
     */
    private $key;
    /**
     * @var bool
     */
    private $isProduction;
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    public function __construct(string $mid, string $key, bool $isProduction)
    {
        $this->mid = $mid;
        $this->key = $key;
        $this->isProduction = $isProduction;

        $this->init();
    }

    /**
     * @param array $data
     *
     * @return array
     * @throws \Respect\Validation\Exceptions\ValidationException
     */
    public function getPaymentUrl(array $data)
    {
        $validator = v::arrayVal()
            // ->key('mid', v::stringType()->length(null, 20))
            ->key('api_mode', v::stringType()->in([
                self::API_MODE_HOP,
                self::API_MODE_SOP,
            ], true))
            ->key('payment_type', v::stringType()->in([
                self::PAYMENT_TYPE_SALE,
                self::PAYMENT_TYPE_AUTHORISATION,
                self::PAYMENT_TYPE_INSTALLMENT,
            ], true))
            ->key('order_id', v::stringType()->length(null, 20))
            ->key('ccy', v::currencyCode())
            ->key('amount', v::numeric()->length(null, 3))
            ->key('payment_channel', v::stringType()->length(null, 1), false)
            ->key('multiple_method_page', v::intVal()->in([0, 1]), false)
            ->key('back_url', v::url(), false)
            ->key('redirect_url', v::url())
            ->key('notify_url', v::url())
            // ->key('signature', v::stringType()->length(null, 128))
            ->key('payer_id', v::stringType()->length(null, 100), false)
            ->key('payer_email', v::stringType()->length(null, 45), false)
            ->key('merchant_reference', v::stringType()->length(null, 100), false)
            ->key('locale', v::stringType()->in([
                'en',
                'id',
                'es',
                'fr',
                'de',
            ]), false)
            ->key('bill_to_forename', v::stringType()->length(null, 60), false)
            ->key('bill_to_surname', v::stringType()->length(null, 60), false)
            ->key('bill_to_address_city', v::stringType()->length(null, 50), false)
            ->key('bill_to_address_line1', v::stringType()->length(null, 60), false)
            ->key('bill_to_address_line2', v::stringType()->length(null, 60), false)
            ->key('bill_to_address_country', v::countryCode(), false)
            ->key('bill_to_address_state', v::stringType()->length(null, 2), false)
            ->key('bill_to_address_postal_code', v::stringType()->length(null, 10), false)
            ->key('bill_to_phone', v::stringType()->length(null, 15), false)
            ->key('ship_to_forename', v::stringType()->length(null, 60), false)
            ->key('ship_to_surname', v::stringType()->length(null, 60), false)
            ->key('ship_to_address_city', v::stringType()->length(null, 50), false)
            ->key('ship_to_address_line1', v::stringType()->length(null, 60), false)
            ->key('ship_to_address_line2', v::stringType()->length(null, 60), false)
            ->key('ship_to_address_country', v::countryCode(), false)
            ->key('ship_to_address_state', v::stringType()->length(null, 2), false)
            ->key('ship_to_address_postal_code', v::stringType()->length(null, 10), false)
            ->key('ship_to_phone', v::stringType()->length(null, 15), false)
            ->key('installment_tenor_month', v::numeric(), false)
            ->key('store_code', v::stringType()->length(null, 45), false)
            ->key('card_no', v::stringType()->length(null, 19), false)
            ->key('exp_date', v::numeric(), false)
            ->key('cvv2', v::numeric(), false)
            ->key('payer_name', v::stringType()->length(null, 45), false)
            ->key('bin_filter_code', v::stringType()->length(null, 50), false)
            ->key('token_mod', v::stringType()->in(['0', '1']), false)
            ->key('token_mod_id', v::stringType()->length(null, 100), false)
            ->key('uatp', v::stringType(), false)
            ->key('merchant_data1', v::stringType()->length(null, 32), false)
            ->key('merchant_private_data', v::stringType(), false)
        ;

        $validator->check($data);
        $data['mid'] = $this->mid;


        return [];
    }

    private function init()
    {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => $this->isProduction ? self::PRODUCTION_BASE_URL : self::TEST_BASE_URL,
        ]);
    }

    private function getSignature()
    {

    }
}

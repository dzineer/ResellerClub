<?php

namespace Tests\Unit\Orders\Domains\Responses;

use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;
use ResellerClub\Action;
use ResellerClub\Exceptions\FeatureNotAvailableException;
use ResellerClub\Orders\Domains\Responses\PrivacyProtectionOrderResponse;
use ResellerClub\Orders\Domains\Responses\RenewalResponse;

class RenewalResponseTest extends TestCase
{
    /**
     * @var RenewalResponse
     */
    private $response;

    protected function setUp()
    {
        parent::setUp();

        $this->response = new RenewalResponse([
            'actiontypedesc'          => 'Renewal of yesterday.co.in for 1 year',
            'unutilisedsellingamount' => '-7.910',
            'sellingamount'           => '-7.910',
            'entityid'                => '85547813',
            'actionstatus'            => 'Success',
            'privacydetails'          => [
                'entityid' => '85547813',
                'status'   => 'error',
                'error'    => 'Privacy Protection Service not available.',
            ],
            'status'                  => 'Success',
            'eaqid'                   => '524620696',
            'customerid'              => '17824872',
            'description'             => 'yesterday.co.in',
            'actiontype'              => 'RenewDomain',
            'invoiceid'               => '88713188',
            'sellingcurrencysymbol'   => 'GBP',
            'actionstatusdesc'        => 'Domain renewed successfully',
        ]);
    }

    public function testItCanOrderId()
    {
        $this->assertEquals(85547813, $this->response->orderId());
    }

    public function testItCanGetDescription()
    {
        $this->assertEquals('yesterday.co.in', $this->response->description());
    }

    public function testItCanGetAction()
    {
        $action = $this->response->action();
        $this->assertInstanceOf(Action::class, $action);
        $this->assertEquals(524620696, $action->id());
        $this->assertEquals('RenewDomain', $action->type());
        $this->assertEquals('Renewal of yesterday.co.in for 1 year', $action->typeDescription());
        $this->assertEquals('Success', $action->status());
        $this->assertEquals('Domain renewed successfully', $action->statusDescription());
    }

    public function testItCanGetInvoiceId()
    {
        $this->assertEquals(88713188, $this->response->invoiceId());
    }

    public function testItCanGetSellingCurrencySymbol()
    {
        $this->assertEquals('GBP', $this->response->sellingCurrencySymbol());
    }

    public function testItCanGetSellingCurrency()
    {
        $this->assertInstanceOf(Currency::class, $this->response->sellingCurrency());
    }

    public function testItCanGetSellingAmount()
    {
        $this->assertInstanceOf(Money::class, $this->response->sellingAmount());
        $this->assertEquals(-791, $this->response->sellingAmount()->getAmount());
    }

    public function testItCanGetUnutilisedSellingAmount()
    {
        $this->assertInstanceOf(Money::class, $this->response->unutilisedSellingAmount());
        $this->assertEquals(-791, $this->response->unutilisedSellingAmount()->getAmount());
    }

    public function testItCanGetCustomerId()
    {
        $this->assertEquals(17824872, $this->response->customerId());
    }

    public function testItCanGetPrivacyProtectionDetails()
    {
        $response = new RenewalResponse([
            'privacydetails'          => [
                'actiontypedesc'          => 'Purchase of Privacy Protection Service for domain wibble-wibble.net',
                'unutilisedsellingamount' => '0.000',
                'sellingamount'           => '0.000',
                'entityid'                => '85575828',
                'actionstatus'            => 'Success',
                'status'                  => 'Success',
                'eaqid'                   => '524956079',
                'customerid'              => '17824872',
                'description'             => 'wibble-wibble.net',
                'actiontype'              => 'PurchasePrivacyProtection',
                'invoiceid'               => '88768089',
                'sellingcurrencysymbol'   => 'GBP',
                'actionstatusdesc'        => 'Operation completed successfully',
            ],
        ]);

        $this->assertInstanceOf(PrivacyProtectionOrderResponse::class, $response->privacyProtectionDetails());

        $this->expectException(FeatureNotAvailableException::class);
        $this->response->privacyProtectionDetails();
    }
}

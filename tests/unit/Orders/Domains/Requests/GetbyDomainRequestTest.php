<?php

namespace Tests\Unit\Orders\Domains\Requests;

use PHPUnit\Framework\TestCase;
use ResellerClub\Orders\BusinessEmails\Requests\RenewRequest;
use ResellerClub\Orders\Domains\DomainOrderDetailType;
use ResellerClub\Orders\Domains\Requests\GetByDomainRequest;
use ResellerClub\Orders\Domains\Requests\GetRequest;
use ResellerClub\Orders\Order;

class GetbyDomainRequestTest extends TestCase
{
    /**
     * @var DomainOrderDetailType
     */
    private $domainOrderDetailType;

    /**
     * @var RenewRequest
     */
    private $request;

    protected function setUp()
    {
        parent::setUp();

        $this->domainOrderDetailType = DomainOrderDetailType::all();

        $this->request = new GetByDomainRequest(
            $domain = 'some-domain.co.uk',
            $this->domainOrderDetailType
        );
    }

    public function testItCanGetOrderId()
    {
        $this->assertEquals('some-domain.co.uk', $this->request->domain());
    }

    public function testItCanGetOrderDetailType()
    {
        $this->assertInstanceOf(DomainOrderDetailType::class, $this->request->orderDetailType());
        $this->assertEquals((string) $this->domainOrderDetailType, $this->request->orderDetailType());
    }
}

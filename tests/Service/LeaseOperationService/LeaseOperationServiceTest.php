<?php

namespace SlaveMarket\Service\LeaseOperationService;

use PHPUnit\Framework\TestCase;
use SlaveMarket\Lease\LeaseContract;
use SlaveMarket\Lease\LeaseRequest;
use SlaveMarket\MastersRepository;
use SlaveMarket\Service\PriceCalculatorService\PriceCalculatorService;
use SlaveMarket\SlavesRepository;

class LeaseOperationServiceTest extends TestCase
{

    public function test_Lease()
    {
        //arrange

        {
            $leaseRequest = new LeaseRequest();
            $leaseRequest->masterId = 1;
            $leaseRequest->slaveId = 1;
            $leaseRequest->timeFrom = '2017-01-01 01:30:00';
            $leaseRequest->timeTo = '2017-01-01 02:01:00';


            $mastersRepository = $this->createMock(MastersRepository::class);
            $slavesRepository = $this->createMock(SlavesRepository::class);
            $calculator = $this->createMock(PriceCalculatorService::class);

            $leaseOperationService = new LeaseOperationService($mastersRepository, $slavesRepository, $calculator);
        }

        //act
        $leaseContract = $leaseOperationService->lease($leaseRequest);

        //assert
        $this->assertInstanceOf(LeaseContract::class, $leaseContract);
    }
}

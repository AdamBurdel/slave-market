<?php

namespace SlaveMarket\Service\LeaseVerificationService;

use PHPUnit\Framework\TestCase;
use SlaveMarket\Lease\ILeaseContractsRepository;
use SlaveMarket\Lease\LeaseRequest;
use SlaveMarket\Lease\LeaseResponse;
use SlaveMarket\LeaseRules\SlaveAvailabilityRule;
use SlaveMarket\LeaseRules\SlaveMaxHoursRule;

class LeaseVerificationServiceTest extends TestCase
{

    public function test_IsAvailable()
    {
        //arrange
        {
            //Мок Правил доступности раба.
            $slaveAvailabilityRuleMock = $this->createMock(SlaveAvailabilityRule::class);
            $slaveAvailabilityRuleMock->method('isSlaveAvailable')->willReturn(true);

            //Мок правил максимума рабочей нормы
            $slaveMaxHoursRule = $this->createMock(SlaveMaxHoursRule::class);
            $slaveMaxHoursRule->method('isMaxHoursNotExceed')->willReturn(true);

            //Мок репозитория контрактов
            $leaseContractsRepository = $this->createMock(ILeaseContractsRepository::class);

            $leaseVerificationService = new LeaseVerificationService(
                $slaveAvailabilityRuleMock,
                $leaseContractsRepository,
                $slaveMaxHoursRule
            );

            $leaseRequest = new LeaseRequest();
            $leaseRequest->masterId = 1;
            $leaseRequest->slaveId = 1;
            $leaseRequest->timeFrom = '2017-01-01 01:30:00';
            $leaseRequest->timeTo = '2017-01-01 02:01:00';
        }

        //act
        $response = $leaseVerificationService->isAvailable($leaseRequest);

        $this->assertInstanceOf(LeaseResponse::class, $response);
        $this->assertEmpty($response->getErrors());
    }
}

<?php

namespace SlaveMarket\Service\LeaseVerificationService;

use PHPUnit\Framework\TestCase;
use SlaveMarket\Lease\ILeaseContractsRepository;
use SlaveMarket\Lease\LeaseRequest;
use SlaveMarket\LeaseRules\SlaveAvailabilityRule;
use SlaveMarket\LeaseRules\SlaveMaxHoursRule;

class LeaseVerificationServiceTest extends TestCase
{

    public function testIsAvailable()
    {
        //arrange
        {
            //Мок Правил доступности раба.
            $slaveAvailabilityRuleMock = $this->createMock(SlaveAvailabilityRule::class)->method(
                'isSlaveAvailable'
            )->willReturn(true);

            //Мок правил максимума рабочей нормы
            $slaveMaxHoursRule = $this->createMock(SlaveMaxHoursRule::class)->method('isMaxHoursNotExceed')->willReturn(
                true
            );

            //Мок репозитория контрактов
            $leaseContractsRepository = $this->createMock(ILeaseContractsRepository::class)->method(
                'isSlaveAvailable'
            )->willReturn(true);

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
        $leaseVerificationService->isAvailable($leaseRequest);

        $this->assertTrue(true);
    }
}

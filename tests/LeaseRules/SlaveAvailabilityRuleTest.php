<?php

namespace SlaveMarket\LeaseRules;

use Exception;
use SlaveMarket\AbstractTestCase;
use SlaveMarket\Lease\ILeaseContractsRepository;
use SlaveMarket\Lease\LeaseContract;
use SlaveMarket\Lease\LeaseHour;
use SlaveMarket\Master;
use SlaveMarket\Slave;

class SlaveAvailabilityRuleTest extends AbstractTestCase
{
    public function test_isSlaveAvailable_failed()
    {
        $this->expectException(Exception::class);
        // - arrange
        {
            //Раб и контракт на часы
            $leaseHour = new LeaseHour('2017-01-01 01');
            $slave = new Slave(1, 'Гарье', 70);
            $leaseContract = new LeaseContract(new Master(1, 'Боб', false), $slave, 80, [$leaseHour]);

            //стаб репо с занятым временем
            $leaseContractsRepository = $this->createMock(ILeaseContractsRepository::class);
            $leaseContractsRepository->method('getForSlave')->willReturn([$leaseContract]);
            $slaveAvailabilityRule = new SlaveAvailabilityRule();
        }

        // - act
        $verificationResult = $slaveAvailabilityRule->isSlaveAvailable(
            '2017-01-01 01:00:00',
            '2017-01-01 02:00:00',
            $leaseContractsRepository,
            $slave->getId()
        );

        // - assert
        $this->assertFalse($verificationResult);
    }

    public function test_isSlaveAvailable_successful()
    {
        // - arrange
        {
            //стаб репо с незанятым временем
            $leaseContractsRepository = $this->createMock(ILeaseContractsRepository::class);
            $leaseContractsRepository->method('getForSlave')->willReturn([]);
            $slaveAvailabilityRule = new SlaveAvailabilityRule();
        }

        // - act
        $validationResult = $slaveAvailabilityRule->isSlaveAvailable(
            '2017-01-01 01:00:00',
            '2017-01-01 02:00:00',
            $leaseContractsRepository,
            1
        );

        // - assert
        $this->assertTrue($validationResult);
    }
}

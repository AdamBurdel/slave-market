<?php

namespace SlaveMarket\LeaseRules;

use Exception;
use PHPUnit\Framework\TestCase;
use SlaveMarket\Lease\ILeaseContractsRepository;
use SlaveMarket\Lease\LeaseContract;
use SlaveMarket\Lease\LeaseHour;
use SlaveMarket\Master;
use SlaveMarket\Slave;

class SlaveMaxHoursRuleTest extends TestCase
{

    public function test_isMaxHoursNotExceed_multipleDaysFailed()
    {
        $this->expectException(Exception::class);
        // - arrange
        {
            //Раб и контракт на часы
            $leaseHour = new LeaseHour('2017-01-01 01');
            $leaseHour2 = new LeaseHour('2017-01-01 02');
            $leaseHour3 = new LeaseHour('2017-01-01 03');
            $slave = new Slave(1, 'Гарье', 70);
            $leaseContract = new LeaseContract(
                new Master(1, 'Боб', false),
                $slave,
                80,
                [$leaseHour, $leaseHour2, $leaseHour3]
            );

            //стаб репо с занятым временем
            $leaseContractsRepository = $this->createMock(ILeaseContractsRepository::class);
            $leaseContractsRepository->method('getForSlave')->willReturn([$leaseContract]);
            $slaveMaxHoursRule = new SlaveMaxHoursRule();
        }

        // - act

        //Проверяем при кол-ве меньше рабочих часов в общем
        $ruleVerificationResult = $slaveMaxHoursRule->isMaxHoursNotExceed(
            '2017-01-01 04:00:00',
            '2017-01-03 20:00:00',
            $leaseContractsRepository,
            $slave->getId()
        );
    }

    public function test_isMaxHoursNotExceed_multipleDaysSucceed()
    {
        // - arrange
        {
            //Раб и контракт на часы
            $leaseHour = new LeaseHour('2017-01-01 01');
            $leaseHour2 = new LeaseHour('2017-01-01 02');
            $leaseHour3 = new LeaseHour('2017-01-01 03');
            $slave = new Slave(1, 'Гарье', 70);
            $leaseContract = new LeaseContract(
                new Master(1, 'Боб', false),
                $slave,
                80,
                [$leaseHour, $leaseHour2, $leaseHour3]
            );

            //стаб репо с занятым временем
            $leaseContractsRepository = $this->createMock(ILeaseContractsRepository::class);
            $leaseContractsRepository->method('getForSlave')->willReturn([$leaseContract]);
            $slaveMaxHoursRule = new SlaveMaxHoursRule();
        }

        // - act
        //Проверяем при меньше 16-ти часов в общем
        $ruleVerificationResult = $slaveMaxHoursRule->isMaxHoursNotExceed(
            '2017-01-01 18:00:00',
            '2017-01-03 16:00:00',
            $leaseContractsRepository,
            $slave->getId()
        );

        // - assert
        $this->assertTrue($ruleVerificationResult);
    }

    public function test_isMaxHoursNotExceed_oneDayFailed()
    {
        $this->expectException(Exception::class);
        // - arrange
        {
            //Раб и контракт на часы
            $leaseHour = new LeaseHour('2017-01-01 01');
            $leaseHour2 = new LeaseHour('2017-01-01 02');
            $leaseHour3 = new LeaseHour('2017-01-01 03');
            $slave = new Slave(1, 'Гарье', 70);
            $leaseContract = new LeaseContract(
                new Master(1, 'Боб', false),
                $slave,
                80,
                [$leaseHour, $leaseHour2, $leaseHour3]
            );

            //стаб репо с занятым временем
            $leaseContractsRepository = $this->createMock(ILeaseContractsRepository::class);
            $leaseContractsRepository->method('getForSlave')->willReturn([$leaseContract]);
            $slaveMaxHoursRule = new SlaveMaxHoursRule();
        }

        // - act
        //Проверяем при больше 16-ти часов в общем
        $ruleVerificationResult = $slaveMaxHoursRule->isMaxHoursNotExceed(
            '2017-01-01 04:00:00',
            '2017-01-01 21:00:00',
            $leaseContractsRepository,
            $slave->getId()
        );
    }

    public function test_isMaxHoursNotExceed_oneDaySucceed()
    {
        // - arrange
        {
            //Раб и контракт на часы
            $leaseHour = new LeaseHour('2017-01-01 01');
            $leaseHour2 = new LeaseHour('2017-01-01 02');
            $leaseHour3 = new LeaseHour('2017-01-01 03');
            $slave = new Slave(1, 'Гарье', 70);
            $leaseContract = new LeaseContract(
                new Master(1, 'Боб', false),
                $slave,
                80,
                [$leaseHour, $leaseHour2, $leaseHour3]
            );

            //стаб репо с занятым временем
            $leaseContractsRepository = $this->createMock(ILeaseContractsRepository::class);
            $leaseContractsRepository->method('getForSlave')->willReturn([$leaseContract]);
            $slaveMaxHoursRule = new SlaveMaxHoursRule();
        }

        // - act
        //Проверяем при больше 16-ти часов в общем
        $ruleVerificationResult = $slaveMaxHoursRule->isMaxHoursNotExceed(
            '2017-01-01 04:00:00',
            '2017-01-01 08:00:00',
            $leaseContractsRepository,
            $slave->getId()
        );

        // - assert
        $this->assertTrue($ruleVerificationResult);
    }
}

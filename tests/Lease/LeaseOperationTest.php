<?php

namespace SlaveMarket\Lease;

use SlaveMarket\AbstractTestCase;
use SlaveMarket\Master;
use SlaveMarket\Service\LeaseOperationService\FakeLeaseOperationService;
use SlaveMarket\Service\LeaseVerificationService\LeaseVerificationService;
use SlaveMarket\Slave;

/**
 * Тесты операции аренды раба
 *
 * @package SlaveMarket\Lease
 */
class LeaseOperationTest extends AbstractTestCase
{

    /**
     * Если раб занят, то арендовать его не получится
     */
//    public function test_periodIsBusy_failedWithOverlapInfo()
//    {
//        // -- Arrange
//        {
//            $leaseOperationService = new FakeLeaseOperationService();
//            $leaseValidationService = new LeaseValidationService();
//            // Хозяева
//            $master1    = new Master(1, 'Господин Боб');
//            $master2    = new Master(2, 'сэр Вонючка');
//            $masterRepo = $this->makeFakeMasterRepository($master1, $master2);
//
//            // Раб
//            $slave1    = new Slave(1, 'Уродливый Фред', 20);
//            $slaveRepo = $this->makeFakeSlaveRepository($slave1);
//
//            // Договор аренды. 1й хозяин арендовал раба
//            $leaseContract1 = new LeaseContract($master1, $slave1, 80, [
//                new LeaseHour('2017-01-01 00'),
//                new LeaseHour('2017-01-01 01'),
//                new LeaseHour('2017-01-01 02'),
//                new LeaseHour('2017-01-01 03'),
//            ]);
//
//            // Stub репозитория договоров
//            $contractsRepo = $this->prophesize(ILeaseContractsRepository::class);
//            $contractsRepo
//                ->getForSlave($slave1->getId(), '2017-01-01', '2017-01-01')
//                ->willReturn([$leaseContract1]);
//
//            // Запрос на новую аренду. 2‑й хозяин выбрал занятое время
//            $leaseRequest           = new LeaseRequest();
//            $leaseRequest->masterId = $master2->getId();
//            $leaseRequest->slaveId  = $slave1->getId();
//            $leaseRequest->timeFrom = '2017-01-01 01:30:00';
//            $leaseRequest->timeTo   = '2017-01-01 02:01:00';
//
//            // Операция аренды
//            $leaseOperation = new LeaseOperation($contractsRepo->reveal(), $masterRepo, $slaveRepo, $leaseOperationService, $leaseValidationService);
//        }
//
//        // -- Act
//        $response = $leaseOperation->run($leaseRequest);
//
//        // -- Assert
//        $expectedErrors = ['Ошибка. Раб #1 "Уродливый Фред" занят. Занятые часы: "2017-01-01 01", "2017-01-01 02"'];
//
//        $this->assertArraySubset($expectedErrors, $response->getErrors());
//        $this->assertNull($response->getLeaseContract());
//    }

    /**
     * Если раб бездельничает, то его легко можно арендовать
     */
    public function test_idleSlave_successfullyLeased()
    {
        // -- Arrange
        {
            $leaseOperationService = new FakeLeaseOperationService();

            // Хозяева
            $master1 = new Master(1, 'Господин Боб');

            // Раб
            $slave1 = new Slave(1, 'Уродливый Фред', 20);

            //stub репо для контрактов
            $leaseContractRepository = $this->makeFakeLeaseContractRepository($slave1);
            $leaseValidationService = $this->createMock(LeaseVerificationService::class);

            // Запрос на новую аренду
            $leaseRequest = new LeaseRequest();
            $leaseRequest->masterId = $master1->getId();
            $leaseRequest->slaveId = $slave1->getId();
            $leaseRequest->timeFrom = '2017-01-01 01:30:00';
            $leaseRequest->timeTo = '2017-01-01 02:01:00';

            // Операция аренды
            $leaseOperation = new LeaseOperation($leaseOperationService, $leaseValidationService);
        }

        // -- Act
        $response = $leaseOperation->run($leaseRequest);

        // -- Assert
        $this->assertEmpty($response->getErrors());
//        $this->assertInstanceOf(LeaseContract::class, $response->getLeaseContract());
//        $this->assertEquals(40, $response->getLeaseContract()->price);
    }
}
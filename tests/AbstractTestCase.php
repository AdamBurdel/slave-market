<?php

namespace SlaveMarket;

use PHPUnit\Framework\TestCase;
use SlaveMarket\Lease\ILeaseContractsRepository;
use SlaveMarket\Lease\LeaseContract;
use SlaveMarket\Lease\LeaseHour;

class AbstractTestCase extends TestCase
{
    /**
     * Stub репозитория хозяев
     *
     * @param Master[] ...$masters
     * @return IMastersRepository
     */
    protected function makeFakeMasterRepository(...$masters): IMastersRepository
    {
        $mastersRepository = $this->prophesize(IMastersRepository::class);
        foreach ($masters as $master) {
            $mastersRepository->getById($master->getId())->willReturn($master);
        }

        return $mastersRepository->reveal();
    }

    /**
     * Stub репозитория рабов
     *
     * @param Slave[] ...$slaves
     * @return ISlavesRepository
     */
    protected function makeFakeSlaveRepository(...$slaves): ISlavesRepository
    {
        $slavesRepository = $this->prophesize(ISlavesRepository::class);
        foreach ($slaves as $slave) {
            $slavesRepository->getById($slave->getId())->willReturn($slave);
        }

        return $slavesRepository->reveal();
    }

    //stub репозитория контрактов
    protected function makeFakeLeaseContractRepository(
        Slave $slave
    ): ILeaseContractsRepository {
        $slavesRepository = $this->prophesize(ILeaseContractsRepository::class);
        $leaseHour = new LeaseHour('2017-01-01 01');
        $leaseContract = new LeaseContract(new Master(1, 'Боб', false), $slave, 80, [$leaseHour]);
        $slavesRepository->getForSlave($slave->getId(), '2017-01-01 00:00:00', '2017-01-01 01:00:00')->willReturn(
            [$leaseContract]
        );

        return $slavesRepository->reveal();
    }
}
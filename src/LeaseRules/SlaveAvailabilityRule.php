<?php

namespace SlaveMarket\LeaseRules;

use Exception;
use SlaveMarket\Lease\ILeaseContractsRepository;

class SlaveAvailabilityRule
{
    /**
     * @throws Exception
     */
    public function isSlaveAvailable(
        string $dateFrom,
        string $dateTo,
        ILeaseContractsRepository $leaseContractsRepository,
        int $slaveId
    ): bool {
        if (empty($leaseContractsRepository->getForSlave($slaveId, $dateFrom, $dateTo))) {
            return true;
        }

        throw new Exception('Ошибка: Время арендовано другим хозяином');
    }
}
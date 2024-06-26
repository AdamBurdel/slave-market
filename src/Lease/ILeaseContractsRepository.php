<?php

namespace SlaveMarket\Lease;

/**
 * Репозиторий договоров аренды
 *
 * @package SlaveMarket\Lease
 */
interface ILeaseContractsRepository
{
    /**
     * Возвращает список договоров аренды для раба, в которых заняты часы из указанного периода
     *
     * @param int $slaveId
     * @param string $dateFrom Y-m-d H
     * @param string $dateTo Y-m-d H
     * @return LeaseContract[]
     */
    public function getForSlave(int $slaveId, string $dateFrom, string $dateTo) : array;
}
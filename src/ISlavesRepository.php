<?php

namespace SlaveMarket;

/**
 * Репозиторий рабов
 *
 * @package SlaveMarket
 */
interface ISlavesRepository
{
    /**
     * Возвращает раба по его id
     *
     * @param int $id
     * @return Slave
     */
    public function getById(int $id): Slave;
}
<?php

namespace SlaveMarket\Lease;

use DateInterval;
use DatePeriod;
use DateTime;
use SlaveMarket\Master;
use SlaveMarket\Slave;

/**
 * Договор аренды
 *
 * @package SlaveMarket\Lease
 */
class LeaseContract
{
    /** @var Master Хозяин */
    public $master;

    /** @var Slave Раб */
    public $slave;

    /** @var float Стоимость */
    public $price = null;

    /** @var LeaseHour[] Список арендованных часов */
    public $leasedHours = [];

    public function __construct(Master $master, Slave $slave, ?float $price, array $leasedHours)
    {
        $this->master = $master;
        $this->slave = $slave;
        $this->price = $price;
        $this->leasedHours = $leasedHours;
    }

    public function getLeasedHoursByDay(DateTime $day)
    {
        $leasedHours = [];

        foreach ($this->leasedHours as $leasedHour) {
            if ($day->format('Y-m-d') === $leasedHour->getDate()) {
                $leasedHours[] = $leasedHour;
            }
        }

        return $leasedHours;
    }

    public function addHours(DateTime $dateFrom, DateTime $dateTo)
    {
        $periodInHours = new DatePeriod(
            $dateFrom,
            new DateInterval('PT1H'),
            $dateTo
        );

        $hours = [];
        foreach ($periodInHours as $datetime) {
            $hours[] = $datetime;
        }

        $leasedHours = [];
        foreach ($hours as $hour) {
            $leasedHours[] = new LeaseHour($hour->format('Y-m-d H'));
        }

        $this->leasedHours = $leasedHours;
    }
}

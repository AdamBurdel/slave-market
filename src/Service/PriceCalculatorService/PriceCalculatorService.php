<?php

namespace SlaveMarket\Service\PriceCalculatorService;

use DateInterval;
use DatePeriod;
use DateTime;
use SlaveMarket\Lease\LeaseContract;

class PriceCalculatorService implements PriceCalculatorInterface
{

    const MAX_HOURS_TO_PAY = 16;

    public function calculateLeaseOperationPriceForContract(
        LeaseContract $leaseContract,
        float $pricePerHour
    ): float {

        $firstHour = reset($leaseContract->leasedHours);
        $lastHour = end($leaseContract->leasedHours);
        $period = new DatePeriod(
            DateTime::createFromFormat('Y-m-d H', $firstHour->getDateTime()->format('Y-m-d 00')),
            new DateInterval('P1D'),
            DateTime::createFromFormat('Y-m-d H', $lastHour->getDateTime()->format('Y-m-d 23'))
        );

        $days = [];
        foreach ($period as $datetime) {
            $days[] = $datetime;
        }

        $hoursSum = 0;
        foreach ($days as $day) {
            $hours = count($leaseContract->getLeasedHoursByDay($day));
            if ($hours > self::MAX_HOURS_TO_PAY) {
                $hours = self::MAX_HOURS_TO_PAY;
            }
            $hoursSum += $hours;
        }

        return (float)$hoursSum * $pricePerHour;
    }
}
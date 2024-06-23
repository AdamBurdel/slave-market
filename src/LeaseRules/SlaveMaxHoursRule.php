<?php

namespace SlaveMarket\LeaseRules;

use DateInterval;
use DatePeriod;
use DateTime;
use Exception;
use SlaveMarket\Lease\ILeaseContractsRepository;

class SlaveMaxHoursRule
{
    const MAX_WORKING_HOURS = 16;
    const DAY_START_TIME = '00';
    const DAY_END_TIME = '23';

    /**
     * @throws Exception
     */
    public function isMaxHoursNotExceed(
        string $dateFrom,
        string $dateTo,
        ILeaseContractsRepository $leaseContractsRepository,
        int $slaveId
    ) {
        $dateFrom = new DateTime($dateFrom);
        $dateTo = new DateTime($dateTo);

        //Получаем кол-во дней для аренды по датам

        $firstDay = DateTime::createFromFormat('Y-m-d H', $dateFrom->format('Y-m-d 00'));
        $lastDay = DateTime::createFromFormat('Y-m-d H', $dateTo->format('Y-m-d 23'));

        $period = new DatePeriod(
            $firstDay,
            new DateInterval('P1D'),
            $lastDay
        );

        $days = [];
        foreach ($period as $datetime) {
            $days[] = $datetime;
        }

        //Первый день диапазона
        $first = reset($days);

        //Если диапазон в рамках одного дня
        if (
            (count($days) === 1 &&
                ($dateFrom->format('H') !== self::DAY_START_TIME) &&
                ($dateTo->format('H') !== self::DAY_END_TIME))
        ) {

            //Получаем контракты для диапазона
            $contracts = $leaseContractsRepository->getForSlave(
                $slaveId,
                $dateFrom->format('Y-m-d H'),
                $dateTo->format('Y-m-d H')
            );

            //Получаем кол-во уже арендованых часов
            $leasedHours = $this->getLeaseHoursForDay($first, $contracts);

            //Проверяем не превышаем ли норму труда
            $workingHours = $dateFrom->diff($dateTo)->h + $leasedHours;

            if ($workingHours > self::MAX_WORKING_HOURS) {
                throw new Exception('Ошибка: В Выбранном диапазоне превышается норма труда');
            }

            return true;
        }


        $last = end($days);

        //Получаем контракты для всего диапазона дней
        $contracts = $leaseContractsRepository->getForSlave(
            $slaveId,
            $first->format('Y-m-d'),
            $last->format('Y-m-d')
        );

        //Получаем контракты для первого дня, если он не начинается с первого часа рабочего дня
        // и проверяем не превышаем ли норму труда
        if ($first->format('H') !== self::DAY_START_TIME) {
            $firstDayWorkingHours = $this->getLeaseHoursForDay($first, $contracts);

            $firstDayEnd = DateTime::createFromFormat('Y-m-d H', $first->format('Y-m-d '.self::DAY_END_TIME));
            $workingHours = $first->diff($firstDayEnd)->h + $firstDayWorkingHours;

            if ($workingHours > self::MAX_WORKING_HOURS) {
                throw new Exception('Ошибка: В Выбранном диапазоне превышается норма труда');
            }
        }

        //Получаем контракты для последнего дня, если он не заканчивается в последний час рабочего дня
        // и проверяем не превышаем ли норму труда
        if ($last->format('H') !== self::DAY_END_TIME) {
            $lastDayWorkingHours = $this->getLeaseHoursForDay($last, $contracts);
            $lastDayStart = DateTime::createFromFormat('Y-m-d H', $last->format('Y-m-d '.self::DAY_START_TIME));
            $workingHours = $lastDayStart->diff($dateTo)->h + $lastDayWorkingHours;
            if ($workingHours > self::MAX_WORKING_HOURS) {
                throw new Exception('Ошибка: В Выбранном диапазоне превышается норма труда');
            }
        }

        return true;
    }

    private function getLeaseHoursForDay(DateTime $day, array $contracts): int
    {
        //Собираем массив часов
        $leasedHours = [];
        foreach ($contracts as $contract) {
            $leasedHours[] = count($contract->getLeasedHoursByDay($day));
        }

        return array_sum($leasedHours);
    }

}
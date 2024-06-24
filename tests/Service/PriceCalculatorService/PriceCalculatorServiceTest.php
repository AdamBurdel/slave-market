<?php

namespace SlaveMarket\Service\PriceCalculatorService;

use DateTime;
use PHPUnit\Framework\TestCase;
use SlaveMarket\Lease\LeaseContract;
use SlaveMarket\Master;
use SlaveMarket\Slave;

class PriceCalculatorServiceTest extends TestCase
{

    public function test_CalculateLeaseOperationPriceRangeInOneDay()
    {
        //arrange
        {
            $pricePerHour = 80;

            $leaseContract = new LeaseContract(
                new Master(1, 'Билли'),
                new Slave(1, 'Георг', $pricePerHour),
                null,
                []
            );
            $leaseContract->addHours(new DateTime('2017-01-01 04:00:00'), new DateTime('2017-01-01 06:00:00'));
            $priceCalculator = new PriceCalculatorService();

            $expectedPrice = 160.0;
        }

        //act
        $leaseContract->price = $priceCalculator->calculateLeaseOperationPriceForContract(
            $leaseContract,
            $pricePerHour
        );


        //arrange
        $this->assertSame($expectedPrice, $leaseContract->price);
    }

    public function test_CalculateLeaseOperationPriceRangeMultipleDays()
    {
        //arrange
        {
            $pricePerHour = 80;

            $leaseContract = new LeaseContract(
                new Master(1, 'Билли'),
                new Slave(1, 'Георг', $pricePerHour),
                null,
                []
            );
            $leaseContract->addHours(new DateTime('2017-01-01 12:00:00'), new DateTime('2017-01-02 06:00:00'));
            $priceCalculator = new PriceCalculatorService();

            $expectedPrice = 1440.0;
        }

        //act
        $leaseContract->price = $priceCalculator->calculateLeaseOperationPriceForContract(
            $leaseContract,
            $pricePerHour
        );


        //assert
        $this->assertSame($expectedPrice, $leaseContract->price);
    }
}

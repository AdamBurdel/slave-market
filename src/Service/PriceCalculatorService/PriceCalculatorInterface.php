<?php

namespace SlaveMarket\Service\PriceCalculatorService;

use SlaveMarket\Lease\LeaseContract;

interface PriceCalculatorInterface
{
    public function calculateLeaseOperationPriceForContract(LeaseContract $leaseContract, float $pricePerHour): float;
}
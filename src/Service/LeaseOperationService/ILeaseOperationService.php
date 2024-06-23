<?php

namespace SlaveMarket\Service\LeaseOperationService;

use SlaveMarket\Lease\LeaseContract;
use SlaveMarket\Lease\LeaseRequest;

interface ILeaseOperationService
{
    public function lease(LeaseRequest $leaseRequest): LeaseContract;
}
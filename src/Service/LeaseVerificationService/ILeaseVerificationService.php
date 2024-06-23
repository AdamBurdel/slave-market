<?php

namespace SlaveMarket\Service\LeaseVerificationService;

use SlaveMarket\Lease\LeaseRequest;
use SlaveMarket\Lease\LeaseResponse;

interface ILeaseVerificationService
{
    public function isAvailable(LeaseRequest  $leaseRequest): LeaseResponse;
}
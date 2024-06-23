<?php

namespace SlaveMarket\Service\LeaseOperationService;

use SlaveMarket\Lease\LeaseContract;
use SlaveMarket\Lease\LeaseRequest;
use SlaveMarket\Master;
use SlaveMarket\Slave;

class FakeLeaseOperationService implements ILeaseOperationService
{
    public function lease(LeaseRequest $leaseRequest): LeaseContract
    {
        return new LeaseContract(new Master(1, 'test'), new Slave(1, 'test', 80), 40, ['2017-01-01 01:30:00', '2017-01-01 01:30:00']);
    }
}
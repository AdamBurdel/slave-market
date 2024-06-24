<?php

namespace SlaveMarket\Lease;

use SlaveMarket\AbstractTestCase;
use SlaveMarket\Service\LeaseOperationService\LeaseOperationService;
use SlaveMarket\Service\LeaseVerificationService\LeaseVerificationService;

/**
 * Тесты операции аренды раба
 *
 * @package SlaveMarket\Lease
 */
class LeaseOperationTest extends AbstractTestCase
{
    public function test_run_()
    {
        //arrange
        {
            $leaseOperationService = $this->createMock(LeaseOperationService::class);
            $leaseVerificationService = $this->createMock(LeaseVerificationService::class);
            $leaseOperation = new LeaseOperation($leaseOperationService, $leaseVerificationService);

            $leaseResponse = $leaseOperation->run(new LeaseRequest());
        }

        $this->assertEmpty([]);
    }
}
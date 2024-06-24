<?php

namespace SlaveMarket\Lease;

use SlaveMarket\Service\LeaseOperationService\LeaseOperationService;
use SlaveMarket\Service\LeaseVerificationService\ILeaseVerificationService;
use SlaveMarket\Service\LeaseVerificationService\LeaseVerificationService;

/**
 * Операция "Арендовать раба"
 *
 * @package SlaveMarket\Lease
 */
class LeaseOperation
{
    /**
     * @var ILeaseVerificationService
     */
    private $leaseOperationService;
    /**
     * @var ILeaseVerificationService
     */
    private $leaseVerificationService;

    /**
     * LeaseOperation constructor.
     *
     * @param LeaseOperationService $leaseOperationService
     * @param LeaseVerificationService $leaseVerificationService
     */
    public function __construct(
        LeaseOperationService $leaseOperationService,
        LeaseVerificationService $leaseVerificationService
    ) {
        $this->leaseOperationService = $leaseOperationService;
        $this->leaseVerificationService = $leaseVerificationService;
    }

    /**
     * Выполнить операцию
     *
     * @param LeaseRequest $request
     * @return LeaseResponse
     */
    public function run(LeaseRequest $request): LeaseResponse
    {
        $leaseResponse = $this->leaseVerificationService->isAvailable($request);
        if (!empty($leaseResponse->getErrors())) {
            return $leaseResponse;
        }

        $leaseContract = $this->leaseOperationService->lease($request);
        $leaseResponse->setLeaseContract($leaseContract);

        return $leaseResponse;
    }
}
<?php

namespace SlaveMarket\Lease;

use SlaveMarket\IMastersRepository;
use SlaveMarket\ISlavesRepository;
use SlaveMarket\Service\LeaseOperationService\ILeaseOperationService;
use SlaveMarket\Service\LeaseVerificationService\ILeaseVerificationService;

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
    private $leaseValidationService;

    /**
     * LeaseOperation constructor.
     *
     * @param ILeaseContractsRepository $contractsRepo
     * @param IMastersRepository $mastersRepo
     * @param ISlavesRepository $slavesRepo
     */
    public function __construct(
        ILeaseOperationService $leaseOperationService,
        ILeaseVerificationService $leaseValidationService
    ) {
        $this->leaseOperationService = $leaseOperationService;
        $this->leaseValidationService = $leaseValidationService;
    }

    /**
     * Выполнить операцию
     *
     * @param LeaseRequest $request
     * @return LeaseResponse
     */
    public function run(LeaseRequest $request): LeaseResponse
    {

        $leaseResponse = $this->leaseValidationService->isAvailable($request);
        if (!empty($leaseResponse->getErrors())) {
            return $leaseResponse;
        }

        $leaseContract = $this->leaseOperationService->lease($request);
        $leaseResponse->setLeaseContract($leaseContract);

        return $leaseResponse;
    }
}
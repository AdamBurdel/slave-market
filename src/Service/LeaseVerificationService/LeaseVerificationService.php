<?php

namespace SlaveMarket\Service\LeaseVerificationService;

use SlaveMarket\Helper\DateTimeRoundHelper;
use SlaveMarket\Lease\ILeaseContractsRepository;
use SlaveMarket\Lease\LeaseRequest;
use SlaveMarket\Lease\LeaseResponse;
use SlaveMarket\LeaseRules\SlaveAvailabilityRule;
use SlaveMarket\LeaseRules\SlaveMaxHoursRule;
use SlaveMarket\LeaseRules\Validator\IValidator;


class LeaseVerificationService implements ILeaseVerificationService
{
    /**
     * @var SlaveAvailabilityRule
     */
    private $slaveAvailabilityService;

    /**
     * @var array
     */
    private $errors = [];
    /**
     * @var ILeaseContractsRepository
     */
    private $leaseContractsRepository;
    /**
     * @var SlaveMaxHoursRule
     */
    private $slaveMaxHoursRule;

    public function __construct(
        SlaveAvailabilityRule $availabilityRule,
        ILeaseContractsRepository $leaseContractsRepository,
        SlaveMaxHoursRule $maxHoursRule
    ) {
        $this->leaseContractsRepository = $leaseContractsRepository;
        $this->slaveAvailabilityService = $availabilityRule;
        $this->slaveMaxHoursRule = $maxHoursRule;
    }

    public function isAvailable(LeaseRequest $leaseRequest): LeaseResponse
    {
        $leaseRequest = $this->roundRequestTime($leaseRequest);
        $leaseResponse = new LeaseResponse();

        try {
            //Есть ли на это время занятые часы
            $this->slaveAvailabilityService->isSlaveAvailable(
                $leaseRequest->timeFrom,
                $leaseRequest->timeTo,
                $this->leaseContractsRepository,
                $leaseRequest->slaveId
            );

            //Проверить не превышает ли норму рабочих часов
            $this->slaveMaxHoursRule->isMaxHoursNotExceed(
                $leaseRequest->timeFrom,
                $leaseRequest->timeTo,
                $this->leaseContractsRepository,
                $leaseRequest->slaveId
            );


        } catch (\Exception $exception) {
            $leaseResponse->addError($exception->getMessage());
        }

        return $leaseResponse;
    }


    private function roundRequestTime(LeaseRequest $leaseRequest): LeaseRequest
    {
        $leaseRequest->timeFrom = DateTimeRoundHelper::roundHoursString($leaseRequest->timeFrom);
        $leaseRequest->timeTo = DateTimeRoundHelper::roundHoursString($leaseRequest->timeTo);

        return $leaseRequest;
    }

}
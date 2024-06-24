<?php

namespace SlaveMarket\Service\LeaseOperationService;

use SlaveMarket\Helper\DateTimeRoundHelper;
use SlaveMarket\Lease\LeaseContract;
use SlaveMarket\Lease\LeaseContractsRepository;
use SlaveMarket\Lease\LeaseRequest;
use SlaveMarket\MastersRepository;
use SlaveMarket\Service\PriceCalculatorService\PriceCalculatorInterface;
use SlaveMarket\Service\PriceCalculatorService\PriceCalculatorService;
use SlaveMarket\SlavesRepository;

class LeaseOperationService implements ILeaseOperationService
{
    /**
     * @var LeaseContractsRepository
     */
    private $leaseContractsRepository;
    /**
     * @var MastersRepository
     */
    private $mastersRepository;
    /**
     * @var SlavesRepository
     */
    private $slavesRepository;
    /**
     * @var PriceCalculatorInterface
     */
    private $priceCalculatorService;

    public function __construct(
        MastersRepository $mastersRepository,
        SlavesRepository $slavesRepository,
        PriceCalculatorService $priceCalculatorService
    ) {
        $this->mastersRepository = $mastersRepository;
        $this->slavesRepository = $slavesRepository;
        $this->priceCalculatorService = $priceCalculatorService;
    }

    public function lease(LeaseRequest $leaseRequest): LeaseContract
    {
        $master = $this->mastersRepository->getById($leaseRequest->masterId);
        $slave = $this->slavesRepository->getById($leaseRequest->slaveId);

        $dateFrom = DateTimeRoundHelper::roundHoursString($leaseRequest->timeTo);
        $dateTo = DateTimeRoundHelper::roundHoursString($leaseRequest->timeFrom);

        $leaseContract = new LeaseContract($master, $slave, 0, []);
        $leaseContract->addHours(new \DateTime($dateFrom), new \DateTime($dateTo));
        $leaseContract->price = $this->priceCalculatorService->calculateLeaseOperationPriceForContract(
            $leaseContract,
            $slave->getPricePerHour()
        );


        return $leaseContract;
    }
}
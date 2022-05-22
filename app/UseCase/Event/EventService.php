<?php

namespace App\UseCase\Event;

use App\Domain\Entity\Event;
use App\UseCase\Event\EventDepositDto;
use App\UseCase\Event\EventTransferDto;
use App\UseCase\Event\EventWithdrawDto;
use App\Domain\Repositories\EventRepositoryInterface;
use App\Domain\Repositories\AccountRepositoryInterface;

class  EventService implements EventServiceInterface
{
    private AccountRepositoryInterface $accountRepository;
    private EventRepositoryInterface $eventRepository;
    public function __construct(
        AccountRepositoryInterface $accountRepository,
        EventRepositoryInterface $eventRepository
    ) {
        $this->accountRepository = $accountRepository;
        $this->eventRepository = $eventRepository;
    }

    public function deposit(int $destinationId, float $amount): EventDepositDto
    {
        $account = $this->accountRepository->findOrCreate($destinationId);
        $accountUpdated = $this->accountRepository->updateBalance($account, $account->balance + $amount);

        $event = $this->eventRepository->create(new Event(
            destination: $account->id,
            amount: $amount,
            type: Event::TYPE_DEPOSIT,
        ));

        return new EventDepositDto($event->destination, $accountUpdated->balance);
    }

    public function withdraw(int $originId, float $amount): EventWithdrawDto|int
    {
        $account = $this->accountRepository->findById($originId);
        if (empty($account)) {
            return 0;
        }

        $accountUpdated = $this->accountRepository->updateBalance($account, $account->balance - $amount);

        $event = $this->eventRepository->create(new Event(
            origin: $accountUpdated->id,
            amount: $amount,
            type: Event::TYPE_WITHDRAW,
        ));

        return new EventWithdrawDto($event->origin, $accountUpdated->balance);
    }

    public function transfer(int $originId, int $destinationId, float $amount): EventTransferDto|int
    {
        $origin = $this->accountRepository->findById($originId);
        if (empty($origin)) {
            return 0;
        }

        $destination = $this->accountRepository->findOrCreate($destinationId);

        $originUpdated = $this->accountRepository->updateBalance($origin, $origin->balance - $amount);
        $destinationUpdated = $this->accountRepository->updateBalance($destination, $destination->balance + $amount);

        $this->eventRepository->create(new Event(
            origin: $originUpdated->id,
            destination: $destinationUpdated->id,
            amount: $amount,
            type: Event::TYPE_TRANSFER,
        ));

        return new EventTransferDto($originUpdated, $destinationUpdated);
    }
}

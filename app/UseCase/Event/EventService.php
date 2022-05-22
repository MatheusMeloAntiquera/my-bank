<?php

namespace App\UseCase\Event;

use App\Domain\Entity\Event;
use App\UseCase\Event\EventDepositDto;
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
    public function deposit(int $destination, float $amount): EventDepositDto
    {
        $account = $this->accountRepository->findOrCreate($destination);
        $newBalance = $account->balance + $amount;
        $this->accountRepository->updateBalance($account, $newBalance);

        $event = $this->eventRepository->create(new Event(
            destination: $account->id,
            amount: $amount,
            type: Event::TYPE_DEPOSIT,
        ));

        return new EventDepositDto($event->destination, $newBalance);
    }
}

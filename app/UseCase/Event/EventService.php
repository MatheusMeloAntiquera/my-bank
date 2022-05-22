<?php

namespace App\UseCase\Event;

use App\Domain\Entity\Event;
use App\UseCase\Event\EventDepositDto;
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
    public function deposit(int $destination, float $amount): EventDepositDto
    {
        $account = $this->accountRepository->findOrCreate($destination);
        $accountUpdated = $this->accountRepository->updateBalance($account, $account->balance + $amount);

        $event = $this->eventRepository->create(new Event(
            destination: $account->id,
            amount: $amount,
            type: Event::TYPE_DEPOSIT,
        ));

        return new EventDepositDto($event->destination, $accountUpdated->balance);
    }

    public function withdraw(int $origin, float $amount): EventWithdrawDto|int
    {
        $account = $this->accountRepository->findById($origin);
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
}

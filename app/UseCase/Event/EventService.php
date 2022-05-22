<?php

namespace App\UseCase\Event;

use App\Domain\Entity\Event;
use App\UseCase\Event\EventDepositDto;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Repositories\EventRepositoryInterface;
use App\Domain\Repositories\AccountRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class  EventService implements EventServiceInterface
{
    private UserRepositoryInterface $userRepository;
    private AccountRepositoryInterface $accountRepository;
    private EventRepositoryInterface $eventRepository;
    public function __construct(
        UserRepositoryInterface $userRepository,
        AccountRepositoryInterface $accountRepository,
        EventRepositoryInterface $eventRepository
    ) {
        $this->userRepository = $userRepository;
        $this->accountRepository = $accountRepository;
        $this->eventRepository = $eventRepository;
    }
    public function deposit(int $destinationId, float $amount): EventDepositDto
    {
        $user = $this->userRepository->findById($destinationId);

        if (empty($user)) {
            throw new NotFoundHttpException("User not found");
        }
        $account = $this->accountRepository->findOrCreate($user->account_id);

        $newBalance = $account->balance + $amount;
        $this->accountRepository->updateBalance($account, $newBalance);

        $event = $this->eventRepository->create(new Event(
            destination: $destinationId,
            amount: $amount,
            type: Event::TYPE_DEPOSIT,
        ));

        return new EventDepositDto($event->destination, $event->amount);
    }
}

<?php

namespace App\Infra\Http\Controllers;

use Exception;
use ReflectionClass;
use Illuminate\Http\Request;
use App\UseCase\Event\EventServiceInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class EventController extends Controller
{
    private EventServiceInterface $eventService;
    private $typeAllowed = [
        "deposit",
        "withdraw"
    ];
    public function __construct(EventServiceInterface $eventService)
    {
        $this->eventService = $eventService;
    }

    public function handleEvent(Request $request)
    {
        $this->request = $request;
        try {
            $type = $request->input('type');
            if (!in_array($type, $this->typeAllowed)) {
                throw new BadRequestException("The type is wrong, please enter a valid one", 400);
            }

            $statusCode = 201;
            $responseBody = [];
            switch ($type) {
                case "deposit":
                    $responseBody = $this->handleDeposit($request->input('destination'), (float) $request->input('amount'));
                    break;
                case "withdraw":
                    $responseBody = $this->handleWithdraw($request->input('origin'), (float) $request->input('amount'));
                    if ($responseBody === 0) {
                        $statusCode = 404;
                    }
                    break;
                default:
            }
            return response()
                ->json($responseBody, $statusCode);
        } catch (BadRequestException $e) {
            return response()
                ->json($e->getMessage(), 400);
        } catch (Exception $e) {
            return response()
                ->json("It was not possible to complete the event, try again later", 500);
        }
    }

    private function handleDeposit(?int $destination, ?float $amount)
    {
        if (empty($destination)) {
            throw new BadRequestException("The 'destination' not be empty", 400);
        }

        if (empty($amount)) {
            throw new BadRequestException("The 'amount' not be empty", 400);
        }

        $result = $this->eventService->deposit($destination, $amount);
        return $result->__toArray();
    }

    private function handleWithdraw(?int $origin, ?float $amount)
    {
        if (empty($origin)) {
            throw new BadRequestException("The 'origin' not be empty", 400);
        }

        if (empty($amount)) {
            throw new BadRequestException("The 'amount' not be empty", 400);
        }

        $result = $this->eventService->withdraw($origin, $amount);
        return $result === 0 ? $result : $result->__toArray();
    }
}

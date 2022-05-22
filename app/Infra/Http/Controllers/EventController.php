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
        try {
            $type = $request->input('type');
            if (!in_array($type, $this->typeAllowed)) {
                throw new BadRequestException("The type is wrong, please enter a valid one", 400);
            }
            $this->validateRequest($request, $type);
            $statusCode = 201;
            $responseBody = [];
            switch ($type) {
                case "deposit":
                    $responseBody = $this->eventService->deposit(
                        $request->input('destination'),
                        $request->input('amount')
                    )->__toArray();
                    break;
                case "withdraw":
                    $result = $this->eventService->withdraw(
                        $request->input('origin'),
                        $request->input('amount')
                    );
                    if ($result === 0) {
                        $statusCode = 404;
                        $responseBody = $result;
                        break;
                    }
                    $responseBody = $result->__toArray();
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

    private function validateRequest(Request $request, string $type)
    {
        if (($type == 'deposit' || $type == 'transfer') && empty($request->input('destination'))) {
            throw new BadRequestException("The 'destination' not be empty", 400);
        }

        if (($type == 'withdraw' || $type == 'transfer') && empty($request->input('origin'))) {
            throw new BadRequestException("The 'origin' not be empty", 400);
        }

        if (empty($request->input('amount'))) {
            throw new BadRequestException("The 'amount' not be empty", 400);
        }
    }
}

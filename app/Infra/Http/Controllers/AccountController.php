<?php

namespace App\Infra\Http\Controllers;

use Illuminate\Http\Request;
use App\UseCase\Account\AccountServiceInterface;

class AccountController extends Controller
{
    private AccountServiceInterface $accountService;
    public function __construct(AccountServiceInterface $accountService)
    {
        $this->accountService = $accountService;
    }

    public function getBalance(Request $request)
    {
        $accountId = (int) $request->query('account_id');

        if (empty($accountId) || !is_int($accountId)) {
            return response()
                ->json(["message" => "account_id not informed correctly"], 400);
        }

        $account = $this->accountService->findByAccountId($request->query('account_id'));
        if (empty($account)) {
            return response()
                ->json(0, 404);
        }

        return response()
            ->json($account->balance, 200);
    }
}

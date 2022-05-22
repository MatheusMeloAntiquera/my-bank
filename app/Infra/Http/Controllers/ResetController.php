<?php

namespace App\Infra\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class ResetController extends Controller
{
    public function reset()
    {
        Artisan::call('migrate:refresh', [
            '--force' => true,
        ]);

        return response('OK',200)->header('Content-Type', 'text/plain');
    }
}

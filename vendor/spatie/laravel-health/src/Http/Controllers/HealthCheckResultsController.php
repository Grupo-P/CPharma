<?php

namespace Spatie\Health\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Spatie\Health\Commands\RunHealthChecksCommand;
use Spatie\Health\Health;
use Spatie\Health\ResultStores\ResultStore;

class HealthCheckResultsController
{
    public function __invoke(Request $request, ResultStore $resultStore, Health $health): JsonResponse|View
    {
        if ($request->has('fresh')) {
            Artisan::call(RunHealthChecksCommand::class);
        }else{
            Artisan::call(RunHealthChecksCommand::class);
        }

        $checkResults = $resultStore->latestResults();

        return view('health::list', [
            'lastRanAt' => new Carbon($checkResults?->finishedAt),
            'checkResults' => $checkResults,
            'assets' => $health->assets(),
            'theme' => config('health.theme'),
        ]);
    }

    public function healthHistory(){
        $HealthHistories_result =  DB::table('health_check_result_history_items')->get();
        $HealthHistories = json_decode($HealthHistories_result, true);
        return view('core.health.index', compact('HealthHistories'));
    }
}

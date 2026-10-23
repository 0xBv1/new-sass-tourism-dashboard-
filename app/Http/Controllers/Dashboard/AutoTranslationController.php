<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AutoTranslationController extends Controller
{
    public function translate()
    {
        $model = Str::of(request('model'))->plural();

        $modelJob = '\App\Jobs\Translate' . $model . 'Job';

        if (class_exists($modelJob)) {
            $ids = (array)request('id', []);
            $message = 'Translation job is running in background now, Please wait';

            if (empty($ids)) {
                $modelJob::dispatch([]);
            } else {
                foreach ($ids as $id) {
                    $modelJob::dispatch([$id]);
                }
            }

            return response()->json([
                'message' => $message
            ]);
        }

        return response()
            ->json([
                'resource' => $modelJob,
                'message' => 'Translation not implemented for ' . $model
            ], Response::HTTP_BAD_REQUEST);
    }
}

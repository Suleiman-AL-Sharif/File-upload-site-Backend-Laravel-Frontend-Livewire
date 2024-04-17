<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
// use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LogRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        if ($request->method() == 'POST')
            $fileId = $request->fileId;
        else
            $fileId = $request->route()->parameter('fileId');

        if ($request->getPathInfo() == 'checkInFiles') {
            $fileIds = $request->fileIds;
            foreach ($fileIds as $fileId) {
                $logData = array(
                    'user_id' => $request->user()->id,
                    'file_id' => ($fileId) ? $fileId : 0,
                    'service' => $request->getPathInfo(),
                    'request' => $request->getContent(),
                    'response' => $response->getContent(),
                );
                DB::table('files_log')->insert($logData);
            }
        } else {
            $logData = array(
                'user_id' => $request->user()->id,
                'file_id' => ($fileId) ? $fileId : 0,
                'service' => $request->getPathInfo(),
                'request' => $request->getContent(),
                'response' => $response->getContent(),
            );
            DB::table('files_log')->insert($logData);
        }

        return $response;
    }
}

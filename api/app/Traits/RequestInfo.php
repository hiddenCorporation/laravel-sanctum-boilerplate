<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait RequestInfo
{

    /**
     * Determines if request is an api call.
     *
     * If the request URI contains '/api/v'.
     *
     * @param Request $request
     * @return bool
     */
    protected function isApiCall(Request $request)
    {

        if ($request->is('api/*') || $request->is('api/*')) {
            //
        }
        return strpos($request->getUri(), '/api/v') !== false;
    }

}

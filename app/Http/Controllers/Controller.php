<?php

namespace App\Http\Controllers;
use App\CPU\ResponseUtil;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    
    /**
     * @param $result
     * @param $message
     * @param int $code
     * @return JsonResponse
     */
    public function sendResponse($result, $message, int $code = 200): JsonResponse
    {
        return response()->json(ResponseUtil::makeResponse($message, $result), $code);
    }

    /**
     * @param $message
     * @param array $errors
     * @param int $code
     * @return JsonResponse
     */
    public function sendError($message, array $errors = [], int $code = 404): JsonResponse
    {
        return response()->json(ResponseUtil::makeError($message, $errors, $code), $code);
    }

    /**
     * @param $items
     * @param Request $request
     * @param $class
     * @return mixed
     */
    public function getList($items, Request $request, $class)
    {

        $path = 'App\\Http\\Resources\\';
        if ($request->filled('page')) {
            $path .= $class . 'Collection';
            $limit = $request->get('limit', 10);
            $items = new $path($items->paginate($limit));
        } else {
            $path .= $class . 'Resource';
            if ($request->filled('limit')) {
                $items->limit($request->get('limit'));
            }
            $items = $path::collection($items->get());
        }

        return $items;
    }
}

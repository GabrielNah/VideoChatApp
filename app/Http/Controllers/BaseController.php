<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseController extends Controller
{
    protected function responseBadRequest()
    {
        return response()->json(['error' => 'Bad operation'])->setStatusCode(Response::HTTP_BAD_REQUEST);
    }

    protected function responseOk(JsonResource $data)
    {
        return response()->json($data)->setStatusCode(Response::HTTP_OK);
    }

    protected function responseCreated(JsonResource $data)
    {
        return response()->json($data)->setStatusCode(Response::HTTP_CREATED);
    }

    protected function responseInvalidData(string $message, array $errors)
    {
        return response()->json([
            'message' => $message,
            'errors' => $errors
        ])->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    protected function responseMethodNotAllowed()
    {
        return response()->json(['error' => 'Has Not Implemented'])->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED);
    }
}

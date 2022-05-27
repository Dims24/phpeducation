<?php

namespace App\Http\Common;

use App\Foundation\HTTP\Response;
use App\Models\Common\BaseModel;

abstract class BaseController
{
    public function respond(mixed $data, int $code = 200, array $headers = []): Response
    {
        if ($data instanceof BaseModel)
        {
            $data = $data->toArray();
        }

        $response = new Response([
            'data' => $data,
        ], $code, $headers);
        return $response;
    }


}
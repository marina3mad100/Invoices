<?php

namespace App\CPU;

class ResponseUtil
{
    /**
     * @param string $message
     * @param mixed $data
     * @param int $code
     * @return array
     */
    public static function makeResponse(string $message, $data, int $code = 200): array
    {
        $res = [
            "status" => [
                "type" => "success",
                "message" => $message,
                "code" => $code,
                "error" => false
            ]
        ];

        if (!empty($data)) {
            $res['data'] = $data;
        }

        return $res;
    }

    /**
     * @param string $message
     * @param array $data
     * @param int $code
     * @return array
     */
    public static function makeError(string $message = '', array $errors = [], int $code = 404): array
    {

        switch ($code) {
            case 400:
                $type = 'bad-request';
                $defaultMessage = 'Bad Request';
                break;
            case 401:
                $type = 'unauthorized';
                $defaultMessage = 'Authentication Failure';
                break;
            case 403:
                $type = 'forbidden';
                $defaultMessage = 'Forbidden';
                break;
            case 404:
                $type = 'not-found';
                $defaultMessage = 'Not Found';
                break;
            case 422:
                $type = 'unprocessable-entity';
                $defaultMessage = 'Validation Failure';
                break;
            case 500:
                $type = 'server-error';
                $defaultMessage = 'Internal Server Error';
                break;
            default:
                $type = 'error';
                $defaultMessage = 'Something Error Happened';
                break;
        }

        $res = [
            "status" => [
                "type" => $type,
                "message" => $message ?: $defaultMessage,
                "code" => $code,
                "error" => true
            ],
        ];

        if (!empty($errors)) {
            $res['errors'] = $errors;
        }

        return $res;
    }
}

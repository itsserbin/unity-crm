<?php

namespace App\Services;

use JetBrains\PhpStorm\ArrayShape;
use JsonException;


class ApiService
{
    /**
     * Sends an HTTP request using cURL and returns the response in an array format.
     * @param string $url The URL to send the request to.
     * @param array $data An optional array of data to send as the request payload.
     * @param string $method An optional string indicating the HTTP method to use. Defaults to 'POST'.
     * @param array $headers An optional array of headers to include in the request. Defaults to ['content-type: application/json'].
     * @return array An array with two keys:
     * 'success': a boolean indicating if the request was successful
     * 'result': an array containing the response data if the request was successful, or an error message if it was not.
     * If the request was successful, the 'result' key will contain the response data in an array format.
     * If the request failed, the 'result' key will contain an error message with two keys:
     *  - 'error': the cURL error code
     *  - 'message': the cURL error message
     *
     */
    #[ArrayShape(['success' => "bool", 'result' => "array|mixed"])] final public function response(
        string $url,
        array  $data = [],
        string $method = 'POST',
        array  $headers = ['content-type: application/json']
    ): array
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $data ? json_encode($data) : null,
        ]);

        $response = curl_exec($curl);
        $error = curl_errno($curl);

        curl_close($curl);

        return [
            'success' => $error === 0,
            'result' => $error === 0 ? json_decode($response) : [
                'error' => $error,
                'message' => curl_error($curl)
            ]
        ];
    }
}

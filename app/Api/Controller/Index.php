<?php

namespace App\Api\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use support\Request;
use support\Response;

class Index
{
    public function index(): Response
    {
        $client  = new Client();
        $url     = 'https://api.openai.com/v1/completions';
        $headers = [
            'Content-Type'  => 'application/json',
            'Authorization' => '',
        ];
        $request = new \GuzzleHttp\Psr7\Request('POST', $url, $headers);

        $prompt = request()->get('query');
        $model  = 'text-davinci-003';
        if (!$prompt) {
            return failJson('输入内容错误');
        }

        $body = [
            'model' => $model,
            'prompt' => $prompt,
            'temperature' => 0,
            'max_tokens' => 2048
        ];
        $return = '';
        try {
            $response = $client->send($request, ['body' => json_encode($body), 'stream' => true]);
            $body     = $response->getBody();
            while (!$body->eof()) {
                $return .= $body->read(1024);
            }
        } catch (GuzzleException $e) {
            return failJson($e->getMessage());
        }
        return successJson([$return]);
    }

    public function user(Request $request, $uid): Response
    {
        return successJson([], 'uid is ' . $uid);
    }

}

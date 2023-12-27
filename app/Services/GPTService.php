<?php

namespace App\Services;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GPTService
{
    const BASE_URL = 'https://key.wenwen-ai.com/v1/chat/completions';

    public readonly string $presetContent;
    private string $GPT3Key;
    private string $GPT4Key;

    public function __construct()
    {
        $settings = config('gpt');

        $this->presetContent = $settings['preset_content'];

        $this->GPT3Key = $settings['gpt3_key'];

        $this->GPT4Key = $settings['gpt4_key'];
    }

    /**
     * @throws GuzzleException
     */
    public function request(array $messages)
    {
        $client = new Client([
            'headers' => [
                'Authorization' => 'Bearer ' . $this->GPT3Key,
                'Content-Type' => 'application/json',
            ],
        ]);

        $res = $client->post(self::BASE_URL, [
            'body' => json_encode($this->data($messages)),
        ]);

        $resBody = $res->getBody()->getContents();

        return json_decode($resBody, true);
    }

    /**
     * @param array $messages
     * @return array
     */
    public function data(array $messages): array
    {
        return [
            'model' => 'gpt-3.5-turbo',
            'messages' => $messages,
            'stream' => true,
        ];
    }
}

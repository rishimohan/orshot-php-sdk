<?php

namespace Orshot;

require_once __DIR__ . '/Constants.php';

use GuzzleHttp\Client as HttpClient;

class Client
{
    private $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    private function getBaseUrl(string $version=Constants::ORSHOT_API_VERSION) {
        return Constants::ORSHOT_API_BASE_URL . '/' . $version;
    }

    public function renderFromTemplate(array $renderOptions) {
        $client = new HttpClient();

        $templateId = $renderOptions['templateId'];
        $modifications = $renderOptions['modifications'];
        $responseType = $renderOptions['responseType'] ?? Constants::DEFAULT_RESPONSE_TYPE;
        $responseFormat = $renderOptions['responseFormat'] ?? Constants::DEFAULT_RESPONSE_FORMAT;

        $data = [
            'response' => [
                'type' => $responseType,
                'format' => $responseFormat,
            ],
            'modifications' => $modifications,
            'source' => Constants::ORSHOT_SOURCE,
        ];

        $headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ];

        $endpoint = $this->getBaseUrl() . '/generate/images/' . $templateId;
        
        $response = $client->post($endpoint, [
            'json' => $data,
            'headers' => $headers,
        ]);

        if ($responseType == 'base64' || $responseType == 'url') {
            $responseBody = $response->getBody();

            $jsonResponse = json_decode($responseBody, true);

            return $jsonResponse;
        } else {
            return $response->getBody()->getContents();
        }
    }

    public function generateSignedUrl(array $signedUrlOptions) {
        $client = new HttpClient();

        $templateId = $signedUrlOptions['templateId'];
        $expiresAt = $signedUrlOptions['expiresAt'];
        $modifications = $signedUrlOptions['modifications'];
        $renderType = $signedUrlOptions['renderType'] ?? Constants::DEFAULT_RENDER_TYPE;
        $responseFormat = $signedUrlOptions['responseFormat'] ?? Constants::DEFAULT_RESPONSE_FORMAT;

        $data = [
            'templateId' => $templateId,
            'renderType' => $renderType,
            'responseFormat' => $responseFormat,
            'modifications' => $modifications,
            'source' => Constants::ORSHOT_SOURCE,
            'expiresAt' => $expiresAt,
        ];

        $headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ];

        $endpoint = $this->getBaseUrl() . '/signed-url/create/';
        
        $response = $client->post($endpoint, [
            'json' => $data,
            'headers' => $headers,
        ]);

        $responseBody = $response->getBody();

        $jsonResponse = json_decode($responseBody, true);

        return $jsonResponse;
    }

    public function renderFromStudioTemplate(array $options) {
        $client = new HttpClient();

        $templateId = $options['templateId'];
        $modifications = $options['modifications'] ?? null;
        $responseOptions = $options['response'] ?? [];
        $pdfOptions = $options['pdfOptions'] ?? null;
        $videoOptions = $options['videoOptions'] ?? null;
        $publish = $options['publish'] ?? null;

        $responseType = $responseOptions['type'] ?? Constants::DEFAULT_RESPONSE_TYPE;
        $responseFormat = $responseOptions['format'] ?? Constants::DEFAULT_RESPONSE_FORMAT;

        $data = [
            'templateId' => $templateId,
            'source' => Constants::ORSHOT_SOURCE,
            'response' => [
                'type' => $responseType,
                'format' => $responseFormat,
            ],
        ];

        if ($modifications !== null) {
            $data['modifications'] = $modifications;
        }

        if (isset($responseOptions['scale'])) {
            $data['response']['scale'] = $responseOptions['scale'];
        }

        if (isset($responseOptions['includePages'])) {
            $data['response']['includePages'] = $responseOptions['includePages'];
        }

        if (isset($responseOptions['fileName'])) {
            $data['response']['fileName'] = $responseOptions['fileName'];
        }

        if ($pdfOptions !== null) {
            $data['pdfOptions'] = $pdfOptions;
        }

        if ($videoOptions !== null) {
            $data['videoOptions'] = $videoOptions;
        }

        if ($publish !== null) {
            $data['publish'] = $publish;
        }

        $headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ];

        $endpoint = $this->getBaseUrl() . '/studio/render';

        $response = $client->post($endpoint, [
            'json' => $data,
            'headers' => $headers,
        ]);

        if ($responseType == 'base64' || $responseType == 'url') {
            $responseBody = $response->getBody();
            $jsonResponse = json_decode($responseBody, true);
            return $jsonResponse;
        } else {
            return $response->getBody()->getContents();
        }
    }
}

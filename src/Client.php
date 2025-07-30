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

    /**
     * Render from studio template
     * 
     * @param array $renderOptions Array containing studioTemplateId, modifications, and optional responseType/responseFormat
     * @return array|string Returns JSON array for base64/url responses, or binary string for binary response
     */
    public function renderFromStudioTemplate(array $renderOptions) {
        $client = new HttpClient();

        $studioTemplateId = $renderOptions['studioTemplateId'];
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

        $endpoint = $this->getBaseUrl() . '/generate/images-from-studio-template/' . $studioTemplateId;
        
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
}

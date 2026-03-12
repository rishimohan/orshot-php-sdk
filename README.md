# Orshot API PHP SDK

View on Packagist: [https://packagist.org/packages/rishimohan/orshot](https://packagist.org/packages/rishimohan/orshot)

## Installation

```
composer require rishimohan/orshot
```

## Usage

If you don't have your API key, get one from [orshot.com](https://orshot.com)

```php
<?php

require 'vendor/autoload.php';

use Orshot\Client;

$client = new Client("YOUR_ORSHOT_API_KEY");
```

## renderFromStudioTemplate

Render from a custom [Studio template](https://orshot.com/features/orshot-studio). Supports image, PDF, video generation and publishing to social accounts.

### Generate Image

```php
$response = $client->renderFromStudioTemplate([
    'templateId' => 1234,
    'modifications' => [
        'title' => 'Orshot Studio',
        'description' => 'Generate images from custom templates',
    ],
    'response' => [ 'type' => 'url', 'format' => 'png', 'scale' => 2 ],
]);
```

### Generate PDF

```php
$response = $client->renderFromStudioTemplate([
    'templateId' => 1234,
    'modifications' => [ 'title' => 'Invoice #1234' ],
    'response' => [ 'type' => 'url', 'format' => 'pdf' ],
    'pdfOptions' => [
        'margin' => '20px',
        'rangeFrom' => 1,
        'rangeTo' => 2,
        'colorMode' => 'rgb',
        'dpi' => 300,
    ],
]);
```

### Generate Video

```php
$response = $client->renderFromStudioTemplate([
    'templateId' => 1234,
    'modifications' => [
        'videoElement' => 'https://example.com/custom-video.mp4',
        'videoElement.trimStart' => 0,
        'videoElement.trimEnd' => 10,
    ],
    'response' => [ 'type' => 'url', 'format' => 'mp4' ],
    'videoOptions' => [ 'trimStart' => 0, 'trimEnd' => 20, 'muted' => false, 'loop' => true ],
]);
```

### Publish to Social Accounts

```php
$response = $client->renderFromStudioTemplate([
    'templateId' => 1234,
    'modifications' => [ 'title' => 'Check out our latest update!' ],
    'response' => [ 'type' => 'url', 'format' => 'png' ],
    'publish' => [
        'accounts' => [1, 2],
        'content' => 'Check out our latest design!',
    ],
]);
// $response['publish'] => [['platform' => 'twitter', 'username' => 'acmehq', 'status' => 'published'], ...]
```

### Schedule a Post

```php
$response = $client->renderFromStudioTemplate([
    'templateId' => 1234,
    'modifications' => [ 'title' => 'Scheduled post' ],
    'response' => [ 'type' => 'url', 'format' => 'png' ],
    'publish' => [
        'accounts' => [1],
        'content' => 'This will be posted later!',
        'schedule' => [ 'scheduledFor' => '2026-04-01T10:00:00Z' ],
        'timezone' => 'America/New_York',
    ],
]);
```

### Parameters

| key                             | required | description                                                                    |
| ------------------------------- | -------- | ------------------------------------------------------------------------------ |
| `templateId`                    | Yes      | ID of the Studio template (integer).                                           |
| `modifications`                 | No       | Array of dynamic modifications for the template.                               |
| `response.type`                 | No       | `base64`, `binary`, `url` (Defaults to `url`).                                 |
| `response.format`               | No       | `png`, `webp`, `jpg`, `jpeg`, `pdf`, `mp4`, `webm`, `gif` (Defaults to `png`). |
| `response.scale`                | No       | Scale of the output (`1` = original, `2` = double). Defaults to `1`.           |
| `response.includePages`         | No       | Page numbers to render for multi-page templates (e.g. `[1, 3]`).               |
| `response.fileName`             | No       | Custom file name (without extension). Works with `url` and `binary` types.     |
| `pdfOptions`                    | No       | `{ margin, rangeFrom, rangeTo, colorMode, dpi }`                               |
| `videoOptions`                  | No       | `{ trimStart, trimEnd, muted, loop }`                                          |
| `publish.accounts`              | No       | Array of social account IDs from your workspace.                               |
| `publish.content`               | No       | Caption/text for the social post.                                              |
| `publish.isDraft`               | No       | `true` to save as draft instead of publishing.                                 |
| `publish.schedule.scheduledFor` | No       | ISO date string to schedule the post.                                          |
| `publish.timezone`              | No       | Timezone string (e.g. `"America/New_York"`).                                   |
| `publish.platformOptions`       | No       | Per-account options keyed by account ID.                                       |

---

## renderFromTemplate

Render from a pre-built Orshot template.

```php
$response = $client->renderFromTemplate([
    'templateId' => 'open-graph-image-1',
    'modifications' => [ 'title' => 'Hello World' ],
    'responseType' => 'url',
    'responseFormat' => 'png',
]);
```

| key              | required | description                                                      |
| ---------------- | -------- | ---------------------------------------------------------------- |
| `templateId`     | Yes      | ID of the template (`open-graph-image-1`, `tweet-image-1`, etc.) |
| `modifications`  | Yes      | Modifications for the selected template.                         |
| `responseType`   | No       | `base64`, `binary`, `url` (Defaults to `url`).                   |
| `responseFormat` | No       | `png`, `webp`, `pdf`, `jpg`, `jpeg` (Defaults to `png`).         |

For available templates and their modifications refer [Orshot Templates Page](https://orshot.com/templates)

## generateSignedUrl

Generate a signed URL for a template.

```php
$response = $client->generateSignedUrl([
    'templateId' => 'open-graph-image-1',
    'modifications' => [ 'title' => 'Hello World' ],
    'expiresAt' => 1744276943,
    'renderType' => 'images',
    'responseFormat' => 'png',
]);
```

| key              | required | description                                              |
| ---------------- | -------- | -------------------------------------------------------- |
| `templateId`     | Yes      | ID of the template.                                      |
| `modifications`  | Yes      | Modifications for the selected template.                 |
| `expiresAt`      | Yes      | Expires at in unix timestamp (Number).                   |
| `renderType`     | No       | `images`, `pdfs` (Defaults to `images`).                 |
| `responseFormat` | No       | `png`, `webp`, `pdf`, `jpg`, `jpeg` (Defaults to `png`). |

## Local development and testing

Run these from the project root

`composer config repositories.local '{"type": "path", "url": "/path/to/your/library"}' --global`

Update the url to the path of the library in your computer.

In a separate test directory, run this

`composer require rishimohan/orshot:@dev`

Write a small PHP script `test.php` in the test directory using the above exposed functions and run `php test.php`

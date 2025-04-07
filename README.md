# Orshot API PHP SDK

View on Packagist: [https://packagist.org/packages/rishimohan/orshot](https://packagist.org/packages/rishimohan/orshot)

## Installation

```
composer require rishimohan/orshot
```

## Usage

If you don't have your API key, get one from [orshot.com](https://orshot.com)

### Render from template

```php
$response = $client->renderFromTemplate(['templateId'=> 'open-graph-image-1', 'modifications' => $modifications, 'responseType'=> 'url', 'responseFormat' => 'png']);
```

### Generate signed URL

```php
$signed_response = $client->generateSignedUrl(['templateId'=> 'open-graph-image-1', 'expiresAt' => 1744276943, 'modifications' => $modifications, 'renderType'=> 'images', 'responseFormat' => 'png']);
```

## Example

### `Base64` response format

```php
<?php 

require 'vendor/autoload.php';

use Orshot\Client;

$client = new Client("os-ha2jdus1cbz1dpt4mktgjyvx");

$modifications = [
    'title' => 'Title from PHP SDK.',
    'description' => 'Description from PHP SDK.'
];

$response = $client->renderFromTemplate(['templateId'=> 'open-graph-image-1', 'modifications' => $modifications, 'responseType'=> 'base64', 'responseFormat' => 'png']);
print_r($response['data']);
```

Output

```
Array
(
    [content] => data:image/png;base64,iVBORw0K...
    [format] => png
    [type] => base64
    [responseTime] => 2787.17
)
```

### `URL` response format

```php
<?php 

require 'vendor/autoload.php';

use Orshot\Client;

$client = new Client("os-ha2jdus1cbz1dpt4mktgjyvx");

$modifications = [
    'title' => 'Title from PHP SDK.',
    'description' => 'Description from PHP SDK.'
];

$response = $client->renderFromTemplate(['templateId'=> 'open-graph-image-1', 'modifications' => $modifications, 'responseType'=> 'url', 'responseFormat' => 'png']);
print_r($response['data']);
```

Output

```
Array
(
    [content] => https://storage.orshot.com/00632982-fd46-44ff-9a61-f41edf1b8e62/images/KwSv2IS4jwH.png
    [type] => url
    [format] => png
    [responseTime] => 3775.54
)
```

### `Binary` response format

```php
<?php 

require 'vendor/autoload.php';

use Orshot\Client;

$client = new Client("os-ha2jdus1cbz1dpt4mktgjyvx");

$modifications = [
    'title' => 'Title from PHP SDK.',
    'description' => 'Description from PHP SDK.'
];

$response = $client->renderFromTemplate(['templateId'=> 'open-graph-image-1', 'modifications' => $modifications, 'responseType'=> 'binary', 'responseFormat' => 'png']);
file_put_contents('og.png', $response);
```

This example writes the binary image to the file `og.png`

### Signed URL

```php
<?php 

require 'vendor/autoload.php';

use Orshot\Client;

$client = new Client("os-ha2jdus1cbz1dpt4mktgjyvx");

$modifications = [
    'title' => 'Title from PHP SDK.',
    'description' => 'Description from PHP SDK.'
];

$signed_response = $client->generateSignedUrl(['templateId'=> 'open-graph-image-1', 'expiresAt' => 1744276943, 'modifications' => $modifications, 'renderType'=> 'images', 'responseFormat' => 'png']);
print_r($signed_response['data']);
```

Output

```
Array
(
    [url] => https://api.orshot.com/v1/generate/images?description=Description%20from%20PHP%20SDK.&expiresAt=1744276943&id=36&templateId=open-graph-image-1&title=Title%20from%20PHP%20SDK.&signature=7ede3e531de82cbage6174f8f684840b6f8ed0281d5115a748dce924c014daa7
)
```

## renderFromTemplate

Use this function to render an image/pdf.

| argument | required | description |
|----------|----------|-------------|
| `templateId` | Yes | ID of the template (`open-graph-image-1`, `tweet-image-1`, `beautify-screenshot-1`, ...) |
| `modifications` | Yes | Modifications for the selected template. |
| `responseType` | No | `base64`, `binary`, `url` (Defaults to `base64`). |
| `responseFormat` | No | `png`, `webp`, `pdf`, `jpg`, `jpeg` (Defaults to `png`). |

For available templates and their modifications refer [Orshot Templates Page](https://orshot.com/templates)

## generateSignedUrl

Use this function to generate signed URL.

| key | required | description |
|----------|----------|-------------|
| `templateId` | Yes | ID of the template (`open-graph-image-1`, `tweet-image-1`, `beautify-screenshot-1`, ...) |
| `modifications` | Yes | Modifications for the selected template. |
| `expiresAt` | Yes | Expires at in unix timestamp (Number). |
| `renderType` | No | `images`, `pdfs` (Defaults to `images`). |
| `responseFormat` | No | `png`, `webp`, `pdf`, `jpg`, `jpeg` (Defaults to `png`). |

## Local development and testing

Run these from the project root

`composer config repositories.local '{"type": "path", "url": "/path/to/your/library"}' --global`

Update the url to the path of the library in your computer.

In a separate test directory, run this

`composer require rishimohan/orshot:@dev`

Write a small PHP script `test.php` in the test directory using the above exposed functions and run `php test.php`

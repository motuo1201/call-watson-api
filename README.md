# Call Watson API for Assistant and Discovery

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/motuo/call-watson-api.svg?style=flat-square)](https://packagist.org/packages/motuo/call-watson-api)
[![Total Downloads](https://img.shields.io/packagist/dt/motuo/call-watson-api.svg?style=flat-square)](https://packagist.org/packages/motuo/call-watson-api)

`motuo/call-watson-api` is a laravel package providing easy cooperation to Watson
Such as:

- Call Watson Assistant API(Get response to user input)
- Call Watson Discovery Service API

Japanese Document  
日本語記事は[こちら](https://qiita.com/motuo/items/27b7f50fba64c1dd149f)

## Installation

You can install the package via composer:

``` bash
composer require motuo/call-watson-api
```

If you are using Laravel 5.5, the service provider will automatically be discovered. 

Publish config file

```bash
php artisan vendor:publish
```
and choose

```
Provider: motuo\CallWatsonAPI\CallWatsonServiceProvider
```

Add API credentials to `.env` file like this as you need.

```env
WATSON_ASSISTAN_API_URL="https://gateway-fra.watsonplatform.net/assistant/api"
WATSON_ASSISTANT_VERSION="2018-07-10"
WATSON_ASSISTANT_WORKSPACEID=xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
WATSON_ASSISTANT_USER_NAME=xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
WATSON_ASSISTANT_PASSWORD=xxxxxxxxxxxx

WATSON_DISCOVERY_API_URL="https://gateway-fra.watsonplatform.net/discovery/api"
WATSON_DISCOVERY_VERSION="2018-10-15"
WATSON_DISCOVERY_ENV_ID=xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
WATSON_DISCOVERY_COLLECTION=xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
WATSON_DISCOVERY_USER_NAME=xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
WATSON_DISCOVERY_PASSWORD=xxxxxxxxxxxx
```

## Usage

### Call Watson Assistant
Example

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use motuo\CallWatsonAPI\CallAssistant;

class TestContoller extends Controller
{
    public function index(Request $request,CallAssistant $CWA){
        //provide user input message and context
        $response      = $CWA->postMessage('input message',session('context')?session('context'):[]);
        //this method returns json.If necessary,please json decode.
        $responseArray = json_decode($response,true);
        //this step put a context to session for next conversation
        $request->session()->put('context',$responseArray['context']);
        return view('welcome');
    }
}
```

### Call Watson Discovery
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use motuo\CallWatsonAPI\CallDiscovery;

class TestContoller extends Controller
{
    public function index(Request $request,CallDiscovery $CWD){
        //Query your collection
        $query  = ['query'=>[
            'version'        => '2018-08-01',
            'deduplicate'    => 'false',
            'highlight'      => 'true',
            'passages'       => 'true',
            'passages.count' => '5'   ,
            'natural_language_query' => 'natural_language_query'
        ]];
        $CWD->queryCollection($query);
        //Management training Querys
        $CWD->listTrainingData();
        $CWD->getQueryIdByNLQ('natural_language_query');
        $CWD->addQueryToTrainingData('document_id','natural_language_query',100);
        $CWD->deleteTrainingDataQuery('query_id');
        //Management Examples
        $CWD->listExamplesTrainingData('query_id');
        $CWD->getExampleId('query_id','document_id');
        $CWD->addExampleToTrainingData('query_id','document_id',100);
        $CWD->deleteExampleForTrainingDataQuery('query_id','document_id');
        return view('welcome');
    }
}
```

## Version history

See the [dedicated change log](CHANGELOG.md)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

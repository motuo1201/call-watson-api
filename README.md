# Call Watson Assistant API

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

`motuo/call-watson-api` is a laravel package providing easy cooperation to Watson
Such as:

- Call Watson Assistant API(Get response to user input)


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

Add API credentials to `.env` file like this.

```env
WATSON_ASSISTAN_API_URL="https://gateway-fra.watsonplatform.net/assistant/api"
WATSON_ASSISTANT_VERSION="2018-07-10"
WATSON_ASSISTANT_WORKSPACEID=xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
WATSON_ASSISTANT_USER_NAME=xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
WATSON_ASSISTANT_PASSWORD=xxxxxxxxxxxx
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

## Version history

See the [dedicated change log](CHANGELOG.md)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
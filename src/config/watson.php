<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Assistant Enviroment Infomation
    |--------------------------------------------------------------------------
    */
    'assistant_api_url'      => env('WATSON_ASSISTAN_API_URL'),
    'assistant_version'      => env('WATSON_ASSISTANT_VERSION'),
    'assistant_workspace_id' => env('WATSON_ASSISTANT_WORKSPACEID'),
    /*
    |--------------------------------------------------------------------------
    | Discovery Enviroment Infomation
    |--------------------------------------------------------------------------
    */
    'discovery_api_url'     => env('WATSON_DISCOVERY_API_URL'),
    'discovery_version'     => env('WATSON_DISCOVERY_VERSION'),
    'discovery_env_id'      => env('WATSON_DISCOVERY_ENV_ID'),
    'discovery_collection'  => env('WATSON_DISCOVERY_COLLECTION'),
    /*
    |--------------------------------------------------------------------------
    | API Keys (UserName/Password)
    |--------------------------------------------------------------------------
    */
    'assistant_user_name'   => env('WATSON_ASSISTANT_USER_NAME'),
    'assistant_password'    => env('WATSON_ASSISTANT_PASSWORD'),
    
    'discovery_user_name'   => env('WATSON_DISCOVERY_USER_NAME'),
    'discovery_password'    => env('WATSON_DISCOVERY_PASSWORD'),
];
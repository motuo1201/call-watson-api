<?php

namespace motuo\CallWatsonAPI;

use GuzzleHttp\Client;
/**
 * Call Watson Assistant API
 */
class CallAssistant{
    /**
     * this method returns WatsonAssitant's Response.
     * 
     * @param string $spokenWord message from user
     * @param array $context　context from before message
     * @return json Response From Watson Assistant
     */
    public function postMessage(string $spokenWord,array $context)
    {
        if(count($context)>0){
            $requestData  = json_encode(['input'=>['text'=>$spokenWord],'context'=>$context]);
        }else{
            $requestData  = json_encode(['input'=>['text'=>$spokenWord]]);
        }
        $headers = ['Content-Type' => 'application/json','Content-Length' => strlen($requestData)];
        $curlOpts = [
            CURLOPT_USERPWD        => config('watson.assistant_user_name').':'.config('watson.assistant_password'),
            CURLOPT_POSTFIELDS     => $requestData
        ];
        $path         = config('watson.assistant_workspace_id') . '/message?version='.config('watson.assistant_version');
        $guzzleClient = new Client(['base_uri'=>config('watson.assistant_api_url').'/v1/workspaces/']);
        return $guzzleClient->request('POST',$path,['headers'=> $headers,'curl'=>$curlOpts])->getBody()->getContents();
    }
}
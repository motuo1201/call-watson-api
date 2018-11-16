<?php

namespace motuo\CallWatsonAPI;

use GuzzleHttp\Client;
/**
 * Call Watson Discovery API
 */
class CallDiscovery{
    /**
     * queries to search collection
     *
     * @param array $queryParameter https://console.bluemix.net/apidocs/discovery#query-your-collection
     * @return json Response from Watson Discovery
     */
    public function queryCollection(array $queryParameter)
    {
        return $this->getRequest('query',$queryParameter);
    }
    /**
     * Lists the training data for the specified collection.
     *
     * @return json response from Watson Discovery
     */
    public function listTrainingData()
    {
        return $this->getRequest('training_data');
    }
    /**
     * Get Query_ID using natural language query
     * If query exists, return query_id
     * else returns null.
     * This method is useful when you decide that a training data might be added to Query  or Example.
     * 
     * @param string $natural_language_query
     * @return string query_id
     */
    public function getQueryIdByNLQ(string $natural_language_query)
    {
        $response      = json_decode($this->getRequest('training_data'));
        foreach ($response->queries as $query) {
            if($natural_language_query === $query->natural_language_query){
                return $query->query_id;
            }
        }
        return null;
    }
    /**
     * Add query to training Data
     *
     * @param string $document_id
     * @param string $natural_language_query
     * @param integer $relevance min:0|max:100
     * @return json response from Watson Discovery
     */
    public function addQueryToTrainingData(string $document_id,string $natural_language_query,int $relevance)
    {
        $requestData  = json_encode([
            'natural_language_query'=> $natural_language_query,
            'filter'                => '',
            'examples'              => [['document_id'=>$document_id,"relevance"=>$relevance]]
        ]);
        $headers = ['Content-Type' => 'application/json','Content-Length' => strlen($requestData)];
        $curlOpts = [
            CURLOPT_POSTFIELDS     => $requestData
        ];
        $path         = config('watson.discovery_env_id') . '/collections/'.config('watson.discovery_collection').
                        '/training_data?version='.config('watson.discovery_version');
        $guzzleClient = new Client(['base_uri'=>config('watson.discovery_api_url').'/v1/environments/']);
        return $guzzleClient->request('POST',$path,[
            'headers'=> $headers,
            'curl'   => $curlOpts]+$this->getAuthKey())->getBody()->getContents();
    }
    /**
     * Delete training Data query
     *
     * @param string $query_id query_id
     * @return json response from Watson Discovery
     */
    public function deleteTrainingDataQuery(string $query_id)
    {
        $path         = config('watson.discovery_env_id') . '/collections/'.config('watson.discovery_collection').
                        '/training_data/'.$query_id.'?version='.config('watson.discovery_version');
        $guzzleClient = new Client(['base_uri'=>config('watson.discovery_api_url').'/v1/environments/']);
        return $guzzleClient->request('DELETE',$path,$this->getAuthKey())->getBody()->getContents();
    }
    /**
     * Lists the examplest for training data.
     *
     * @return json response from Watson Discovery
     */
    public function listExamplesTrainingData(string $query_id)
    {
        $getParameter = $this->getAuthKey();
        $getParameter['query_id'] = $query_id;
        $path         = config('watson.discovery_env_id') . '/collections/'.config('watson.discovery_collection').'/training_data/'.$query_id.'/examples?version='.
                        config('watson.discovery_version');
        $guzzleClient = new Client(['base_uri'=>config('watson.discovery_api_url').'/v1/environments/']);
        return $guzzleClient->request('GET',$path,$getParameter)->getBody()->getContents();
    }
    /**
     * Get Example ID From Query ID and document id.
     * If example exists, return example_id
     * else returns null.
     * This method is useful when you decide that a example data might be added or change.
     * 
     * @param string $query_id
     * @param string $document_id
     * @return string document_id
     */
    public function getExampleId(string $query_id,string $document_id)
    {
        $response      = json_decode($this->listExamplesTrainingData($query_id));
        foreach ($response->examples as $example) {
            if($document_id === $example->document_id){
                return $example->document_id;
            }
        }
        return null;
    }
    /**
     * Add example to training Data.
     *
     * @param string $query_id
     * @param string $document_id
     * @param integer $relevance
     * @return json response from Watson Discovery
     */
    public function addExampleToTrainingData(string $query_id,string $document_id,int $relevance)
    {
        $requestData  = json_encode([
            'document_id'     => $document_id,
            'cross_reference' => '',
            'relevance'       => $relevance
        ]);
        $headers = ['Content-Type' => 'application/json','Content-Length' => strlen($requestData)];
        $curlOpts = [
            CURLOPT_POSTFIELDS     => $requestData
        ];
        $path         = config('watson.discovery_env_id') . '/collections/'.config('watson.discovery_collection').'/training_data/'.
                        $query_id.'/examples/?version='.config('watson.discovery_version');
        $guzzleClient = new Client(['base_uri'=>config('watson.discovery_api_url').'/v1/environments/']);
        return $guzzleClient->request('POST',$path,[
            'headers'=> $headers,
            'curl'   => $curlOpts]+$this->getAuthKey())->getBody()->getContents();
    }
    /**
     * Delete example for training Data query
     *
     * @param string $query_id query_id
     * @param string $example_id document_id
     * @return json response from Watson Discovery
     */
    public function deleteExampleForTrainingDataQuery(string $query_id,string $example_id)
    {
        $path         = config('watson.discovery_env_id') . '/collections/'.config('watson.discovery_collection').
                        '/training_data/'.$query_id.'/examples/'.$example_id.'?version='.config('watson.discovery_version');
        $guzzleClient = new Client(['base_uri'=>config('watson.discovery_api_url').'/v1/environments/']);
        return $guzzleClient->request('DELETE',$path,$this->getAuthKey())->getBody()->getContents();
    }
    
    /**
     * Request To Watson Discovery By GET method
     *
     * @param string $function function name
     * @param array $requestParameters 
     * @return json response from Watson Discovery Service
     */
    private function getRequest(string $function,array $requestParameters=[])
    {
        $getParameter = $this->getAuthKey();
        foreach ($requestParameters as $key=>$requestParameter) {
            $getParameter[$key] = $requestParameter;
        }
        $path         = config('watson.discovery_env_id') . '/collections/'.config('watson.discovery_collection').'/'.$function.'?version='.
                        config('watson.discovery_version');
        $guzzleClient = new Client(['base_uri'=>config('watson.discovery_api_url').'/v1/environments/']);
        return $guzzleClient->request('GET',$path,$getParameter)->getBody()->getContents();
    }
    /**
     * return Access Key for Watson Discovery
     *
     * @return array authKey
     */
    private function getAuthKey()
    {
        return ['auth'=> [config('watson.discovery_user_name'),config('watson.discovery_password')]];
    }
}
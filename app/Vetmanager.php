<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Throwable;
use GuzzleHttp\Client;
use function Otis22\VetmanagerUrl\url;
use function Otis22\VetmanagerRestApi\uri;
use function Otis22\VetmanagerRestApi\byApiKey;
use Otis22\VetmanagerRestApi\Query\PagedQuery;
use Otis22\VetmanagerRestApi\Query\Query;
use Otis22\VetmanagerRestApi\Query\Filters;
use Otis22\VetmanagerRestApi\Model\Property;
use Otis22\VetmanagerRestApi\Query\Filter\Value\StringValue;
use Otis22\VetmanagerRestApi\Query\Filter\Like;
use Otis22\VetmanagerRestApi\Query\Filter\EqualTo;
use Otis22\VetmanagerRestApi\Query\Filter\NotEqualTo;
use Otis22\VetmanagerRestApi\Query\Sorts;
use Otis22\VetmanagerRestApi\Query\Sort\AscBy;


/**
 * https://github.com/otis22/vetmanager-rest-api
 * 
 * $model - client|pet
 */

class Vetmanager 
{
    private $userUrl;

    private $userApiKey;

    private $limit = 50;

    public function __construct()
    {
        $user = Auth::user()->load('keySettings');
        $this->userUrl = $user->keySettings->url;
        $this->userApiKey = $user->keySettings->key;
    }

    /**
     * API models array
     */
    private function _model()
    {
        return [
            'client' => 'client',
            'pet' => 'pet',
        ];
    }
    
    
    /**
     * Get PagedQuery clients or pets
     * 
     * $request Request 
     * $model - API model
     * $fieldsArray - array (field => value)
     * $limitP integer | null 
     *  {{Domain URL}}/rest/api/client
     *  {{Domain URL}}/rest/api/pet
     */
    public function getAll(Request $request, $model, $fieldsArray = [], $limitP = null)
    {
        $page = $request->page ?: 1;
        $limit = $limitP ?: $this->limit;
        $filters = new Filters();
        $sorts = new Sorts(
            new AscBy(
                new Property('id')
            )
        );

        if ($model == $this->_model()['client']) {
            $filters = $this->_searchClient($request);
        }

        if (!empty($fieldsArray)) {
            $filtersArray = [];
            foreach ($fieldsArray as $k => $v) {
                $filtersArray[] = new EqualTo(
                    new Property($k),
                    new StringValue($v)
                );
            }
            $filters = new Filters(...$filtersArray);
        }
        
        $query = new PagedQuery(
            new Query($filters, $sorts),
            $limit,
            $page-1
        );
        $response = $this->_request('GET', $model, $query);

        if (isset($response['data']['totalCount'])) {
            $paginator = new LengthAwarePaginator(
                $response['data'][$model], $response['data']['totalCount'], $limit, $page, ['path' => request()->fullUrl()]
            );
            
        }
        return $paginator;
    }

    /**
     * GET by id
     * $model - string API model 
     * $id integer
     */
    public function getById($model, $id)
    {
        $response = $this->_request('GET', $model, null, $id);
        if (isset($response['data'][$model])) {
            return $response['data'][$model];
        }
        return [];
    }

    /**
     * 
     * $model - string API model
     * $data array
     */
    public function createRequest($model, $data)
    {
        return $this->_request('POST', $model, $data);
    }

    /**
     * 
     * $model - string API model
     * $data array
     * $id integer
     */
    public function updateRequest($model, $data, $id)
    {
        return $this->_request('PUT', $model, $data, $id);
    }

    /**
     * $model - string API model
     * $id integer
     */
    public function deleteRequest($model, $id)
    {
        return $this->_request('DELETE', $model, null, $id);
    }

    /**
     * Client search filters
     * $request Request
     */
    private function _searchClient(Request $request)
    {
        $filtersArray = [];
        $search = $request->search ?: null;

        $filtersArray[] = new EqualTo(
            new Property('status'),
            new StringValue('ACTIVE')
        );

        if (isset($search)) {
            $search_arr = preg_split("/[\s,]+/", $search);

            if (count($search_arr) == 3) {
                $filtersArray[] = new Like(
                    new Property('last_name'),
                    new StringValue($search_arr[0])
                );
                $filtersArray[] = new Like(
                    new Property('first_name'),
                    new StringValue($search_arr[1])
                );
                $filtersArray[] = new Like(
                    new Property('middle_name'),
                    new StringValue($search_arr[2])
                );
                
            } elseif (count($search_arr) == 2) {
                $filtersArray[] = new Like(
                    new Property('last_name'),
                    new StringValue($search_arr[0])
                );
                $filtersArray[] = new Like(
                    new Property('first_name'),
                    new StringValue($search_arr[1])
                );
            } elseif (isset($search_arr[0])) {
                $filtersArray[] = new Like(
                    new Property('last_name'),
                    new StringValue($search_arr[0])
                );
            }
        }
        $filters = new Filters(...$filtersArray);

        return $filters;
    }

    /**
     * Delete client with his pets
     * $client_id integer
     */
    public function deleteClient($client_id)
    {
        // Get pets by client ID
        //  /rest/api/pet/?filter=[{'property':'owner_id', 'value':'{{ID}}'},{'property':'status', 'value':'deleted', 'operator':'!='}]
        $filters = new Filters(
            new EqualTo(
                new Property('owner_id'),
                new StringValue($client_id)
            ),
            new NotEqualTo(
                new Property('status'),
                new StringValue('deleted')
            )
        );
        $ownerPets = $this->_request('GET', $this->_model()['pet'], $filters);

        if (isset($ownerPets['data']['pet'])) {
            $petDeleteFails = [];
            foreach ($ownerPets['data']['pet'] as $v) {
                // Delete pet
                $response = $this->_request('DELETE', $this->_model()['pet'], null, $v['id']);
                if (!isset($response['success']) || !$response['success']) {
                    $petDeleteFails[] = $v['id'];
                }
            }

            if (empty($petDeleteFails)) {
                // Delete client itself
                $response = $this->_request('DELETE', $this->_model()['client'], null, $client_id);
                if (!isset($response['success']) || !$response['success']) {
                    return ['result' => false];
                }
            } else {
                return ['result' => false, 'fails' => $petDeleteFails];
            }
            return ['result' => true];
        }
       
        return ['result' => false];
    }

    /**
     * Make request to API Vetmanager
     * $method - HTTP method
     * $model - API model (client or pet or another)
     * $query - query object or array
     * $id integer|null
     */
    private function _request($method, $model, $query = null, $id = null)
    {
        try {
            $requestBody = [
                'headers' => byApiKey($this->userApiKey)->asKeyValue(),
            ];
            if ($method == 'GET' && $query) {
                $requestBody['query'] = $query->asKeyValue();
            } elseif (in_array($method, ['POST', 'PUT']) && is_array($query)) {
                $requestBody['form_params'] = $query;
            }
            $uri = ($id) ? uri($model, $id)->asString() : uri($model)->asString();
    
            $client = new Client([
                'base_uri' => $this->userUrl
            ]);
            $response = $client->request(
                $method,
                $uri,
                $requestBody,
            );
            $response = (string)$response->getBody();
            $data = json_decode($response, true);
    
            return $data;

        } catch (Throwable $e) {
            report($e->getMessage());
        }
    }
}
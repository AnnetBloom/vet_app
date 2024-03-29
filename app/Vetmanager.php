<?php

namespace App;

use Illuminate\Http\Request;
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
 * Class Vetmanager
 * 
 * @see https://github.com/otis22/vetmanager-rest-api
 * 
 * $model - client|pet
 */

class Vetmanager 
{
    /**
     * base_uri
     * @var string
     */
    private $userUrl;

    /**
     * headers param
     * @var string
     */
    private $userApiKey;

    /**
     * Limit per page
     * 
     * @var int
     */
    private $limit = 50;

    /**
     * Init headers param and base_uri
     * 
     * @param string $url User url
     * @param string $key User key
     */
    public function __construct(string $url, string $key)
    {
        $this->userUrl = $url;
        $this->userApiKey = $key;
    }

    /**
     * API models array
     * 
     * @return array<string, string>
     */
    private function model()
    {
        return [
            'client' => 'client',
            'pet' => 'pet',
        ];
    }
    
    
    /**
     * Get PagedQuery items
     * 
     * @param \Illuminate\Http\Request $request
     * @param string $model API model
     * @param array $fieldsArray array(model field => value)
     * @param int|null $limitP
     * @return Illuminate\Pagination\LengthAwarePaginator
     * 
     *  {{Domain URL}}/rest/api/client
     *  {{Domain URL}}/rest/api/pet
     */
    public function getLimited(Request $request, string $model, $fieldsArray = [], $limitP = null)
    {
        $page = $request->page ?: 1;
        $limit = $limitP ?: $this->limit;
        $filters = new Filters();
        $sorts = new Sorts(
            new AscBy(
                new Property('id')
            )
        );

        if ($model == $this->model()['client']) {
            $filters = $this->searchClient($request);
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
        $response = $this->request('GET', $model, $query);

        if (isset($response['data']['totalCount'])) {
            $paginator = new LengthAwarePaginator(
                $response['data'][$model], $response['data']['totalCount'], $limit, $page, ['path' => request()->fullUrl()]
            );
            
        }
        return $paginator;
    }

    /**
     * GET by id
     * 
     * @param string $model string API model 
     * @param int $id
     * @return array response
     */
    public function getById($model, $id)
    {
        $response = $this->request('GET', $model, null, $id);
        if (isset($response['data'][$model])) {
            return $response['data'][$model];
        }
        return [];
    }

    /**
     * Create request
     * 
     * @param string $model API model
     * @param array $data 
     * @return array response
     */
    public function createRequest($model, $data)
    {
        return $this->request('POST', $model, $data);
    }

    /**
     * Update request
     * 
     * @param string $model API model
     * @param array $data
     * @param int $id
     * @return array response
     */
    public function updateRequest($model, $data, $id)
    {
        return $this->request('PUT', $model, $data, $id);
    }

    /**
     * Delete request
     * 
     * @param string $model API model
     * @param int $id 
     * @return array response
     */
    public function deleteRequest($model, $id)
    {
        return $this->request('DELETE', $model, null, $id);
    }

    /**
     * Client search filters
     * 
     * @param \Illuminate\Http\Request $request
     * @return Otis22\VetmanagerRestApi\Query\Filters
     */
    private function searchClient(Request $request)
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
     * 
     * @param int $client_id
     * @return array{result:string, bool}
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
        $ownerPets = $this->request('GET', $this->model()['pet'], $filters);

        if (isset($ownerPets['data']['pet'])) {
            $petDeleteFails = [];
            foreach ($ownerPets['data']['pet'] as $v) {
                // Delete pet
                $response = $this->request('DELETE', $this->model()['pet'], null, $v['id']);
                if (!isset($response['success']) || !$response['success']) {
                    $petDeleteFails[] = $v['id'];
                }
            }

            if (empty($petDeleteFails)) {
                // Delete client itself
                $response = $this->request('DELETE', $this->model()['client'], null, $client_id);
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
     * 
     * @param string $method HTTP method
     * @param string $model API model (client or pet or another)
     * @param Otis22\VetmanagerRestApi\Query\PagedQuery|Otis22\VetmanagerRestApi\Query\Filters|array|null $query
     * @param int|null $id
     * 
     * @throw Throwable
     * @return array response
     */
    private function request($method, $model, $query = null, $id = null)
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
<?php

namespace App\Util;

use App\Models\EventSearch;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class EventSearchApi
{

    public function __construct(private HttpClientInterface $client)
    {
    }

    public function search(EventSearch $searchEvent) : array
    {
        $url = 'https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/evenements-publics-openagenda/records?limit=20';
        $response = $this->client->request(
            'GET',
            $url,
            [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'query' => [
                    'where' => 'location_city="' . ucfirst($searchEvent->city).'"'
                        .' and firstdate_begin >= "' . $searchEvent->dateEvent->format('Y/m/d'). '"',
                    'order_by' => 'firstdate_begin ASC'
                ],
            ]
        );
        if ($response->getStatusCode() == Response::HTTP_OK) {
            return $response->toArray()['results'];
        }
        else {
            return [];
        }
    }
}
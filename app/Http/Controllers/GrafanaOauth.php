<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Support\Str;

use Illuminate\Http\Request;

class GrafanaOauth extends Controller
{

    public function checkOrg(Request $request)
    {
        $userLogin = $request->user()->username;
        $header = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $client = new Client([
            'base_uri' => env('GRAFANA_URL'),
            'auth' => [env('GRAFANA_USER'), env('GRAFANA_PASSWORD')],
            'headers' =>  $header
        ]);
        try {
            $response = $client->get("/api/users/lookup?loginOrEmail={$userLogin}")->getBody();
            $data = json_decode($response, true);
        } catch (\Throwable $th) {
            $this->createGrafanaUser($request);
            $data = [];
        }
        return $data;
    }

    public function updateUserOrg(Request $request, $data)
    {
        $header = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer ".env('GRAFANA_KEY')
        ];
        $client = new Client([
            'base_uri' => env('GRAFANA_URL'),
            'headers' =>  $header
        ]);

        try {
            $promise = $client->post("/api/orgs/{$data['orgId']}/users", [
                'json' => [
                    'role' => 'Viewer',
                    'loginOrEmail' => $request->user()->username
                ]
            ]);
        } catch (\Throwable $th) {
        }
    }

    public function createGrafanaUser(Request $request)
    {
        try {
            $header = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ];

            $client = new Client([
                'base_uri' => env('GRAFANA_URL'),
                'auth' => [env('GRAFANA_USER'), env('GRAFANA_PASSWORD')],
                'headers' =>  $header
            ]);

            $response = $client->post('/api/admin/users', [
                'json' => [
                    'name' => $request->user()->last_name,
                    'login' => $request->user()->username,
                    'email' => $request->user()->email ? $request->user()->email : $request->user()->username . '@' . 'sis.moe.gov.lk',
                    'password' =>  Str::random(8),
                ]
            ])->getBody();
            $data = json_decode($response, true);
            return $data;
        } catch (\Throwable $th) {
        }
    }

    public function removeUserMainOrg($data)
    {
        try {
            $header = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ];
            $client = new Client([
                'base_uri' => env('GRAFANA_URL'),
                'auth' => [env('GRAFANA_USER'), env('GRAFANA_PASSWORD')],
                'headers' =>  $header
            ]);

            $client->delete("/api/orgs/1/users/{$data['user']['id']}");
        } catch (\Throwable $th) {
        }
    }

    public function getUserOrg(Request $request)
    {
        if ($request->user()->super_admin) {
            $data = [
                [
                    "orgId" => 2,
                    "userId" => $request->user()->id,
                    "role" => 'Admin',
                    "name" => 'Schools',
                    "email" => $request->user()->username,
                    "login" => 'Schools',
                ],
                [
                    "orgId" => 4,
                    "userId" => $request->user()->id,
                    "role" => 'Admin',
                    "name" => 'Zones',
                    "email" => $request->user()->username,
                    "login" => 'Zones',
                ],
                [
                    "orgId" => 5,
                    "userId" => $request->user()->id,
                    "role" => 'Admin',
                    "name" => 'Provinces',
                    "email" => $request->user()->username,
                    "login" => 'Provinces',
                ]
            ];
        } elseif ($request->user() &&  (!($request->user()->principal->isEmpty()))  && !is_null($request->user()->principal) && ($request->user()->principal[0]->roles->code == 'PRINCIPAL')) {
            $data = $this->checkOrg($request);
            if (empty($data) || ((!empty($data['orgId']) && $data['orgId'] !== 2))) {
                $request['data'] = $data;
                $data['user'] = $data;
                $data['orgId'] = 2;
                $this->updateUserOrg($request, $data);
            }
            $data = [
                [
                    "orgId" => 2,
                    "userId" => $request->user()->id,
                    "role" => 'Viewer',
                    "name" => 'Schools',
                    "email" => $request->user()->username,
                    "login" => 'Schools',
                ]
            ];
        } elseif ($request->user()  &&  (!($request->user()->zonal_cordinator->isEmpty())) && !is_null($request->user()->zonal_cordinator) && ($request->user()->zonal_cordinator[0]->roles->code == 'ZONAL_COORDINATOR')) {
            $data = $this->checkOrg($request);
            if (empty($data) || ((!empty($data['orgId']) && $data['orgId'] !== 4))) {
                $request['data'] = $data;
                $data['user'] = $data;
                $data['orgId'] = 4;
                $this->updateUserOrg($request, $data);
            }
            $data = [
                [
                    "orgId" => 4,
                    "userId" => $request->user()->id,
                    "role" => 'Viewer',
                    "name" => 'Zones',
                    "email" => $request->user()->username,
                    "login" => 'Zones',
                ]
            ];
        } elseif ($request->user()  &&  (!is_null($request->user()->zonal_cordinator) && ($request->user()->zonal_cordinator[0]->roles->code == 'ZONAL_COORDINATOR'))) {
            $data = $this->checkOrg($request);
            if (empty($data)  || ((!empty($data['orgId']) && $data['orgId'] !== 5))) {
                $request['data'] = $data;
                $data['user'] = $data;
                $data['orgId'] = 5;
                $this->updateUserOrg($request, $data);
            }
            $data = [
                [
                    "orgId" => 5,
                    "userId" => $request->user()->id,
                    "role" => 'Viewer',
                    "name" => 'Provinces',
                    "email" => $request->user()->username,
                    "login" => 'Provinces',
                ]
            ];
        }
        return $data;
    }
}

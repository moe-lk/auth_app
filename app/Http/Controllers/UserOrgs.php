<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use function GuzzleHttp\Psr7\stream_for;

class UserOrgs extends Controller
{

    public function checkOrg(Request $request)
    {
        $userLogin = $request->user()->email;
        $header = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $client = new Client([
            'base_uri' => env('GRAFANA_URL'),
            'auth' => [env('GRAFANA_USER'), env('GRAFANA_PASSWORD')],
        ]);
        try {
            $response = $client->request('GET', "/api/users/lookup?loginOrEmail={$userLogin}", [
                'headers' =>  $header
            ])->getBody();
            $data = json_decode($response, true);
        } catch (\Throwable $th) {
            $data = [];
        }
        return $data;
    }

    public function updateUserOrg(Request $request, $data)
    {
        $header = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
        $client = new Client([
            'base_uri' => env('GRAFANA_URL'),
            'auth' => [env('GRAFANA_USER'), env('GRAFANA_PASSWORD')],
        ]);
        try {
            $client->request('POST', "/api/orgs/{$data['orgId']}/users", [
                'auth' => [env('GRAFANA_USER'), env('GRAFANA_PASSWORD')],
                'headers' =>  $header,
                'json' => [
                    'role' => 'Viewer',
                    'loginOrEmail' => $request->user()->username
                ]
            ]);
        } catch (\Throwable $th) {
            $this->createGrafanaUser($request);
            $this->updateUserOrg($request, $data);
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
            ]);
            $response = $client->request('POST', '/api/admin/users', [
                'headers' =>  $header,
                'json' => [
                    'name' => $request->user()->last_name,
                    'login' => $request->user()->username,
                    'email' => $request->user()->email,
                    'password' =>  Str::random(8),
                ]
            ])->getBody();
            $data = json_decode($response, true);
            $this->removeUserMainOrg($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function removeUserMainOrg($data)
    {
        $header = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
        $client = new Client([
            'base_uri' => env('GRAFANA_URL'),
            'auth' => [env('GRAFANA_USER'), env('GRAFANA_PASSWORD')],
        ]);
        $client->request('DELETE', "/api/orgs/1", [
            'auth' => [env('GRAFANA_USER'), env('GRAFANA_PASSWORD')],
            'headers' =>  $header
        ]);
    }

    public function getUserOrg(Request $request)
    {
        if ($request->user() &&  (!($request->user()->principal->isEmpty()))  && !is_null($request->user()->principal) && ($request->user()->principal[0]->roles->code == 'PRINCIPAL')) {
            $data = $this->checkOrg($request);
            if (empty($data) || ( (!empty($data['orgId']) && $data['orgId'] !== 2))) {
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
                    "name" => 'schools',
                    "email" => $request->user()->username,
                    "login" => 'schools',
                ]
            ];
        } elseif ($request->user()  &&  (!($request->user()->zonal_cordinator->isEmpty())) && !is_null($request->user()->zonal_cordinator) && ($request->user()->zonal_cordinator[0]->roles->code == 'ZONAL_COORDINATOR')) {
            $data = $this->checkOrg($request);
            if (empty($data) || ((!empty($data['orgId']) && $data['orgId'] !== 3))) {
                $request['data'] = $data;
                $data['user'] = $data;
                $data['orgId'] = 3;
                $this->updateUserOrg($request, $data);
            }
            $data = [
                [
                    "orgId" => 3,
                    "userId" => $request->user()->id,
                    "role" => 'Viewer',
                    "name" => 'zone',
                    "email" => $request->user()->username,
                    "login" => 'zone',
                ]
            ];
        } elseif ($request->user() &&  (!($request->user()->provincial_cordinator->isEmpty())) && !is_null($request->user()->provincial_cordinator) && ($request->user()->provincial_cordinator[0]->roles->code == 'PROVINCIAL_COORDINATOR')) {
            $data = $this->checkOrg($request);
            if (empty($data)  || ((!empty($data['orgId']) && $data['orgId'] !== 4))) {
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
                    "name" => 'province',
                    "email" => $request->user()->username,
                    "login" => 'province',
                ]
            ];
        }
        return $data;
    }
}

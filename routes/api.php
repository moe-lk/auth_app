<?php

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        $data['email'] = $request->user()->email ? $request->user()->email : $request->user()->username.'@'.'sis.moe.gov.lk' ;
        $data['name'] = $request->user()->last_name;
        $data['id'] = 8553;
        $data['username'] = $request->user()->username;
        $data['info'] = [
            "groups" => [
                'school',
            ],
            'company' => [
                "orgId" => 2,
                "userId" => $request->user()->id,
                "role" => 'Viewer',
                "name" => 'Schools',
                "email" => $request->user()->email ? $request->user()->email : $request->user()->username.'@'.'sis.moe.gov.lk' ,
                "login" => 'Schools',
            ],
            'role' => [
                'Viewer'
            ]
        ];
        return response()->json($data);
    });
    Route::get('/user/teams', function (Request $request) {
        return
            [
                [
                    "id" => 2,
                    "orgId" => 2,
                    "userId" => $request->user()->id,
                    "role" => 'Viewer',
                    "name" => 'schools',
                    "email" => 'schools@sis.moe.gov.lk',
                    "login" => 'schools',
                    'ids' => [2]
                ]
            ];
    });
    Route::get('/user/orgs','GrafanaOauth@getUserOrg');

});





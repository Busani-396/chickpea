<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Resources\ClientResource;
use App\Http\Resources\UserResource;
use App\Models\Client;
use App\Models\ClientUser;
use App\Models\User;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //return response(['name'=>'client'], 200, ['accept'=>'application/json']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        $client = Client::create([
            'name' => $request->name,
        ]);

        if(!Auth::user()){
            throw new Exception('Login first');
        }

        ClientUser::create([
            'client_id' => $client->id,
            'user_id' => Auth::user()->id
        ]);

        return $this->success(new ClientResource($client), 'Client created successfully',201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        //
    }
}

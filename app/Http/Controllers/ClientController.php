<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteClientRequest;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\ResponseFactory;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Inertia\Response|ResponseFactory
     */
    public function index(Request $request)
    {
        $clients = ClientResource::collection(Client::orderBy('id')->paginate());

        if ($request->wantsJson()) {
            return $clients;
        }

        return inertia('Client/Index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response|ResponseFactory
     */
    public function create()
    {
        return inertia('Client/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreClientRequest  $request
     * @return Response
     */
    public function store(StoreClientRequest $request)
    {
        $country = $request->country();

        $client = Client::create(array_merge(
            $request->safe()->except('country'),
            [
                'country_id' => $country->id,
            ]
        ));

        return ClientResource::make($client);
    }

    /**
     * Display the specified resource.
     *
     * @param  Client  $client
     * @return Client
     */
    public function show(Client $client)
    {
        return ClientResource::make($client);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Client  $client
     * @return \Inertia\Response|ResponseFactory
     */
    public function edit(Client $client)
    {
        $countries = Country::all();

        $client->load('country');

        return inertia('Client/Edit', compact('client', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateClientRequest  $request
     * @param  Client  $client
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function update(UpdateClientRequest $request, Client $client)
    {

        $country = $request->country();

        $client->update(array_merge(
            $request->safe()->except('country'),
            [
                'country_id' => $country->id,
            ]
        ));

        if ($request->wantsJson()) {
            return response()->noContent();
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DeleteClientRequest  $request
     * @param  Client  $client
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function destroy(DeleteClientRequest $request, Client $client)
    {
        $client->delete();

        if ($request->wantsJson()) {
            return response()->noContent();
        }

        return back();
    }
}

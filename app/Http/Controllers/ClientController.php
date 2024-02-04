<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreClientRequest;
use App\Vetmanager;

class ClientController extends Controller
{
    public $model = 'client';

    public function index(Request $request)
    {
        $vet = new Vetmanager();
        $clients = $vet->getAll($request, $this->model);

        return view('client.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $vet = new Vetmanager();
        $cities = $vet->getAll($request, 'city', [], 50);

        return view('client.create', compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        $validated = $request->validated();
        $vetRequest = (new Vetmanager())->createRequest($this->model, $validated);
        
        if (isset($vetRequest['success']) && $vetRequest['success']) {
            session()->flash('success', __('main.saved'));
        } else {
            session()->flash('error', __('main.error'));
        }
        return redirect(route('dashboard'));
    }
    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $vet = new Vetmanager();
        $client = $vet->getById($this->model, $id);

        $filters = [
            'owner_id' => $id
        ];
        $pets = $vet->getAll($request, 'pet', $filters);
        return view('client.show', compact('client', 'id', 'pets'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, Request $request)
    {
        $vet = new Vetmanager();
        $client = $vet->getById($this->model, $id);
        $cities = $vet->getAll($request, 'city', [], 0);
        
        return view('client.edit', compact('client', 'id', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreClientRequest $request, string $id)
    {
        $validated = $request->validated();
        $vetRequest = (new Vetmanager())->updateRequest($this->model, $validated, $id);
        if (isset($vetRequest['success']) && $vetRequest['success']) {
            session()->flash('success', __('main.saved'));
        } else {
            session()->flash('error', __('main.server_error'));
            return redirect(route('clients.edit', [$id]));
        }
        return redirect(route('dashboard'));
    }

    /**
     * Удалить всех питомцев, потом самого клиента
     */
    public function destroy(string $id)
    {
        $response = (new Vetmanager())->deleteClient($id);
        if ($response['result']) {
            session()->flash('success', __('main.deleted')); 
        } else {
            $message = __('main.deleted_error');
            if (isset($response['fails']) && !empty($response['fails'])) {
                $count = count($response['fails']);
                $message .= trans_choice('main.pets', $count) . ' ' . __('main.fails_pets', [ 'ids' => implode(', ', $response['fails']) ]) . trans_choice('main.pets_deleted', $count);
            }
            session()->flash('error', $message); 
        }
        
        return redirect(route('dashboard'));
    }
}

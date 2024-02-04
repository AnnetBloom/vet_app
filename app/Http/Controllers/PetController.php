<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StorePetRequest;
use App\Vetmanager;

class PetController extends Controller
{
    public $model = 'pet';

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $vet = new Vetmanager();
        $petTypes = $vet->getAll($request, 'petType');

        return view('pet.edit', compact('petTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePetRequest $request)
    {
        $validated = $request->validated();
        $response = (new Vetmanager())->createRequest($this->model, $validated);

        if (isset($response['success']) && $response['success']) {
            session()->flash('success', __('main.saved'));
        } else {
            session()->flash('error', __('main.error'));
        }
        return redirect(route('clients.show', $validated['owner_id'] ));
        //return redirect(route('pets.edit', $response['data']['pet'][0]['id']));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $vet = new Vetmanager();
        $pet = $vet->getById($this->model, $id);

        return view('pet.show', compact('pet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, Request $request)
    {
       $vet = new Vetmanager();
       $petTypes = $vet->getAll($request, 'petType');
       $pet = $vet->getById($this->model, $id);

       return view('pet.edit', compact('pet', 'id', 'petTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePetRequest $request, string $id)
    {
        $validated = $request->validated();
        $response = (new Vetmanager())->updateRequest($this->model, $validated, $id);
        if (isset($response['success']) && $response['success']) {
            session()->flash('success', __('main.saved'));
        } else {
            session()->flash('error', __('main.error'));
        }
        return redirect(route('clients.show', $validated['owner_id'] ));
        //return redirect(route('pets.edit', [$id]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $response = (new Vetmanager())->deleteRequest($this->model, $id);

        if ($response) {
            session()->flash('success', __('main.deleted'));
        } else {
            session()->flash('error', __('main.error'));
        }
        return redirect(url()->previous());
    }
}

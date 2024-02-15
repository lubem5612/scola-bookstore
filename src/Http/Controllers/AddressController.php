<?php


namespace Transave\ScolaBookstore\Http\Controllers;


use Illuminate\Http\Request;
use Transave\ScolaBookstore\Actions\Address\CreateAddress;
use Transave\ScolaBookstore\Actions\Address\DeleteAddress;
use Transave\ScolaBookstore\Actions\Address\SearchAddress;
use Transave\ScolaBookstore\Actions\Address\UpdateAddress;
use Transave\ScolaBookstore\Http\Models\Address;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        return (new SearchAddress(Address::class, ['user']))->execute();
    }

    public function store(Request $request)
    {
        return (new CreateAddress($request->all()))->execute();
    }

    public function show($id)
    {
        return (new SearchAddress(Address::class, ['user'], $id))->execute();
    }

    public function update(Request $request, $id)
    {
        $data = $request->merge(['address_id' => $id])->all();
        return (new UpdateAddress($data))->execute();
    }

    public function destroy($id)
    {
        return (new DeleteAddress(['address_id' => $id]))->execute();
    }
}
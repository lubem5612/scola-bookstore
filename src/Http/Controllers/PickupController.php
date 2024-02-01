<?php

namespace Transave\ScolaBookstore\Http\Controllers;

use Illuminate\Http\Request;
use Transave\ScolaBookstore\Actions\Pickup\SearchPickup;
use Transave\ScolaBookstore\Actions\Pickup\UpdatePickup;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Http\Models\Pickup;


class PickupController extends Controller
{
    use ResponseHelper;


    public function index()
    {
        return (new SearchPickup(Pickup::class, ['state', 'order', 'lg', 'country']))->execute();
    }


    public function show($id)
    {
        return (new SearchPickup(Pickup::class, ['state', 'order', 'lg', 'country'], $id))->execute();
    }


    public function update(Request $request, $id)
    {
        $inputs = $request->merge(['pickup_id' => $id])->all();
        return (new UpdatePickup($inputs))->execute();
    }

}
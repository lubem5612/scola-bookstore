<?php


use Transave\ScolaBookstore\Http\Models\Country;
use Transave\ScolaBookstore\Http\Models\Lg;
use Transave\ScolaBookstore\Http\Models\State;

class StateTableSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = config("constants.states");
        $country = Country::query()->where('name', 'Nigeria')->first();
        foreach ($states as $index => $state) {
            $st = State::query()->updateOrCreate([
                'country_id' => $country->id,
                'name' => $state['name']
            ], [
                'country_id' => $country->id,
                'name' => $state['name'],
                'capital' => $state['capital']
            ]);
            foreach ($state['lga'] as $lg) {
                Lg::query()->updateOrCreate([
                    'name' => $lg,
                ], [
                    'state_id' => $st->id,
                    'name' => $lg,
                ]);
            }
        }
    }
}
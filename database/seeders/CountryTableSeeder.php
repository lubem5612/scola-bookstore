<?php


use Transave\ScolaBookstore\Http\Models\Country;

class CountryTableSeeder
{
    public function run()
    {
        $countries = config('constants.countries');
        foreach ($countries as $index => $country) {
            Country::query()->updateOrCreate([ 'name' => $country['country']], ['code' => $index, 'name' => $country['country'], 'continent' => $country['continent'] ]);
        }
    }
}
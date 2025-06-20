<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        State::create(['state' => 'AL']);
        State::create(['state' => 'AK']);
        State::create(['state' => 'AZ']);
        State::create(['state' => 'AR']);
        State::create(['state' => 'CA']);
        State::create(['state' => 'CO']);
        State::create(['state' => 'CT']);
        State::create(['state' => 'DE']);
        State::create(['state' => 'DC']);
        State::create(['state' => 'FL']);
        State::create(['state' => 'GA']);
        State::create(['state' => 'HI']);
        State::create(['state' => 'ID']);
        State::create(['state' => 'IL']);
        State::create(['state' => 'IN']);
        State::create(['state' => 'IA']);
        State::create(['state' => 'KS']);
        State::create(['state' => 'KY']);
        State::create(['state' => 'LA']);
        State::create(['state' => 'ME']);
        State::create(['state' => 'MD']);
        State::create(['state' => 'MA']);
        State::create(['state' => 'MI']);
        State::create(['state' => 'MN']);
        State::create(['state' => 'MS']);
        State::create(['state' => 'MO']);
        State::create(['state' => 'MT']);
        State::create(['state' => 'NE']);
        State::create(['state' => 'NV']);
        State::create(['state' => 'NH']);
        State::create(['state' => 'NJ']);
        State::create(['state' => 'NM']);
        State::create(['state' => 'NY']);
        State::create(['state' => 'NC']);
        State::create(['state' => 'ND']);
        State::create(['state' => 'OH']);
        State::create(['state' => 'OK']);
        State::create(['state' => 'OR']);
        State::create(['state' => 'PA']);
        State::create(['state' => 'RI']);
        State::create(['state' => 'SC']);
        State::create(['state' => 'SD']);
        State::create(['state' => 'TN']);
        State::create(['state' => 'TX']);
        State::create(['state' => 'UT']);
        State::create(['state' => 'VT']);
        State::create(['state' => 'VA']);
        State::create(['state' => 'WA']);
        State::create(['state' => 'WV']);
        State::create(['state' => 'WI']);
        State::create(['state' => 'WY']);
    }
}

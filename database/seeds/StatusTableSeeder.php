<?php

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		Status::create(['name' => 'inactive']);
        Status::create(['name' => 'active']);
        Status::create(['name' => 'deactivated']);
        Status::create(['name' => 'delete']);
        Status::create(['name' => 'remindpassword']);
    }
}

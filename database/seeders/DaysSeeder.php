<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class DaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
			
        DB::table('days')->insert(['title'=>'Sun','num'=>'7']);
		DB::table('days')->insert(['title'=>'Mon','num'=>'1']);
		DB::table('days')->insert(['title'=>'Tue','num'=>'2']);
		DB::table('days')->insert(['title'=>'Wed','num'=>'3']);
		DB::table('days')->insert(['title'=>'Thu','num'=>'4']);
		DB::table('days')->insert(['title'=>'Fri','num'=>'5']);
		DB::table('days')->insert(['title'=>'Sat','num'=>'6']);
    }
}

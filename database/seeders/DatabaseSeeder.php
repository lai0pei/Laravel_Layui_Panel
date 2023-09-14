<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AuthSubMenu::class,  
            AuthMenu::class,
            DefaultRole::class, 
            DefaultAdmin::class,    
            DefaultSystemConfig::class,
        ]);
    }
}

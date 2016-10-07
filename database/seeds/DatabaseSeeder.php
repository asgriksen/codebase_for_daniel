<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Tables (by environment) that need to be trucnated.
     *
     * @var array
     */
    protected $tables_to_truncate = [
        'local' => [
            'accounts',
            'actions',
            'ads',
            'ad_products',
            'ad_sets',
            'campaigns',
            'conditions',
            'metrics',
            'notifications',
            'products',
            'rules',
            'rule_applications',
            'tasks',
            'users'
        ],
        'production' => [

        ]
    ];

    /**
     * Seeders (by environment) that need to be called.
     *
     * @var array
     */
    protected $seeders_to_call = [
        'local' => [
            'LocalConditionsTableSeeder',
            'LocalUsersTableSeeder'
        ],
        'production' => [

        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Truncate the appropriate tables.
        //
        $this->truncateTables();

        // Call the appropriate seeders.
        //
        $this->callSeeders();

        Model::reguard();
    }

    /**
     * Truncate database tables.
     *
     * @return void
     */
    public function truncateTables()
    {
        $environment = app()->environment();

        foreach( $this->tables_to_truncate[$environment] as $table )
        {
            DB::table( $table )->truncate();
        }
    }

    /**
     * Truncate database tables.
     *
     * @return void
     */
    public function callSeeders()
    {
        $environment = app()->environment();

        foreach( $this->seeders_to_call[$environment] as $seeder )
        {
            $this->call( $seeder );
        }
    }
}

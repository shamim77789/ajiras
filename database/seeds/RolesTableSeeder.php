<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [

                'slug' => 'admin',
                'name' => 'Admin',
                'is_system' => 1,
                'permissions' => '{"members":true,"members.view":true,"members.update":true,"members.delete":true,"members.create":true,"events":true,"events.create":true,"events.update":true,"events.delete":true,"events.view":true,"payroll":true,"payroll.view":true,"payroll.update":true,"payroll.delete":true,"payroll.create":true,"expenses":true,"expenses.view":true,"expenses.create":true,"expenses.update":true,"expenses.delete":true,"other_income":true,"other_income.view":true,"other_income.create":true,"other_income.update":true,"other_income.delete":true,"reports":true,"communication":true,"communication.create":true,"communication.delete":true,"custom_fields":true,"custom_fields.view":true,"custom_fields.create":true,"custom_fields.update":true,"custom_fields.delete":true,"users":true,"users.view":true,"users.create":true,"users.update":true,"users.delete":true,"users.roles":true,"settings":true,"audit_trail":true,"dashboard":true,"dashboard.members_statistics":true,"dashboard.calendar":true,"dashboard.contributions_statistics":true,"dashboard.pledges_statistics":true,"dashboard.finance_graph":true,"dashboard.tags_statistics":true,"assets":true,"assets.create":true,"assets.view":true,"assets.update":true,"assets.delete":true,"tags":true,"tags.view":true,"tags.create":true,"tags.update":true,"tags.delete":true,"follow_ups":true,"follow_ups.view":true,"follow_ups.create":true,"follow_ups.update":true,"follow_ups.delete":true,"pledges":true,"pledges.view":true,"pledges.create":true,"pledges.update":true,"pledges.delete":true,"contributions":true,"contributions.view":true,"contributions.create":true,"contributions.update":true,"contributions.delete":true,"branches":true,"branches.view":true,"branches.create":true,"branches.update":true,"branches.delete":true,"branches.view_all":true}'
            ]
        ]);
    }
}

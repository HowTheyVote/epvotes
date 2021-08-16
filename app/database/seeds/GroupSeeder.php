<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupSeeder extends Seeder
{
    const GROUPS = [
        [
            'code' => 'EPP',
            'name' => 'European People\'s Party',
            'abbreviation' => 'EPP',
        ],
        [
            'code' => 'NI',
            'name' => 'Non-attached members',
            'abbreviation' => 'Non-attached',
        ],
        [
            'code' => 'ID',
            'name' => 'Identity and Democracy',
            'abbreviation' => 'ID',
        ],
        [
            'code' => 'SD',
            'name' => 'Progressive Alliance of Socialists and Democrats',
            'abbreviation' => 'S&D',
        ],
        [
            'code' => 'ECR',
            'name' => 'European Conservatives and Reformists',
            'abbreviation' => 'ECR',
        ],
        [
            'code' => 'GREENS',
            'name' => 'The Greens/European Free Aliance',
            'abbreviation' => 'Greens/EFA',
        ],
        [
            'code' => 'RENEW',
            'name' => 'Renew Europe',
            'abbreviation' => 'Renew',
        ],
        [
            'code' => 'LEFT',
            'name' => 'The Left in the European Parliament – GUE/NGL',
            'abbreviation' => 'The Left',
        ],
        [
            'code' => 'GUE',
            'name' => 'Group of the European United Left - Nordic Green Left',
            'abbreviation' => 'GUE/NGL',
        ],
        [
            'code' => 'EFDD',
            'name' => 'Europe of Freedom and Direct Democracy',
            'abbreviation' => 'EFDD',
        ],
        [
            'code' => 'ALDE',
            'name' => 'Alliance of Liberals and Democrats for Europe',
            'abbreviation' => 'ALDE',
        ],
        [
            'code' => 'ENF',
            'name' => 'Europe of Nations and Freedom',
            'abbreviation' => 'ENF',
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::GROUPS as $group) {
            DB::table('groups')->upsert($group, 'code');
        }
    }
}

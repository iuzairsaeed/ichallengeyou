<?php

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::insert([
            [
                'name' => 'CURRENCY',
                'value' => '$',
                'type' => 'text',
            ],
            [
                'name' => 'PREMIUM_COST',
                'value' => 1,
                'type' => 'number',
            ],
            [
                'name' => 'TERMS_CONDITIONS',
                'value' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum scelerisque dignissim iaculis. Etiam quis tempor metus, in facilisis urna. Fusce sit amet consequat est.</p>
                            <ul>
                                <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                                <li>Curabitur semper purus et tempor interdum.</li>
                                <li>Proin suscipit nibh quis ex tincidunt, in pellentesque urna faucibus.</li>
                            </ul>
                            <ul>
                                <li>Quisque sit amet orci molestie, sodales sem eu, mattis quam.</li>
                                <li>Proin ac odio euismod, elementum ligula in, lobortis ante.</li>
                                <li>Donec id turpis maximus, sollicitudin neque egestas, venenatis enim.</li>
                                <li>Pellentesque fermentum risus ultrices tempus porttitor.</li>
                                <li>Nam sit amet purus varius nisi mollis efficitur.</li>
                                <li>Nullam porta nisl a diam accumsan, vitae maximus neque elementum.</li>
                            </ul>',
                'type' => 'textarea',
            ]
        ]);
    }
}

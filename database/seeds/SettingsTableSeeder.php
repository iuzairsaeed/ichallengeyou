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
            ],
            [
                'name' => 'FCM_SECRET_KEY',
                'value' => 'AAAAcG2KUA8:APA91bGODgsELMbG2xAMvAwddZ4WIQJhQHyBbs7sLb15hJUvLUkaLPm8YpY2_nSoG1iPERqyWOkIZI8A4v6II7I-NzyxkBhtZqtnLXYVotqW1p7m_6O6p4Lk2x8d0nLPHkwNMw986WQC',
                'type' => 'textarea',
            ],
            [
                'name' => 'PAYPAL_CLIENT_ID',
                'value' => 'Ab53_-WMeShRUNx6lvevKNgqpJYIvM46DRTAuuTzIN_l2QXBPi4d11xgkVRn67bspowmWc6WFClmTv_N',
                'type' => 'text',
            ],
            [
                'name' => 'PAYPAL_CLIENT_SECRET',
                'value' => 'EEGikJpagcxHKy6abaKbDN1VgpS61ERo4owIbrlvumcFHlQIYayFZvD9OQnYZmUojNpOjcblDfUyj3Ge',
                'type' => 'text',
            ],
            [
                'name' => 'SECOND_VOTE_DURATION_IN_DAYS',
                'value' => 2,
                'type' => 'number',
            ],
        ]);
    }
}

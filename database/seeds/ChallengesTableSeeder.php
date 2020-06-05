<?php

use Illuminate\Database\Seeder;
use App\Models\Challenge;
use App\Models\Amount;
use Carbon\Carbon;

class ChallengesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        # Challenge 1
        $challenge = Challenge::create([
            'user_id' => 1,
            'category_id' => 1,
            'title' => 'Invade Stadium while Semi Naked',
            'description' => "  
                1- Invade Field after 15 minutes of main game started.
                2- Must be 10 Minutes before it ends.
                3- Reach at Least the middle of the field.
                4- Stadium must have at least 10.000 people.
                5- Must be on your Underwear.
                6- Game must be broadcasted on tv
                7- Must be older than 18 years old.
                8- Must paint big letters on your body promoting the App. I CHALLENGE YOU APP
            ",
            'start_time' => Carbon::createFromDate('2020','09','01'),
            'file' => 'football_ground.jpg',
            'location' => "Anywhere",
            'duration_days' => '00',
            'duration_hours' => "24",
            'duration_minutes' => "00",
            'created_at' => now()
        ]);
        $challenge->setStatus(Approved());
        $donation = new Amount([
            'user_id' => 1,
            'amount' => '100',
            'type' => 'initial',
            'created_at' => now()
        ]);
        $challenge->amounts()->save($donation);
        $donation = new Amount([
            'user_id' => 1,
            'amount' => '836',
            'type' => 'donation',
            'created_at' => now()
        ]);
        $challenge->amounts()->save($donation);
                
        # Challenge 2
        $challenge = Challenge::create([
            'user_id' => 1,
            'category_id' => 1,
            'title' => 'Shave your Head!',
            'description' => "  
                Change your style and raise awareness to Childrens Cancer Victims
                1- Must be Female.
                2- Must have hair longer than 35cm.
                3- Must do a live Video.
                4- Must be older than 18 years old
                5- Must Donate hair to Childrens Hospital fo Cancer patient and to Raise awareness.
            ",
            'start_time' => Carbon::createFromDate('2020','09','08'),
            'file' => 'shave_head.jpg',
            'location' => "Anywhere",
            'duration_days' => '00',
            'duration_hours' => "02",
            'duration_minutes' => "00",
            'created_at' => now()
        ]);
        $challenge->setStatus(Approved());
        $donation = new Amount([
            'user_id' => 1,
            'amount' => '432',
            'type' => 'initial',
            'created_at' => now()
        ]);
        $challenge->amounts()->save($donation);
        $donation = new Amount([
            'user_id' => 1,
            'amount' => '1404',
            'type' => 'donation',
            'created_at' => now()
        ]);
        $challenge->amounts()->save($donation);

        # Challenge 3
        $challenge = Challenge::create([
            'user_id' => 1,
            'category_id' => 1,
            'title' => 'Loose 20 kg',
            'description' => "  
                Become healthier and more good looking and get paid to do it!
                1- Loose 20 KG wihtout Surgery.
                2- Make Videos 3x a Week showing your face, body and scale results.
                3- Use always same Scale.
                4- Must be 100 KG at least to start Challenge.
            ",
            'start_time' => Carbon::createFromDate('2020','09','09'),
            'file' => 'loose_kg.jpg',
            'location' => "Anywhere",
            'duration_days' => '120',
            'duration_hours' => "00",
            'duration_minutes' => "00",
            'created_at' => now()
        ]);
        $challenge->setStatus(Approved());
        $donation = new Amount([
            'user_id' => 1,
            'amount' => '500',
            'type' => 'initial',
            'created_at' => now()
        ]);
        $challenge->amounts()->save($donation);
        $donation = new Amount([
            'user_id' => 1,
            'amount' => '16951',
            'type' => 'donation',
            'created_at' => now()
        ]);
        $challenge->amounts()->save($donation);
    }
}

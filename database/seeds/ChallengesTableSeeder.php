<?php

use Illuminate\Database\Seeder;
use App\Models\Challenge;
use App\Models\AcceptedChallenge;
use App\Models\Amount;
use App\Models\Bid;
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
        $faker = \Faker\Factory::create();
        # Challenge DEFAULT FOR TESTING
        $challenge = Challenge::create([
            'user_id' => 1,
            'category_id' => 1,
            'title' => 'TEST',
            'description' => 'TEST',
            'start_time' => now(), 
            'file' => 'football_ground.jpg',
            'location' => "WISCONSIN",
            'result_type' => 'vote',
            'duration_days' => '00',
            'duration_hours' => "01",
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
        $bid = new Bid([
            'user_id' => 2,
            'bid_amount' => 200,
        ]);
        $challenge->bids()->save($bid);

        # Challenge 1
        $challenge = Challenge::create([
            'user_id' => 1,
            'category_id' => 1,
            'title' => 'Invade Stadium while Semi Naked',
            'description' => "1- Invade Field after 15 minutes of main game started.\n2- Must be 10 Minutes before it ends.\n3- Reach at Least the middle of the field.\n4- Stadium must have at least 10.000 people.\n5- Must be on your Underwear.\n6- Game must be broadcasted on tv\n7- Must be older than 18 years old.\n8- Must paint big letters on your body promoting the App. I CHALLENGE YOU APP",
            'start_time' => '2020-09-01', 
            'file' => 'football_ground.jpg',
            'location' => "Anywhere",
            'result_type' => 'vote',
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
        $bid = new Bid([
            'user_id' => 2,
            'bid_amount' => 200,
        ]);
        $challenge->bids()->save($bid);
        $bid = new Bid([
            'user_id' => 3,
            'bid_amount' => 200,
        ]);
        $challenge->bids()->save($bid);
                
        # Challenge 2
        $challenge = Challenge::create([
            'user_id' => 1,
            'category_id' => 6,
            'title' => 'Shave your Head!',
            'description' => "Change your style and raise awareness to Childrens Cancer Victims\n1- Must be Female.\n2- Must have hair longer than 35cm.\n3- Must do a live Video.\n4- Must be older than 18 years old\n5- Must Donate hair to Childrens Hospital fo Cancer patient and to Raise awareness.",
            'start_time' => '2020-09-08',
            'file' => 'shave_head.jpg',
            'location' => "Anywhere",
            'result_type' => 'vote',
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
        $bid = new Bid([
            'user_id' => 2,
            'bid_amount' => 200,
        ]);
        $challenge->bids()->save($bid);
        $bid = new Bid([
            'user_id' => 3,
            'bid_amount' => 200,
        ]);
        $challenge->bids()->save($bid);

        # Challenge 3
        $challenge = Challenge::create([
            'user_id' => 1,
            'category_id' => 7,
            'title' => 'Loose 20 kg',
            'description' => "Become healthier and more good looking and get paid to do it!\n1- Loose 20 KG wihtout Surgery.\n2- Make Videos 3x a Week showing your face, body and scale results.\n3- Use always same Scale.\n4- Must be 100 KG at least to start Challenge.",
            'start_time' => '2020-09-09',
            'file' => 'loose_kg.jpg',
            'location' => "Anywhere",
            'result_type' => 'vote',
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
        $bid = new Bid([
            'user_id' => 2,
            'bid_amount' => 200,
        ]);
        $challenge->bids()->save($bid);
        $bid = new Bid([
            'user_id' => 3,
            'bid_amount' => 200,
        ]);
        $challenge->bids()->save($bid);
        # Challenge 4
        $challenge = Challenge::create([
            'user_id' => 1,
            'category_id' => 1,
            'title' => 'DUMMY VIDEO CHALLENGE',
            'description' => "Become healthier and more good looking and get paid to do it!\n1- Loose 20 KG wihtout Surgery.\n2- Make Videos 3x a Week showing your face, body and scale results.\n3- Use always same Scale.\n4- Must be 100 KG at least to start Challenge.",
            'start_time' => '2020-09-10',
            'file' => 'dummy.mp4',
            'location' => "Anywhere",
            'duration_days' => '120',
            'duration_hours' => "00",
            'duration_minutes' => "00",
            'created_at' => now()
        ]);
        $challenge->setStatus(Approved());
        $donation = new Amount([
            'user_id' => 1,
            'amount' => '202',
            'type' => 'initial',
            'created_at' => now()
        ]);
        $challenge->amounts()->save($donation);
        $donation = new Amount([
            'user_id' => 1,
            'amount' => '1648',
            'type' => 'donation',
            'created_at' => now()
        ]);
        $challenge->amounts()->save($donation);
        
        // # 20 Fake DATA
        // for ($i = 0; $i <= 20; $i++) {
        //     $challenge = Challenge::create([
        //         'user_id' => $faker->randomElement([1,2]),
        //         'category_id' => $faker->randomElement([1,2]),
        //         'title' => $faker->unique()->word . ' ' . $faker->unique()->word,
        //         'description' => $faker->paragraph(),
        //         'start_time' => now(),
        //         'result_type' => $faker->randomElement(['first_win','vote']),
        //         'file' => 'no-image.png',
        //         'location' => $faker->country,
        //         'duration_days' => $faker->numberBetween(0, 10),
        //         'duration_hours' => $faker->numberBetween(0, 24),
        //         'duration_minutes' => $faker->numberBetween(0, 60),
        //         'created_at' => now()
        //     ]);
        //     $challenge->setStatus(Approved());
        //     $donation = new Amount([
        //         'user_id' => $faker->randomElement([1,2]),
        //         'amount' => $faker->randomNumber(2),
        //         'type' => 'initial',
        //         'created_at' => now()
        //     ]);
        //     $challenge->amounts()->save($donation);

        //     $donation = new Amount([
        //         'user_id' => $faker->randomElement([1,2]),
        //         'amount' => $faker->randomNumber(2),
        //         'type' => 'donation',
        //         'created_at' => now()
        //     ]);
        //     $challenge->amounts()->save($donation);

        //     $donation = new Amount([
        //         'user_id' => $faker->randomElement([1,2]),
        //         'amount' => $faker->randomNumber(2),
        //         'type' => 'donation',
        //         'created_at' => now()
        //     ]);
        //     $challenge->amounts()->save($donation);
        // }

    }
}

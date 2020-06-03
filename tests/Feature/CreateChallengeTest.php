<?php

namespace Tests\Feature;

use App\Models\Challenge;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CreateChallengeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_challenge_can_be_added()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        Storage::fake('storage');
        $file = UploadedFile::fake()->image(public_path(challengesPath().'no-image.png'));

        $response = $this->actingAs($user)->postJson('/api/challenges', [
            'terms_accepted' => 'true',
            'category_id' => 3,
            'title' => 'This is a challenge',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent ut euismod mauris. Phasellus enim tortor, consequat sit amet tortor sed, commodo ullamcorper purus. Integer fringilla, elit et placerat porta, erat nisl tincidunt lectus, quis vulputate justo ligula ut diam. Vestibulum non scelerisque felis. Donec cursus egestas ligula sit amet dictum.',
            'start_time' => '05-14-2020 02:05 PM',
            'duration_days' => 5,
            'duration_hours' => 5,
            'duration_minutes' => 30,
            'location' => 'New York, USA',
            'amount' => 500.50,
            'file' => $file
        ]);

        // Assert the file was stored...
        Storage::disk('storage')->assertExists($file->hashName());

        $response->assertOk();
        $this->assertCount(1, Challenge::all());
    }
}

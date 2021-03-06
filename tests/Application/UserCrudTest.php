<?php namespace Tests\Application;

use Tests\TestCase;

use App\Events\UserRegistered;
use App\Models\User;
use Faker\Factory;
use Faker\Generator;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Log;

class UserCrudTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function test_a_user_can_be_created()
    {
        $faker = Factory::create();
        $this->expectsEvents(UserRegistered::class);

        $email = $faker->email;
        $response = $this->json('POST', '/register', [
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $email,
                'password' => 'somepassword',
            ]);
        $response->assertStatus(302);
        $this->assertTrue(User::byEmail($email)->exists());
    }

    /** @test */
    public function a_user_can_be_updated()
    {
        // create user
        $user = factory(User::class)->create();
        $faker = Factory::create();
        
        $this->actingAs($user, 'api')
             ->json('POST', '/api/user/update-info', [
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->email,
            ])->assertJson(['success' => true]);
    }

    /** @test **/
    public function a_users_password_can_change()
    {
        // create user
        $password = 'somepassword';
        $user = factory(User::class)->create(['password' => bcrypt($password)]);

        $this->actingAs($user, 'api')
             ->json('POST', '/api/user/update-password', [
                'password' => $password,
                'new_password' => 'passwordtwo',
                'new_password_confirmation' => 'passwordtwo',
            ])->assertJson(['success' => true]);
    }

    /***************************************************************************************
     ** HELPERS
     ***************************************************************************************/

    public function makeUser()
    {
        $this->expectsEvents(UserRegistered::class);
        $faker = Factory::create();
        $email = $faker->email;
        $this->call('POST', '/register', [
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $email,
                'password' => 'somepassword',
            ]);
        return User::byEmail($email)->first();
    }
}
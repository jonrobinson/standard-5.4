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
        //$this->expectsEvents(UserRegistered::class);

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
        $user = $this->makeUser();

        $this->actingAs($user, 'api')
             ->json('POST', '/api/user/update-password', [
                'password' => 'somepassword',
                'new_password' => 'passwordtwo',
                'new_password_confirmation' => 'passwordtwo',
            ])->assertJson(['success' => true]);
    }

    /** @test */
    public function we_can_check_a_persons_age_on_date()
    {
        $user = $this->makeUser();

        $this->actingAs($user, 'api')
             ->json('POST', '/api/user/check-age-on-date', [
                'dob' => '1981-09-02',
            ])->assertJson(['success' => true]);
    }

    /** @test */
    public function a_persons_age_fails()
    {
        $user = $this->makeUser();

        $this->actingAs($user, 'api')
             ->json('POST', '/api/user/check-age-on-date', [
                'dob' => '2004-02-14',
            ])->assertJson(['success' => false]);
    }

    /** @test */
    public function we_can_confirm_a_users_email()
    {
        $user = $this->makeUser();

        $this->json('GET', '/api/user/confirm-email/' . $user->token)
            ->seeJson(['success' => true]);
        $user = $user->fresh();
        $this->assertJson($user->email_confirmed);
    }

    /***************************************************************************************
     ** HELPERS
     ***************************************************************************************/

    public function makeUser()
    {
        $this->expectsEvents(UserRegistered::class);
        $faker = Factory::create();
        $response = $this->call('POST', '/register', [
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->email,
                'password' => 'somepassword',
            ])->getContent();
        $response = json_decode($response);
        return User::byEmail($response->data->user->email)->first();
    }
}
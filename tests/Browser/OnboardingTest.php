<?php

namespace Tests\Browser;

use App\Events\UserRegistered;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use Faker\Factory;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Log;

class OnboardingTest extends DuskTestCase
{
    //use DatabaseTransactions;

    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Laravel');
        });
    }

    /** @test */
    public function a_user_can_register()
    {
        Event::fake();
        Event::assertDispatched(UserRegistered::class);

        $faker = Factory::create();
        $email = $faker->email;
        
        $this->browse(function (Browser $browser) use ($faker, $email) {
            
            $browser->visit('/register')
                    ->type('first_name', $faker->firstName)
                    ->type('last_name', $faker->lastName)
                    ->type('email', $email)
                    ->type('password', 'somerandompassword')
                    ->press('Register')
                    ->assertPathIs('/home');
        });

        
        $this->assertTrue(User::byEmail($email)->exists());
    }

    /** @test */
    public function we_can_confirm_a_users_email()
    {
        $user = factory(User::class)->create();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/user/confirm-email/' . $user->token)
                    ->assertSee('Your email has been confirmed');
            
        });
        $user = $user->fresh();
        $this->assertTrue($user->email_confirmed);
    }

}

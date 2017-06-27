<?php namespace App\Models;

// extends
use Illuminate\Database\Eloquent\Model;

// includes
use Carbon\Carbon;

class User_Onboarding extends Model
{
    protected $table = 'user_onboardings';
    protected $guarded = ['id'];
    protected $hidden = ['email_confirmation_code_expires_at', 'completed_at', 'created_at', 'updated_at'];
    protected $dates = ['email_confirmation_code_sent_at', 'email_confirmation_code_expires_at', 'completed_at', 'created_at', 'updated_at'];
    protected $casts = [
        'joining_an_organization' => 'boolean',
        'email_confirmed' => 'boolean',
        'accepted_terms_of_service' => 'boolean',
        'invite_others_page_seen' => 'boolean',
        'completed' => 'boolean',
    ];
    public $timestamps = true;

    /***************************************************************************************
     ** TO ARRAY
     ***************************************************************************************/

    public function toArray()
    {
        $array = parent::toArray();
        $array['step'] = $this->getStep();
        return $array;
    }

    /***************************************************************************************
     ** SCOPES
     ***************************************************************************************/

    public function scopeByEmail($query, string $email)
    {
        return $query->where('email', $email);
    }

    /***************************************************************************************
     ** RELATIONS
     ***************************************************************************************/

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function organization()
    {
        return $this->belongsTo('App\Models\Organization');
    }

    /***************************************************************************************
     ** CRUD
     ***************************************************************************************/

    public static function startOne(string $email, boolean $joining_an_organization = false)
    {
        $onboarding = new User_Onboarding;
        $onboarding->email = $email;
        $onboarding->joining_an_organization = $joining_an_organization;
        $onboarding->save();

        $onboarding->createConfirmationCode();
        return $onboarding;
    }

    public function createConfirmationCode()
    {
        $faker = Factory::create();
        $this->email_confirmation_code = $faker->randomNumber(6);
        $this->email_confirmation_code_expires_at = Carbon::now()->addHours(6);

        // ADD AN EMAIL EVENT HERE
        dispatch(new SendEmailConfirmationCode($this->email));

        $this->incrementEmailsSent(); 
    }

    public function createUser(string $password)
    {
        $user = new User;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->email = $this->email;
        $user->password = bcrypt($password);
        $user->save();

        $this->user()->associate($user);
        $this->save();

        return $user;
    }

    public function createOrganization(string $subdomain)
    {
        $organization = new Organization;
        $organization->name = $this->organization_name;
        $organization->slug = $subdomain;
        $organization->save();

        $organization->addUser($this->user);

        $this->organization()->associate($organization);
        $this->save();

        return $organization;
    }

    /***************************************************************************************
     ** HANDLE STEPS
     ***************************************************************************************/

    public function getStep()
    {
        // Creating An Organization
        if (!$this->joining_an_organization) {

            // get info to create the user
            if (!$this->user) {
                if (!$this->email_confirmed) {
                    return 'confirm_email';
                }
                if (!$this->first_name && !$this->last_name) {
                    return 'get_users_name';
                }
                return 'get_password_and_create_user';
            }

            // get info to create the organization
            if (!$this->organization) {
                if (!$this->organization_name) {
                    return 'get_organization_name';
                }
                return 'get_organization_subdomain_and_create_organization';
            }

            // terms of service
            if (!$this->accepted_terms_of_service) {
                return 'accept_terms_of_service';
            }

            // invite others page
            if (!$this->invite_others_page_seen) {
                return 'visit_invite_page';
            }

            return 'completed';
        }

        // Joining An Organization

        // create user
        if (!$this->user) {
            if (!$this->email_confirmed) {
                return 'confirm_email';
            }
            if (!$this->first_name && !$this->last_name) {
                return 'get_users_name';
            }
            return 'get_password_and_create_user';
        }

        // link to organization
        if (!$this->organization) {
            return 'find_an_organization_to_join';
        }

        // terms of service
        if (!$this->accepted_terms_of_service) {
            return 'accept_terms_of_service';
        }

        return 'completed';
    }

    public function getRedirect()
    {
        switch ($this->getStep()) {
            case 'confirm_email':
                return 'confirm-email';
                break;
            case 'get_users_name';
                return 'your-name';
                break;
            case 'get_password_and_create_user';
                return 'your-password';
                break;
            case 'get_organization_name';
                return 'your-organization';
                break;
            case 'get_organization_subdomain_and_create_organization';
                return 'your-subdomain';
                break;
            case 'accept_terms_of_service';
                return 'accept-terms-of-service';
                break;
            case 'visit_invite_page';
                return 'invite-users';
                break;
            case 'completed';
                return '/';
                break;
        }
    }

    /***************************************************************************************
     ** SETTERS
     ***************************************************************************************/

    public function setEmailConfirmed()
    {
        $this->email_confirmed = true;
        $this->save();
    }

    public function incrementEmailsSent()
    {
        $this->number_of_emails_sent++;
        $this->save();
    }

    public function setUsersName(string $first_name, string $last_name)
    {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->save();
    }

    public function setOrganizationName(string $name)
    {
        $this->organization_name = $name;
        $this->save();
    }

    public function setTermsOfServiceAccepted()
    {
        $this->accepted_terms_of_service = true;
        $this->save();
    }

    public function setInitePageSeen()
    {
        $this->invite_others_page_seen = true;
        $this->save();
    }

    public function setCompleted()
    {
        $this->completed = true;
        $this->save();

        $this->user->setOnboardingCompleted();
    }
    
}
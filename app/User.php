<?php

namespace App;

use DB;
use Auth;
use App\Models\Event\Event;
use App\Models\Event\EventInstance;
use App\Models\Event\Attendance;
use App\Http\Utilities\Slim;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $fillable = [
        'firstname', 'lastname', 'type', 'email', 'phone', 'gender', 'dob', 'instagram',
        'address', 'address2', 'suburb', 'state', 'postcode', 'country',
        'grade_id', 'school_id', 'media_consent', 'media_consent_by', 'media_consent_at', 'photo',
        'wwc_no', 'wwc_exp', 'wwc_verified', 'wwc_verified_by', 'notes', 'minhub', 'status', 'aid',
        'login', 'username', 'password', 'password_reset', 'email_verified_at', 'last_ip', 'last_login', 'created_by', 'updated_by'];
    protected $dates = ['dob', 'wwc_exp', 'wwc_verified', 'media_consent_at', 'last_login'];

    //The attributes that should be hidden for arrays.
    protected $hidden = ['password', 'remember_token',];


    /**
     * A User belongs to a account
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('App\Models\Account\Account', 'aid');
    }


    /**
     * A User May belongs to a grade
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function grade()
    {
        return $this->belongsTo('App\Models\People\Grade', 'grade_id');
    }

    /**
     * A User May belongs to a school
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\People\School', 'school_id');
    }

    /**
     * A User May have belong to many households
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function households()
    {
        return $this->belongsToMany('App\Models\People\Household', 'users_household', 'uid', 'hid');
    }

    /**
     * A User has many Attendance
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attendance()
    {
        return $this->hasMany('App\Models\Event\Attendance', 'uid');
    }

    /**
     * A User has many History
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history()
    {
        return $this->hasMany('App\Models\People\PeopleHistory', 'uid');
    }


    /**
     * Attach photo to a person
     */
    public function attachPhoto()
    {
        // Pass Slim's getImages the name of your file input, and since we only care about one image, use Laravel's head() helper to get the first element
        $image = head(Slim::getImages('photo'));

        // Grab the ouput data (data modified after Slim has done its thing)
        if (isset($image['output']['data'])) {
            $name = $this->id . '.' . pathinfo($image['output']['name'], PATHINFO_EXTENSION);;   // Original file name = $image['output']['name'];
            $data = $image['output']['data'];  // Base64 of the image
            $path = storage_path("app/account/$this->aid/images/people/");   // Server path
            $filepath = $path . $name;

            // Save the file to the server + update record
            $file = Slim::saveFile($data, $name, $path, false);
            $this->photo = $name;
            $this->save();

            // Save the image as a thumbnail of 90x90 + 50x50
            if (exif_imagetype($filepath)) {
                Image::make($filepath)->resize(90, 90)->save($path . 's' . $name);
                Image::make($filepath)->resize(50, 50)->save($path . 'x' . $name);
            } else
                Toastr::error("Bad image");

        }
    }

    /**
     * Check person into EventInstance
     */
    public function checkin($instance, $method = 'check-in')
    {
        $attend = Attendance::create([
            'eid'    => $instance->id,
            'uid'    => $this->id,
            // Need to covert to local TZ because model itself coverts to UTC on save
            'in'     => Carbon::now()->timezone(session('tz'))->toDateTimeString(),
            'method' => $method
        ]);

        return $attend;
    }

    /**
     * Get First Attendance
     */
    public function firstEvent($eid = '')
    {
        if ($eid) {
            $event = EventInstance::select([
                'events_instance.id', 'events_instance.name', 'events_instance.start',
                'events_attendance.eid', 'events_attendance.uid'])
                ->join('events_attendance', 'events_attendance.eid', '=', 'events_instance.id')
                ->where('events_attendance.uid', $this->id)
                ->where('events_instance.eid', $eid)
                ->orderBy('events_instance.start', 'asc')->get()->first();

            return ($event) ? EventInstance::find($event->eid) : null;
        } else {
            $event = EventInstance::select([
                'events_instance.id', 'events_instance.name', 'events_instance.start',
                'events_attendance.eid', 'events_attendance.uid'])
                ->join('events_attendance', 'events_attendance.eid', '=', 'events_instance.id')
                ->where('events_attendance.uid', $this->id)
                ->orderBy('events_instance.start', 'asc')->get()->first();

            return ($event) ? EventInstance::find($event->eid) : null;
        }
    }

    /**
     * Get Last Attendance
     */
    public function lastEvent($eid = '')
    {
        if ($eid) {
            $event = EventInstance::select([
                'events_instance.id', 'events_instance.name', 'events_instance.start',
                'events_attendance.eid', 'events_attendance.uid'])
                ->join('events_attendance', 'events_attendance.eid', '=', 'events_instance.id')
                ->where('events_attendance.uid', $this->id)
                ->where('events_instance.eid', $eid)
                ->orderBy('events_instance.start', 'desc')->get()->first();

            return ($event) ? EventInstance::find($event->eid) : null;
        } else {
            $event = EventInstance::select([
                'events_instance.id', 'events_instance.name', 'events_instance.start',
                'events_attendance.eid', 'events_attendance.uid'])
                ->join('events_attendance', 'events_attendance.eid', '=', 'events_instance.id')
                ->where('events_attendance.uid', $this->id)
                ->orderBy('events_instance.start', 'desc')->get()->first();

            return ($event) ? EventInstance::find($event->eid) : null;
        }
    }

    /**
     * Get the Full name (first + last)   (getter)
     *
     * @return string;
     */
    public function getNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    /**
     * Get Age (getter)
     */
    public function getAgeAttribute()
    {
        return Carbon::parse($this->attributes['dob'])->age;
    }

    /**
     * Get the School name  (getter)
     *
     * @return string;
     */
    public function getSchoolNameAttribute()
    {
        return ($this->attributes['school_id']) ? $this->school->name : '';
    }

    /**
     * Get the Grade name  (getter)
     *
     * @return string;
     */
    public function getGradeNameAttribute()
    {
        return ($this->attributes['grade_id']) ? $this->grade->name : '';
    }

    /**
     * Get the Grade name  (getter)
     *
     * @return string;
     */
    /*
    public function getGradeOrdinalAttribute()
    {
        $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
        if (is_numeric($this->grade)) {
            if (($this->grade % 100) >= 11 && ($this->grade % 100) <= 13)
                return $this->grade . 'th';
            else
                return $this->grade . $ends[$this->grade % 10];
        }

        return $this->grade;
    }*/

    /**
     * Get Photo Path (getter)
     */
    public function getPhotoPathAttribute()
    {
        $path = "/image/" . $this->attributes['aid'] . '/people/';

        return ($this->attributes['photo']) ? $path . $this->attributes['photo'] : '/img/avatar-user2.png';
    }

    /**
     * Get Avatar (getter)
     */
    public function getPhotoSmPathAttribute()
    {
        $path = "/image/" . $this->attributes['aid'] . '/people/s';

        return ($this->attributes['photo']) ? $path . $this->attributes['photo'] : '/img/avatar-user2.png';
    }

    /**
     * This person is a Student
     */
    public function getIsStudentAttribute()
    {
        return (in_array($this->type, ['Student', 'Student/Volunteer'])) ? true : false;
    }

    /**
     * This person is a Volunteer
     */
    public function getIsVolunteerAttribute()
    {
        return (in_array($this->type, ['Volunteer', 'Student/Volunteer', 'Parent/Volunteer'])) ? true : false;
    }

    /**
     * This person is a Parent
     */
    public function getIsParentAttribute()
    {
        return (in_array($this->type, ['Parent', 'Parent/Volunteer'])) ? true : false;
    }

    /**
     * Get First Attendance Ever (getter)
     */
    public function getFirstEventEverAttribute()
    {
        $event = EventInstance::select([
            'events_instance.id', 'events_instance.name', 'events_instance.start',
            'events_attendance.eid', 'events_attendance.uid'])
            ->join('events_attendance', 'events_attendance.eid', '=', 'events_instance.id')
            ->where('events_attendance.uid', $this->id)
            ->orderBy('events_instance.start', 'asc')->get()->first();

        return ($event) ? EventInstance::find($event->eid) : null;

    }

    /**
     * Get Last Attendance Ever (getter)
     */
    public function getLastEventEverAttribute()
    {
        $event = EventInstance::select([
            'events_instance.id', 'events_instance.name', 'events_instance.start',
            'events_attendance.eid', 'events_attendance.uid'])
            ->join('events_attendance', 'events_attendance.eid', '=', 'events_instance.id')
            ->where('events_attendance.uid', $this->id)
            ->orderBy('events_instance.start', 'desc')->get()->first();

        return ($event) ? EventInstance::find($event->eid) : null;
    }

    /**
     * Get the suburb, state, postcode  (getter)
     */
    public function getAddressFormattedAttribute()
    {
        $string = '';
        if ($this->address) $string = "$this->address<br>";
        $string .= strtoupper($this->suburb);
        if ($this->suburb && $this->state) $string .= ', ';
        if ($this->state) $string .= $this->state;
        if ($this->postcode) $string .= ' ' . $this->postcode;

        return ($string) ? $string : '';
    }

    /**
     * A User Media Consent May be Given by a User (getter)
     */
    public function getMediaConsentByUserAttribute()
    {
        return User::find($this->media_consent_by);
    }

    /**
     * A User WWC May be Verified by a User
     */
    public function getWwcVerifieUserdBy()
    {
        return User::find($this->wwc_verified_by);
    }

    /**
     * Get user timezone  (getter)
     */
    public function getDateformatAttribute()
    {
        return $this->account->dateformat;
    }

    /**
     * Get users Timezone  (getter)
     */
    public function getTimezoneAttribute()
    {
        return $this->account->timezone;
    }

    /**
     * Captitalise firstname  (mutator)
     */
    public function setFirstNameAttribute($value)
    {
        $this->attributes['firstname'] = trim(ucfirst($value));
    }

    /**
     * Captitalise lastname  (mutator)
     */
    public function setLastNameAttribute($value)
    {
        $this->attributes['lastname'] = trim(ucfirst($value));
    }


    /**
     * Set the address to capital firat letter format  (mutator)
     */
    public function setAddressAttribute($value)
    {
        $this->attributes['address'] = trim(ucwords(strtolower($value)));
    }

    /**
     * Set the suburb to uppercase format  (mutator)
     */
    public function setSuburbAttribute($value)
    {
        $this->attributes['suburb'] = trim(strtoupper($value));
    }

    /**
     * Set the suburb to uppercase format  (mutator)
     */
    public function setStateAttribute($value)
    {
        $this->attributes['state'] = trim(strtoupper($value));
    }

    /**
     * Set the phone number to AU format  (mutator)
     *
     * @param $phone
     */
    public function setPhoneAttribute($phone)
    {
        $this->attributes['phone'] = format_phone('au', $phone);
    }


    static public function types()
    {
        return ['Student' => 'Student', 'Student/Volunteer' => 'Student/Volunteer', 'Parent' => 'Parent', 'Parent/Volunteer' => 'Parent/Volunteer', 'Volunteer' => 'Volunteer'];
    }

    /**
     * Display records last update_by + date
     *
     * @return string
     */
    public function displayUpdatedBy()
    {
        $user = User::find($this->updated_by);

        return ($user) ? '<span style="font-weight: 400">Last modified: </span>' . $this->updated_at->diffForHumans() . ' &nbsp; ' .
            '<span style="font-weight: 400">By:</span> ' . $user->name : "$this->updated_by";
    }

    /**
     * The "booting" method of the model.
     *
     * Overrides parent function
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        if (auth()->check()) {
            // create a event to happen on creating
            static::creating(function ($table) {
                $table->created_by = auth()->id();
                $table->updated_by = auth()->id();
                $table->aid = session('aid');
            });

            // create a event to happen on updating
            static::updating(function ($table) {
                $table->updated_by = auth()->id();
            });
        }
    }

}

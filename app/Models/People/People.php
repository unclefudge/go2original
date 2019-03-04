<?php

namespace App\Models\People;

use Auth;
use App\User;
use App\Models\Event\Event;
use App\Models\Event\EventInstance;
use App\Models\Event\Attendance;
use App\Http\Utilities\Slim;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use Illuminate\Database\Eloquent\Model;

class People extends Model {

    protected $table = 'people';
    protected $fillable = [
        'firstname', 'lastname', 'type', 'gender', 'dob', 'email', 'phone', 'instagram',
        'address', 'address2', 'suburb', 'state', 'postcode', 'country',
        'grade', 'school_id', 'photo', 'wwc_no', 'wwc_exp', 'wwc_verified', 'wwc_verified_by',
        'media_consent', 'media_consent_by', 'notes', 'minhub', 'status', 'aid', 'created_by', 'updated_by'];
    protected $dates = ['dob', 'wwc_exp', 'wwc_verified', 'media_consent'];

    /**
     * A People belongs to a account
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('App\Models\Account\Account', 'aid');
    }


    /**
     * A People May have many users (who own this profile)
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'users_people', 'pid', 'uid');
    }

    /**
     * A People May belongs to a school
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\People\School', 'school_id');
    }

    /**
     * A People May have belong to many households
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function households()
    {
        return $this->belongsToMany('App\Models\People\Household', 'households_people', 'pid', 'hid');
    }

    /**
     * A People Media Consent May be Given by a User
     */
    public function mediaConsentBy()
    {
        return People::find($this->media_consent_by);
    }

    /**
     * A People WWC May be Verified by a User
     */
    public function wwcVerifiedBy()
    {
        return People::find($this->wwc_verified_by);
    }

    /**
     * This person is a Student
     */
    public function isStudent() {
        return (in_array($this->type, ['Student', 'Student/Volunteer'])) ? true : false;
    }

    /**
     * This person is a Volunteer
     */
    public function isVolunteer() {
        return (in_array($this->type, ['Volunteer', 'Student/Volunteer', 'Parent/Volunteer'])) ? true : false;
    }

    /**
     * This person is a Parent
     */
    public function isParent() {
        return (in_array($this->type, ['Parent', 'Parent/Volunteer'])) ? true : false;
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
            $path = storage_path('app/account/' . Auth::user()->id . '/images/people/');   // Server path
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
    public function checkin($instance, $method = 'checkin')
    {
        $attend = Attendance::create([
            'eid'    => $instance->id,
            'pid'    => $this->id,
            'in'     => Carbon::now()->timezone(session('tz'))->format('Y-m-d H:i:s'),
            'method' => $method
        ]);

        return $attend;
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
     * Get First Attendance (getter)
     */
    public function getFirstAttendedAttribute($eid = '')
    {
        return ($eid) ? Attendance::where('pid', $this->id)->where('eid', $eid)->orderBy('in', 'asc')->first() : Attendance::where('pid', $this->id)->orderBy('in', 'asc')->first();
    }

    /**
     * Get Lst Attendance (getter)
     */
    public function getLastAttendedAttribute($eid = '')
    {
        return ($eid) ? Attendance::where('pid', $this->id)->where('eid', $eid)->orderBy('in', 'desc')->first() : Attendance::where('pid', $this->id)->orderBy('in', 'desc')->first();

    }

    /**
     * Get the suburb, state, postcode  (getter)
     */
    public function getAddressFormattedAttribute()
    {
        $string = '';

        if ($this->attributes['address'])
            $string = strtoupper($this->attributes['address']) . '<br>';

        $string .= strtoupper($this->attributes['suburb']);
        if ($this->attributes['suburb'] && $this->attributes['state'])
            $string .= ', ';
        if ($this->attributes['state'])
            $string .= $this->attributes['state'];
        if ($this->attributes['postcode'])
            $string .= ' ' . $this->attributes['postcode'];

        return ($string) ? $string : '-';
    }

    /**
     * Captitalise firstname  (mutator)
     */
    public function setFirstameAttribute($value)
    {
        $this->attributes['firstname'] = trim(ucfirst($value));
    }

    /**
     * Captitalise lastname  (mutator)
     */
    public function setLastameAttribute($value)
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


    static public function types() {
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

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\People\School', 'school_id');
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

            // Save the image as a thumbnail of 90x90 + 30x30
            if (exif_imagetype($filepath)) {
                Image::make($filepath)->resize(90, 90)->save($path . 't90-' . $name);
                Image::make($filepath)->resize(50, 50)->save($path . 't50-' . $name);
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
    public function getAvatar90Attribute()
    {
        $path = "/image/" . $this->attributes['aid'] . '/people/t90-';

        return ($this->attributes['photo']) ? $path . $this->attributes['photo'] : '/img/avatar-user2.png';
    }

    /**
     * Get Timezone  (getter)
     *
     * @return string;
     */
    public function getTimezoneAttribute()
    {
        return $this->account->timezone;
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
            });

            // create a event to happen on updating
            static::updating(function ($table) {
                $table->updated_by = auth()->id();
            });
        }
    }
}

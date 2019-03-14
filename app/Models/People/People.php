<?php

namespace App\Models\People;

use DB;
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
        'media_consent', 'media_consent_by', 'media_consent_at', 'notes', 'minhub', 'status', 'aid', 'created_by', 'updated_by'];
    protected $dates = ['dob', 'wwc_exp', 'wwc_verified', 'media_consent_at'];

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
     * A People has many Attendance
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attendance()
    {
        return $this->hasMany('App\Models\Event\Attendance', 'pid');
    }

    /**
     * A People has many History
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history()
    {
        return $this->hasMany('App\Models\People\PeopleHistory', 'pid');
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
     * Determine which fields have changed and generate People History JSON data
     * JSON format 'category' : 'before' : 'after'
     *
     * @params $before, $after
     */
    public function addHistoryData($type, $b = '', $a = '')
    {
        if (!$b) $b = new People;
        if (!$a) $a = $this;
        $result = [];

        // {"1": {"after": "Clifton TAS 7000", "field": "Address", "before": "44 Church St"}, "2": {"after": "", "field": "Birthdate", "before": "1973-10-11"}}
        //
        // Personal
        //
        $data = [];
        // Name
        if ($a->firstname != $b->firstname || $a->lastname != $b->lastname) {
            $data['Name'] = [];
            $data['Name']['a'] = $a->name;
            $data['Name']['b'] = $b->name;
        }
        // Address
        if ($a->address != $b->address || $a->suburb != $b->suburb || $a->state != $b->state || $a->postcode != $b->postcode) {
            $data['Address'] = [];
            $data['Address']['a'] = $a->address_formatted;
            $data['Address']['b'] = $b->address_formatted;
        }

        $personal = ['type', 'gender', 'dob', 'email', 'phone', 'instagram'];
        foreach ($personal as $key) {
            if ($a[$key] != $b[$key]) {
                $KEY = ucfirst($key);
                if ($key == 'dob') $KEY = 'Birthdate';
                $data[$KEY] = [];

                if (in_array($key, $this->dates)) {
                    $data[$KEY]['a'] = ($a[$key]) ? $a[$key]->format(session('df')) : '';
                    $data[$KEY]['b'] = ($b[$key]) ? $b[$key]->format(session('df')) : '';
                } else {
                    $data[$KEY]['a'] = $a[$key];
                    $data[$KEY]['b'] = $b[$key];
                }
            }
        }

        if (count($data)) {
            $json = "{";
            $x = 1;
            foreach ($data as $key => $val)
                $json .= '"' . ($x ++) . '": {"field": "' . $key . '", "before": "' . $val['b'] . '", "after": "' . $val['a'] . '"}, ';
            $json = rtrim($json, ', ') . "}";
            $result['personal'] = $json;

            $action = (PeopleHistory::where('pid', $this->id)->where('type', $type)->where('subtype', 'personal')->first()) ? 'updated' : 'created';
            // Save history record to DB
            DB::table('people_history')->insert([
                'pid'  => $this->id, 'action' => $action, 'type' => $type, 'subtype' => 'personal',
                'data' => $json, 'created_by' => Auth::user()->id, 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()]);

            // Delay 1 sec after 'Created' record to ensure it's earlier then updates for order sort later on
            if ($action == 'created') sleep(1);
        }

        //
        // Student
        //
        $data = [];
        $student = ['grade', 'school_id', 'media_consent'];
        foreach ($student as $key) {
            if ($a[$key] != $b[$key]) {
                $KEY = ucfirst($key);
                if ($key == 'school_id') $KEY = 'School';
                if ($key == 'media_consent') $KEY = 'Media Consent';

                $data[$KEY] = [];

                if ($key == 'school_id') {
                    $data[$KEY]['a'] = ($a->school_id) ? $a->school->name : '';
                    $data[$KEY]['b'] = ($b->school_id) ? $b->school->name : '';
                } elseif ($key == 'grade') {
                    $data[$KEY]['a'] = ($a->grade) ? $a->grade_ordinal : '';
                    $data[$KEY]['b'] = ($b->grade) ? $b->grade_ordinal : '';
                } elseif ($key == 'media_consent') {
                    $data[$KEY]['a'] = ($a->media_consent) ? "$a->media_consent<br>" . $a->media_consent_at->format(session('df')) . "<br>By " . $a->mediaConsentByUser->name : '';
                    $data[$KEY]['b'] = ($b->media_consent) ? "$b->media_consent<br>" . $b->media_consent_at->format(session('df')) . "<br>By " . $b->mediaConsentByUser->name : '';
                }
            }
        }

        //print_r($data);
        //dd($data);
        if (count($data)) {
            $json = "{";
            $x = 1;
            foreach ($data as $key => $val)
                $json .= '"' . ($x ++) . '": {"field": "' . $key . '", "before": "' . $val['b'] . '", "after": "' . $val['a'] . '"}, ';
            $json = rtrim($json, ', ') . "}";
            // Save history record to DB
            DB::table('people_history')->insert([
                'pid'  => $this->id, 'action' => 'updated', 'type' => $type, 'subtype' => 'student',
                'data' => $json, 'created_by' => Auth::user()->id, 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()]);

        }

        //
        // Volunteer
        //
        $data = [];
        $volunteer = ['wwc_no', 'wwc_exp', 'wwc_verified', 'wwc_verified_by'];
        if ($a->wwc_no != $b->wwc_no || $a->wwc_exp != $b->wwc_exp || $a->wwc_verified != $b->wwc_verified) {
            $data['WWC Registration'] = [];
            $data['WWC Registration']['a'] = ($a->wwc_exp) ? "No: $a->wwc_no<br>Exp: " . $a->wwc_exp->format(session('df')) : "No: $a->wwc_no<br>";
            $data['WWC Registration']['b'] = ($b->wwc_exp) ? "No: $b->wwc_no<br>Exp: " . $b->wwc_exp->format(session('df')) : "No: $b->wwc_no<br>";
            if ($a->wwc_verified != $b->wwc_verified) {
                $data['WWC Registration']['a'] .= ($a->wwc_verified) ? "Verified by<br>" . $a->wwcVerifiedByUser->name . '<br>' . $a->wwc_verified->format(session('df')) : '';
                $data['WWC Registration']['b'] .= ($b->wwc_verified) ? "Verified by<br>" . $b->wwcVerifiedByUser->name . '<br>' . $b->wwc_verified->format(session('df')) : '';
            }
        }

        //print_r($data);
        if ($data) {
            $json = "{";
            $x = 1;
            foreach ($data as $key => $val)
                $json .= '"' . ($x ++) . '": {"field": "' . $key . '", "before": "' . $val['b'] . '", "after": "' . $val['a'] . '"}, ';
            $json = rtrim($json, ', ') . "}";
            // Save history record to DB
            DB::table('people_history')->insert([
                'pid'  => $this->id, 'action' => 'updated', 'type' => $type, 'subtype' => 'volunteer',
                'data' => $json, 'created_by' => Auth::user()->id, 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()]);
            $result[''] = $json;
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
                'events_attendance.eid', 'events_attendance.pid'])
                ->join('events_attendance', 'events_attendance.eid', '=', 'events_instance.id')
                ->where('events_attendance.pid', $this->id)
                ->where('events_instance.eid', $eid)
                ->orderBy('events_instance.start', 'asc')->get()->first();

            return ($event) ? EventInstance::find($event->eid) : null;
        } else {
            $event = EventInstance::select([
                'events_instance.id', 'events_instance.name', 'events_instance.start',
                'events_attendance.eid', 'events_attendance.pid'])
                ->join('events_attendance', 'events_attendance.eid', '=', 'events_instance.id')
                ->where('events_attendance.pid', $this->id)
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
                'events_attendance.eid', 'events_attendance.pid'])
                ->join('events_attendance', 'events_attendance.eid', '=', 'events_instance.id')
                ->where('events_attendance.pid', $this->id)
                ->where('events_instance.eid', $eid)
                ->orderBy('events_instance.start', 'desc')->get()->first();

            return ($event) ? EventInstance::find($event->eid) : null;
        } else {
            $event = EventInstance::select([
                'events_instance.id', 'events_instance.name', 'events_instance.start',
                'events_attendance.eid', 'events_attendance.pid'])
                ->join('events_attendance', 'events_attendance.eid', '=', 'events_instance.id')
                ->where('events_attendance.pid', $this->id)
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
            'events_attendance.eid', 'events_attendance.pid'])
            ->join('events_attendance', 'events_attendance.eid', '=', 'events_instance.id')
            ->where('events_attendance.pid', $this->id)
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
            'events_attendance.eid', 'events_attendance.pid'])
            ->join('events_attendance', 'events_attendance.eid', '=', 'events_instance.id')
            ->where('events_attendance.pid', $this->id)
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
     * A People Media Consent May be Given by a User (getter)
     */
    public function getMediaConsentByUserAttribute()
    {
        return User::find($this->media_consent_by);
    }

    /**
     * A People WWC May be Verified by a User
     */
    public function getWwcVerifieUserdBy()
    {
        return User::find($this->wwc_verified_by);
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

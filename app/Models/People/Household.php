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

class Household extends Model {

    protected $table = 'households';
    protected $fillable = ['name', 'pid', 'notes', 'aid', 'created_by', 'updated_by'];

    /**
     * A Household belongs to a account
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('App\Models\Account\Account', 'aid');
    }

    /**
     * A Household has a head (Primary Person)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function head()
    {
        return $this->belongsTo('App\Models\People\People', 'pid');
    }

    /**
     * A Household has many members.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function members()
    {
        return $this->belongsToMany('App\Models\People\People', 'households_people', 'hid', 'pid');
    }

    /**
     * Household adults
     */
    public function adults()
    {
        $adults = [];
        foreach ($this->members as $member) {
            if (!$member->isStudent())
                $adults[] = $member->id;
        }
        return People::find($adults);
    }

    /**
     * Household students
     */
    public function students()
    {
        $students = [];
        foreach ($this->members as $member) {
            if ($member->isStudent())
                $students[] = $member->id;
        }
        return People::find($students);
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

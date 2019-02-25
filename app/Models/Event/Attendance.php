<?php

namespace App\Models\Event;

use App\User;
use Carbon\Carbon;
use Camroncade\Timezone\Facades\Timezone;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'events_attendance';
    protected $fillable = [
        'eid', 'pid', 'in', 'out', 'method', 'aid',  'created_by', 'updated_by'];
    protected $dates = ['in', 'out'];

    /**
     * A EventRecur belongs to a account
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('App\Models\Account\Account', 'aid');
    }


    /**
     * A EventInstance belong to an Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recur()
    {
        return $this->belongsTo('App\Models\Event\Event', 'eid');
    }

    /**
     * Set date to UTC format for Timezone localization
     * @param $input
     */
    public function setInAttribute($input)
    {
        $this->attributes['in'] = ($input) ? Timezone::convertToUTC($input, auth()->user()->timezone, 'Y-m-d H:i:s') : null;
    }

    /**
     * Convert date to User Local Timezone format
     * @param $input
     *
     * @return string
     */
    //public function getInAttribute($input)
    //{
    //    return ($input) ? Timezone::convertFromUTC($input, auth()->user()->timezone, 'Y-m-d H:i:s') : '';
    //}

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

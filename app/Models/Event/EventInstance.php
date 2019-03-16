<?php

namespace App\Models\Event;

use App\User;
use Carbon\Carbon;
use Camroncade\Timezone\Facades\Timezone;
use Illuminate\Database\Eloquent\Model;

class EventInstance extends Model
{
    protected $table = 'events_instance';
    protected $fillable = [
        'name', 'code', 'day', 'start', 'end', 'grades', 'background',
        'notes', 'status', 'eid', 'aid',  'created_by', 'updated_by'];
    protected $dates = ['start', 'end'];

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
    public function event()
    {
        return $this->belongsTo('App\Models\Event\Event', 'eid');
    }

    /**
     * A Event has many Attendance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attendance()
    {
        return $this->hasMany('App\Models\Event\Attendance', 'eid');
    }


    /**
     * Did User Attend.
     *
     * @return EventAttendance
     */
    public function userAttend($pid)
    {
        return $this->attendance->where('uid', $pid)->first();
    }

    /**
     * Existing Event Instance on date YYYY-MM-DD
     *
     * @return EventAttendance
     */
    static public function existingLocalDate($date, $eid = '')
    {
        // Convert date to UTC and search for instance from UTC start of day to end of day
        // for those darn international timezone difference
        $utcDateStart = Timezone::convertToUTC("$date 00:00:00", session('tz')); // Beginning of day UTC
        $utcDateEnd = Carbon::createFromFormat('Y-m-d H:i:s', "$date 00:00:00")->timezone(session('tz'))->endOfDay()->timezone('UTC'); // End of day UTC

        return ($eid) ? EventInstance::where('eid', $eid)->whereBetween('start', [$utcDateStart, $utcDateEnd])->first() : EventInstance::whereBetween('start', [$utcDateStart, $utcDateEnd])->first() ;
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
     * Convert date to User Local Timezone format
     *
     * @return Carbon date object
     */
    public function getStartLocalAttribute()
    {
        return $this->start->timezone(session('tz'));
    }

    /**
     * Convert date to User Local Timezone format
     *
     * @param $input
     * @return string
     *//*
    public function getStartAttribute($input)
    {
        return Carbon::parse(Timezone::convertFromUTC($input, session('tz'));
    }*/

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

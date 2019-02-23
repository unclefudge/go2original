<?php

namespace App\Models\Event;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';
    protected $fillable = [
        'name', 'recur', 'code', 'day', 'start', 'end', 'grades', 'background',
        'notes', 'status', 'aid',  'created_by', 'updated_by'];
    protected $dates = ['start', 'end'];

    /**
     * A Event belongs to a account
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('App\Models\Account\Account', 'aid');
    }


    /**
     * A Event has many EventInstance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function instances()
    {
        return $this->hasMany('App\Model\Event\EventInstance');
    }


    /**
     * Get Background Path (getter)
     */
    public function getBackgroundPathAttribute()
    {
        $path = "/image/".$this->attributes['aid'].'/events/';
        return ($this->attributes['background']) ? $path.$this->attributes['background'].'?'.rand(1, 32000) : '/img/bg-event.jpg';
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

<?php

namespace App\Models\People;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PeopleUser extends Model
{
    protected $table = 'users_people';
    protected $fillable = [
        'uid', 'pid', 'created_by', 'updated_by'];

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
     * A User has many trades (trades they are skilled in).
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function tradesSkilledIn()
    {
        return $this->belongsToMany('App\Models\Site\Planner\Trade', 'user_trade', 'user_id', 'trade_id');
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

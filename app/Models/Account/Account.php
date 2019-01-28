<?php

namespace App\Models\Account;

use App\User;
use App\Models\People\People;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'accounts';
    protected $fillable = [
        'name', 'slug', 'timezone', 'dateformat', 'country', 'banner', 'grade_adv',
        'status', 'created_by', 'updated_by'];


    /**
     * A Account has many People
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function people()
    {
        return $this->hasMany('App\Models\People\People', 'aid');
    }

    /**
     * A Account has many Schools
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schools()
    {
        return $this->hasMany('App\Models\People\School', 'aid');
    }

    /**
     * A dropdown list of schools for this account
     *
     * @return array
     */
    public function schoolsSelect($prompt = '', $status = 1)
    {
        $array = [];
        foreach ($this->schools as $school)
            if ($school->status == $status)
                $array[$school->id] =  $school->name;

        asort($array);

        if ($prompt == 'all')
            return ($prompt && count($array) > 1) ? $array = array('' => 'All Schools') + $array : $array;
        if ($prompt == 'ALL')
            return ($prompt && count($array) > 1) ? $array = array('all' => 'All School') + $array : $array;

        return ($prompt && count($array) > 1) ? $array = array('' => 'Select School') + $array : $array;
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

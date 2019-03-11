<?php

namespace App;

use DB;
use App\Models\Account\Account;
use App\Models\People\People;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'last_ip', 'last_login', 'password_reset', 'status', 'created_by', 'updated_by',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * A User May have many people profiles they own.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'users_people', 'pid', 'uid');
    }

    /**
     * A User May have many people (profiles they may own)
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function people()
    {
        return $this->belongsToMany('App\Models\People\People', 'users_people', 'uid', 'pid');
    }

    /**
     * Get user fullname if linked to person else username  (getter)
     */
    public function getNameAttribute()
    {
        return ($this->people->first()) ? $this->people->first()->name : $this->attributes['username'];
    }


    /**
     * Get user primary people profile (getter)
     *
     */
    public function getPrimaryAttribute()
    {
        $row = DB::table('users_people')->select('pid')->where('uid', $this->id)->where('primary', 1)->first();
        return People::findOrFail($row->pid);
    }
    /**
     * Get user timezone  (getter)
     */
    public function getDateformatAttribute()
    {
        return Account::find(1)->dateformat;
    }

    /**
     * Get users Timezone  (getter)
     */
    public function getTimezoneAttribute()
    {
        return Account::find(1)->timezone;
        return ($this->people->first()) ? $this->people->first()->name : $this->attributes['username'];
    }

}

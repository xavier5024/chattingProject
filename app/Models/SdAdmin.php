<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SdAdmin
 * 
 * @property int $admin_seq
 * @property string $id
 * @property string $name
 * @property string $password
 * @property string $email
 * @property string $tel
 * @property string $auth_yn
 * @property Carbon|null $auth_date
 * @property string $use_yn
 * @property Carbon $add_date
 * @property Carbon $mod_date
 * @property string|null $remember_token
 *
 * @package App\Models
 */
class SdAdmin extends Model
{
	protected $table = 'sd_admin';
	protected $primaryKey = 'admin_seq';
	public $timestamps = false;

	protected $dates = [
		'auth_date',
		'add_date',
		'mod_date'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'id',
		'name',
		'password',
		'email',
		'tel',
		'auth_yn',
		'auth_date',
		'use_yn',
		'add_date',
		'mod_date',
		'remember_token'
	];
}

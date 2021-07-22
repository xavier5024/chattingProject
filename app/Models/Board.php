<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Board
 * 
 * @property int $idx
 * @property int|null $id
 * @property string|null $file
 * @property string|null $file_server
 * @property string|null $subject
 * @property string|null $content
 * @property Carbon $regDate
 *
 * @package App\Models
 */
class Board extends Model
{
	protected $table = 'board';
	protected $primaryKey = 'idx';
	public $timestamps = false;

	protected $casts = [
		'id' => 'int'
	];

	protected $dates = [
		'regDate'
	];

	protected $fillable = [
		'id',
		'file',
		'file_server',
		'subject',
		'content',
		'regDate'
	];
}

<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TblPerfile
 * 
 * @property int $Perfil_ID
 * @property string $Nombre
 * 
 * @property Collection|TblUsuario[] $tbl_usuarios
 *
 * @package App\Models
 */
class TblPerfile extends Model
{
	protected $table = 'tbl_perfiles';
	protected $primaryKey = 'Perfil_ID';
	public $timestamps = false;

	protected $fillable = [
		'Nombre'
	];

	public function tbl_usuarios()
	{
		return $this->hasMany(TblUsuario::class, 'Perfil_ID');
	}
}

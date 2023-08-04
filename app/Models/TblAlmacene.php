<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TblAlmacene
 * 
 * @property int $Almacen_ID
 * @property string $Nombre
 * @property string $Ciudad
 * @property string $Estado
 * 
 * @property Collection|TblUsuario[] $tbl_usuarios
 *
 * @package App\Models
 */
class TblAlmacene extends Model
{
	protected $table = 'tbl_almacenes';
	protected $primaryKey = 'Almacen_ID';
	public $timestamps = false;

	protected $fillable = [
		'Nombre',
		'Ciudad',
		'Estado'
	];

	public function tbl_usuarios()
	{
		return $this->hasMany(TblUsuario::class, 'Almacen_ID');
	}
}

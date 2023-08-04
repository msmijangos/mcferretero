<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TblUsuario
 * 
 * @property int $Usuario_ID
 * @property string $Usuario
 * @property string $Password
 * @property string $Nombre
 * @property int $Almacen_ID
 * @property bool $Activo
 * @property int $Perfil_ID
 * 
 * @property TblAlmacene $tbl_almacene
 * @property TblPerfile $tbl_perfile
 *
 * @package App\Models
 */
class TblUsuario extends Model
{
	protected $table = 'tbl_usuarios';
	protected $primaryKey = 'Usuario_ID';
	public $timestamps = false;

	protected $casts = [
		'Almacen_ID' => 'int',
		'Activo' => 'bool',
		'Perfil_ID' => 'int'
	];

	protected $fillable = [
		'Usuario',
		'Password',
		'Nombre',
		'Almacen_ID',
		'Activo',
		'Perfil_ID'
	];

	public function tbl_almacene()
	{
		return $this->belongsTo(TblAlmacene::class, 'Almacen_ID');
	}

	public function tbl_perfile()
	{
		return $this->belongsTo(TblPerfile::class, 'Perfil_ID');
	}
}

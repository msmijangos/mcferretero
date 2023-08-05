<?php

namespace App\Http\Controllers;

use App\Models\TblAlmacene;
use App\Models\TblPerfile;
use App\Models\TblUsuario;
use Illuminate\Http\Request;

class apiController extends Controller
{

    //     - Login:
    // o Entrada: usuario, contraseña
    // o Salida: Nombre, Almacén, Perfil

    //http://127.0.0.1:8000/api/login
    // {  
    //     "usuario": "",
    //     "password": ""
    //   }

    public function login(Request $request)
    {

        // verificar si el usuario existe y trae todos los usuarios que se llamen igual
        $usuarios = TblUsuario::where("Usuario", $request->usuario)->get();
        if ($usuarios->isEmpty()) {
            return response()->json("el usuario no existe");
        }
        foreach ($usuarios as $usuario) {
            //verificar si la contraseña coincide con algun usuario
            if ($request->password == $usuario->Password) {
                //datos a regresar
                $datos = [
                    'nombre' => $usuario->Nombre,
                    'almacen' => $usuario->Almacen_ID,
                    'perfil' => $usuario->Perfil_ID,
                ];
                return response()->json($datos);
            }
        }
        //la contraseña no coincide con nigun usuario
        return response()->json("La contraseña no coincide");
    }

    //     - Edit Usuario:
    // o Entrada: Usuario_ID quien quiere Editar, Usuario_ID a editar, (datos a editar)
    // o Salida: Si se ejecuto o no la edicion

    //http://127.0.0.1:8000/api/editarUsuario
    // {
    //     "usuario_id_quien_quiere_editar": "",
    //     "usuario_id_a_editar": "",
    //     "Password": "",
    //     "Nombre": "",
    //     "Almacen_ID": ,
    //     "Activo": ,
    //     "Perfil_ID": 
    //   }
    public function editUsuario(Request $request)
    {
        $usuario = TblUsuario::where('Usuario_ID', $request->usuario_id_quien_quiere_editar)->first();
        //verificar si el usuario existe
        if (!$usuario) {
            return response()->json("usuario_id_quien_quiere_editar no existe");
        }
        //validacion si el usuario tiene permiso para realizar la edicion
        if ($usuario->Perfil_ID != 1) {
            return response()->json("usuario_id_quien_quiere_editar no tiene permiso para realizar esta accion");
        }

        //datos del usuario a editar
        $usuario_a_editar = TblUsuario::where('Usuario_ID', $request->usuario_id_a_editar)->first();
        if (!$usuario_a_editar) {
            return response()->json("usuario_a_editar no existe");
        }
        //agregue esta validacion
        if ($usuario_a_editar->Perfil_ID == 1) {
            return response()->json("No se puede editar a un usuario administrador");
        }
        if ($request->Activo) {
            if (!is_bool($request->Activo)) {
                return response()->json("El campo Activo debe ser de tipo bool");
            }
        }
        //validacion para verificar si existe el almacen
        if ($request->Almacen_ID) {
            $almacen = TblAlmacene::find($request->Almacen_ID);
            if (!$almacen) {
                return response()->json("El almacen no existe");
            }
        }
        //validacion para verificar si existe el perfil
        if ($request->Perfil_ID) {
            $almacen = TblPerfile::find($request->Perfil_ID);
            if (!$almacen) {
                return response()->json("El perfil no existe");
            }
        }

        //preparar los datos a editar
        $usuario_a_editar->fill($request->only('Usuario', 'Password', 'Nombre', 'Almacen_ID', 'Activo', 'Perfil_ID'));

        //duardar el usuario con los datos nuevos
        $usuario_a_editar->save();
        return response()->json("Usuario editado");
    }

    // - Edit Almacen:
    // o Entrada: Usuario_ID quien quiere Editar, Almacen_ID a editar, (datos a editar)
    // o Salida: Si se ejecutó o no la edición

    //http://127.0.0.1:8000/api/editarAlmacen
    // {
    //     "usuario_id_quien_quiere_editar": "",
    //     "almacen_id_a_editar": "",
    //     "Nombre": "",
    //     "Ciudad": "",
    //     "Estado": ""
    //   }
    public function editAlmacen(Request $request)
    {
        $usuario = TblUsuario::where('Usuario_ID', $request->usuario_id_quien_quiere_editar)->first();
        //verificar si el usuario existe
        if (!$usuario) {
            return response()->json("usuario_id_quien_quiere_editar no existe");
        }
        //validacion si el usuario tiene permiso para realizar la edicion
        if ($usuario->Perfil_ID != 1) {
            return response()->json("usuario_id_quien_quiere_editar no tiene permiso para realizar esta accion");
        }

        //datos del almacen a editar
        $almacen_a_editar = TblAlmacene::where('Almacen_ID', $request->almacen_id_a_editar)->first();
        if (!$almacen_a_editar) {
            return response()->json("almacen_a_editar no existe");
        }

        //preparar los datos a editar
        $almacen_a_editar->fill($request->only('Nombre', 'Ciudad', 'Estado'));

        //duardar el usuario con los datos nuevos
        $almacen_a_editar->save();
        return response()->json("Almacen editado");
    }

    //     - Agregar usuario:
    // o Entrada: Usuario_ID quien quiere realizar la acción, datos del nuevo usuario,
    // almacén y tipo de perfil
    // o Salida: Si se ejecutó correctamente

    //http://127.0.0.1:8000/api/agregarUsuario
    // {
    //     "usuario_id_quien_quiere_agregar": "1",
    //     "Usuario": "qwe",
    //     "Password": "qweqweqweqwe",
    //     "Nombre": "asdasdasdasdasdasdas",
    //     "Almacen_ID": 2 ,
    //     "Perfil_ID": 2,
    //     "Activo": 9
    //   }

    public function agregarUsuario(Request $request)
    {
        $rules = [
            'Usuario' => 'required|max:25',
            'Password' => 'required|max:15',
            'Nombre' => 'required|max:50',
            'Almacen_ID' => 'required',
            'Activo' => 'required|bool',
            'Perfil_ID' => 'required',
        ];

        $messages = [
            'Usuario.required' => 'El campo Usuario es obligatorio.',
            'Usuario.max' => 'El campo Usuario debe ser menor a 25 caracteres.',
            'Password.required' => 'El campo Password es obligatorio.',
            'Password.max' => 'El campo Password debe ser menor a 15 caracteres.',
            'Nombre.required' => 'El campo Nombre es obligatorio.',
            'Nombre.max' => 'El campo Nombre debe ser menor a 50 caracteres.',
            'Almacen_ID.required' => 'El campo Almacen_ID es obligatorio.',
            'Activo.required' => 'El campo Activo es obligatorio.',
            'Activo.boolean' => 'El campo Activo debe ser true o false.',
            'Perfil_ID.required' => 'El campo Perfil_ID es obligatorio.',
        ];
        //hace  la validacion para que no falte ningun campo
        $this->validate($request, $rules, $messages);

        //guarda el registro 
        $usuario = new TblUsuario();
        $usuario->Usuario = $request->Usuario;
        $usuario->Password = $request->Password;
        $usuario->Nombre = $request->Nombre;
        $usuario->Almacen_ID  = $request->Almacen_ID;
        $usuario->Activo = $request->Activo;
        $usuario->Perfil_ID  = $request->Perfil_ID;
        $usuario->save();

        return response()->json("Usuario agregado");
    }

    //     - Eliminar Usuario:
    // o Entrada: Usuario_ID quien quiere Editar, Usuario_ID
    // o Salida: Salida: Si se ejecutó correctamente

    // http://127.0.0.1:8000/api/eliminarUsuario
    // {
    //     "usuario_id_quien_quiere_eliminar": "1",
    //     "usuario_id_a_eliminar": "qwe"
    //   }


    public function eliminarUsuario(Request $request)
    {
        $usuario = TblUsuario::where('Usuario_ID', $request->usuario_id_quien_quiere_eliminar)->first();
        //verificar si el usuario existe
        if (!$usuario) {
            return response()->json("usuario_id_quien_quiere_eliminar no existe");
        }
        //validacion si el usuario tiene permiso para realizar la edicion
        if ($usuario->Perfil_ID != 1) {
            return response()->json("usuario_id_quien_quiere_eliminar no tiene permiso para realizar esta accion");
        }
        $usuario_a_eliminar = TblUsuario::find($request->usuario_id_a_eliminar);
        if (!$usuario_a_eliminar) {
            return response()->json("usuario_id_a_eliminar no existe");
        }
        //agregue esta validacion
        if ($usuario_a_eliminar->Perfil_ID == 1) {
            return response()->json("No se puede eliminar a un usuario administrador");
        }
        //eliminar usuario
        $usuario_a_eliminar->delete();
        return response()->json("Usuario eliminado");
    }
}

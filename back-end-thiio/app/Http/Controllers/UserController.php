<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;



class UserController extends Controller
{
        public function test(Request $request) {
            return 'Accion de pruebas de USER-CONTROLLER';
        }
    
        public function index()
        {
            // Aquí puedes implementar la lógica para listar los usuarios
            $users = User::all();

            // Devolver los usuarios en formato JSON u otro formato según lo necesites
            return response()->json($users);
        }
    
        public function register(Request $request){
 
        //RECIBIR DATOS POR POST 
        $json = $request->getContent(); // Obtener el contenido del cuerpo de la solicitud
        $params = json_decode($json);
        $params_array = json_decode($json, true);
        
    
        
        if (!empty($params_array) && !empty($params)){
            //Limpiar datos
            $params_array = array_map('trim', $params_array);


            //Validar los datos 
            $validate = \Validator::make($params_array, [
                'name'=>'required|alpha',
                'surname'=>'required|alpha',
                'email'=>'required|email|unique:users',//|unique:users  Comprobar si ya existe el usuario 
                'password'=>'required'
            ]);

            if ($validate->fails()){
                //Validacion ha fallado
                $data = array(
                    'status' => 'error', 
                    'code' => 404, 
                    'message' => 'El usuario no se ha creado correctamente', 
                    'errors' => $validate->errors()
                );      
            }else {
                
                //Validacion pasada correctamente

                //Cifrar las contraseñas 
                $pwd = hash('sha256', $params->password);

                //Crear el usuario
                $user= new User();
                
                $user->name = $params_array['name'];
                $user->surname = $params_array['surname'];
                $user->email = $params_array['email'];
                $user->password = $pwd;
                
                //Guardar el usuario
               $user->save();

                $data = array(
                    'status' => 'success', 
                    'code' => 200, 
                    'message' => 'El usuario se ha creado correctamente',
                    'user' => $user
                );  
            }
        }else {
            $data = array(
                    'status' => 'error', 
                    'code' => 404, 
                    'message' => 'Los datos enviados no son correctos'
            );
        }   
        return response()->json( $data, $data['code']);
        }
    
    
        public function login(Request $request)
        {
            $jwtAuth = new \JwtAuth();

            // RECIBIR DATOS POR POST
            $json = $request->getContent(); // Obtener el contenido del cuerpo de la solicitud
            $params = json_decode($json);
            $params_array = json_decode($json, true);

            // VALIDAR DATOS
            $validate = \Validator::make($params_array, [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if ($validate->fails()) {
                // Validación ha fallado
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'El usuario no se ha podido identificar',
                    'errors' => $validate->errors()
                ], 404);
            }

            // CIFRAR LA PASSWORD
            $pwd = hash('sha256', $params->password);

            // DEVOLVER TOKEN O DATOS
            $signup = $jwtAuth->signup($params->email, $pwd);

            if (is_string($signup)) {
                return response()->json([
                    'status' => 'success',
                    'code' => 200,
                    'token' => $signup
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'El usuario no se ha podido identificar'
                ], 404);
            }
        }


    
        public function update(Request $request, $id) {
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        // Recoger los datos por post
        $params_array = json_decode($request->getContent(), true);

        if ($checkToken && !empty($params_array)) {
            // Sacar usuario identificado
            $user = $jwtAuth->checkToken($token, true);

            // Validar datos
            $validate = \Validator::make($params_array, [
                'name' => 'required|alpha',
                'surname' => 'required|alpha',
                'email' => 'required|email|unique:users,email,'.$id
            ]);

            if ($validate->fails()) {
                return response()->json($validate->errors(), 400);
            }

            // Quitar los campos que no quiero actualizar 
            unset($params_array['id']);
            unset($params_array['password']);
            unset($params_array['remember_token']);

            // Actualizar usuario en bbdd
            $user_update = User::where('id', $id)->update($params_array);

            // Devolver array con resultado
            $data = array(
                'code' => 200, 
                'status' => 'success',
                'user' => $user, 
                'changes' => $params_array
            );

        } else {
            $data = array(
                'code' => 400,
                'status' => 'error', 
                'message' => 'El usuario no esta identificado o datos vacíos.'
            );
        }
        return response()->json($data, $data['code']);
        }


    
    
        public function destroy(Request $request, $id){

        // Conseguir el registro del usuario a eliminar
        $user = User::find($id);

        // Verificar si el usuario existe
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Eliminar el usuario
        $user->delete();

        // Retornar una respuesta exitosa
        return response()->json(['message' => 'User deleted successfully'], 200);
        }
    
        public function detail($id) {
        
        $user = User::find($id);
        
        if (is_object($user)){
            $data = array(
                'code' => 200, 
                'status' => 'success', 
                'user' => $user
            ); 
        }else {
            $data = array(
                'code' => 404, 
                'status' => 'error', 
                'message' => 'El usuario no existe'
            ); 
        }
        return response()->json($data, $data['code']);
        }
    
    
    
    
}

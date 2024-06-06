<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_returns_test_message()
    {
        $response = $this->get('/api/user/test');

        $response->assertStatus(200);
        $response->assertSee('Accion de pruebas de USER-CONTROLLER');
    }

    /** @test */
    public function it_registers_a_user_successfully()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'El usuario se ha creado correctamente',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
    }

    /** @test */
    public function it_fails_to_register_a_user_with_existing_email()
    {
        User::create([
            'name' => 'Existing',
            'surname' => 'User',
            'email' => 'existing@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/register', [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'existing@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'status' => 'error',
                'message' => 'El usuario no se ha creado correctamente',
            ]);
    }

    /** @test */
    public function it_logs_in_a_user_successfully()
    {
        $user = User::create([
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'john@example.com',
            'password' => hash('sha256', 'password123'),  // Usar hash('sha256', ...) para la contraseña
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'password123'
        ]);

        // Agregar esta línea para depuración
        \Log::info('Response: ' . $response->getContent());

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }


    /** @test */
    public function it_fails_to_log_in_with_incorrect_password()
    {
        $user = User::create([
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'john@example.com',
            'password' => hash('sha256', 'password123'), // Usar hash('sha256', ...) para la contraseña
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'wrongpassword'
        ]);

        // Agregar esta línea para depuración
        \Log::info('Response: ' . $response->getContent());

        $response->assertStatus(404)
            ->assertJson([
                'status' => 'error',
                'message' => 'El usuario no se ha podido identificar',
            ]);
    }
    


    /** @test */
    public function test_edit_user()
    {
        // Crear un usuario para editar
        $user = User::factory()->create([
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'john@example.com',
            'password' => hash('sha256', 'password123'), // Usar hash('sha256', ...) para la contraseña
        ]);

        // Autenticar al usuario para obtener el token
        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200);
        $token = $response->json('token');

        // Datos para la actualización
        $newName = 'Jane';
        $newSurname = 'Smith';
        $newEmail = 'jane@example.com';

        // Realizar la solicitud de actualización
        $response = $this->withHeader('Authorization',  $token)
                         ->putJson('/api/user/update/' . $user->id, [
                             'name' => $newName,
                             'surname' => $newSurname,
                             'email' => $newEmail,
                         ]);

        // Verificar que la solicitud fue exitosa
        $response->assertStatus(200);

        // Verificar que el usuario fue actualizado en la base de datos
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $newName,
            'surname' => $newSurname,
            'email' => $newEmail,
        ]);
    }



    /** @test */
    public function destroy_user()
    {
        // Crear un usuario para eliminar
        $user = User::factory()->create();

        // Realizar la solicitud de eliminación
        $response = $this->withHeaders([
            'Authorization' => $this->getToken(),
        ])->deleteJson('/api/user/delete/' . $user->id);

        // Verificar que la solicitud fue exitosa
        $response->assertStatus(200);

        // Verificar que el usuario fue eliminado de la base de datos
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    // Método para obtener el token de autenticación
    protected function getToken()
    {
        // Crear un usuario para editar
        $user = User::factory()->create([
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'john@example.com',
            'password' => hash('sha256', 'password123'), // Usar hash('sha256', ...) para la contraseña
        ]);

        // Autenticar al usuario para obtener el token
        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200);
        // Devuelve el token obtenido en la respuesta
        return $response->json('token');
    }
    
    /** @test */
    public function test_user_detail()
    {
        // Crear un usuario para obtener los detalles
        $user = User::factory()->create();

        // Realizar la solicitud para obtener los detalles del usuario
        $response = $this->getJson('/api/user/detail/' . $user->id);

        // Verificar que la solicitud fue exitosa
        $response->assertStatus(200);

        // Verificar que los detalles del usuario son correctos
        $response->assertJson([
            'code' => 200,
            'status' => 'success',
            'user' => [
                'id' => $user->id,
                // Incluye aquí las otras propiedades del usuario que esperas en la respuesta
            ]
        ]);
    }

    /** @test */
    public function test_non_existent_user_detail()
    {
        // ID de usuario que no existe
        $nonExistentUserId = 9999;

        // Realizar la solicitud para obtener los detalles del usuario no existente
        $response = $this->getJson('/api/user/detail/' . $nonExistentUserId);

        // Verificar que la solicitud devuelve un código 404
        $response->assertStatus(404);

        // Verificar el mensaje de error
        $response->assertJson([
            'code' => 404,
            'status' => 'error',
            'message' => 'El usuario no existe'
        ]);
    }




     
}

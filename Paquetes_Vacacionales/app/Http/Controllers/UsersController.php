<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Database\QueryException;

class UsersController extends Controller {
    
    function __construct() {
        // $this->middleware('admin'); 
    }

    public function index(): View {
        $users = User::paginate(10);
        return view('user.index', ['users' => $users]);
    }

    public function create(): View {
        $roles = ['user', 'advanced', 'admin'];
        return view('user.create', ['roles'=> $roles]);
    }

    public function store(Request $request): RedirectResponse {
        $user = new User($request->all()); 
        $result = false;

        try {
            $user->password = Hash::make($request->password); // Cifrar contraseña
            $result = $user->save();
            $mensajetxt = "El usuario se ha creado correctamente";
        } catch(UniqueConstraintViolationException $e) {
            $mensajetxt = "Error: El correo ya está registrado";
        } catch (QueryException $e) {
            $mensajetxt = "Error: Datos incompletos";
        } catch (\Exception $e) {
            $mensajetxt = "Error fatal: ";
        }

        $mensaje = ["mensajeTexto" => $mensajetxt];

        if ($result) {
            return redirect()->route('user.index')->with($mensaje);
        } else {
            return back()->withInput()->withErrors($mensaje);
        }
    }

    public function show(User $user): View {
        // Intentamos buscar al usuario con sus relaciones
        $user = User::with(['reservas.vacacion.tipo', 'reservas.vacacion.fotos'])->find($id);

        // Si no existe, lanzamos una excepción o redirigimos
        if (!$user) {
            return redirect()->route('user.index')
                ->withErrors(['mensajeTexto' => 'El usuario solicitado no existe.']);
        }

        return view('user.show', ['user' => $user]);
    }

    public function edit(User $user): View {
        return view('user.edit', ['user' => $user]);
    }

    public function update(Request $request, User $user): RedirectResponse {
        $result = false;
        
        $user->fill($request->all());

        try {
            // Solo actualizamos la contraseña si el admin ha escrito una nueva
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $result = $user->save();
            $mensajetxt = "Usuario actualizado correctamente";
        } catch (\Exception $e) {
            $result = false;
            $mensajetxt = "No se pudo actualizar el usuario";
        }

        $mensaje = ["mensajeTexto" => $mensajetxt];

        if ($result) {
            return redirect()->route('user.index')->with($mensaje);
        } else {
            return back()->withInput()->withErrors($mensaje);
        }
    }

    public function destroy(User $user): RedirectResponse {
        try {
            $result = $user->delete();
            $mensajetxt = 'Usuario eliminado correctamente';
        } catch (\Exception $e) {
            $result = false;
            $mensajetxt = $e->getMessage();
        }

        $mensaje = ['mensajeTexto' => $mensajetxt];

        if ($result) {
            return redirect()->route('user.index')->with($mensaje);
        } else {
            return back()->withErrors($mensaje);
        }
    }

    public function editProfile() {
        $user = auth()->user(); // Sacamos el usuario actual
        return view('user.profile', ['user' => $user]);
    }

    public function updateProfile(Request $request) {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only('name', 'email'));

        return back()->with('mensajeTexto', 'Perfil actualizado correctamente.');
    }
}
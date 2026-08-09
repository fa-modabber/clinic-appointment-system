<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    /*
|--------------------------------------------------------------------------
| CRUD
|--------------------------------------------------------------------------
*/
    public function store(array $data): User
    {
        $user = User::create([
            'mobile' => $data['mobile'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'password' => isset($data['password']) ?
                $data['password']
                : null
        ]);

        return $user;
    }

    public function index(): Collection
    {
        return User::all();
    }

    public function show(User $user): User
    {
        return $user;
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);

        return $user->fresh();
    }

    public function destroy(User $user): void
    {
        $user->delete();
    }

    /*
|--------------------------------------------------------------------------
| Activation
|--------------------------------------------------------------------------
*/
    public function activate(User $user): User
    {
        $user->update([
            'is_active' => true
        ]);

        return $user;
    }

    public function deactivate(User $user): User
    {
        $user->update([
            'is_active' => false
        ]);

        return $user;
    }
}

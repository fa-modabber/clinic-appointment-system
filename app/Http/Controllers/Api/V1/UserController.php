<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

use App\Services\UserService;
use Illuminate\Support\Facades\Gate;



class UserController extends ApiController
{
    public function __construct(protected UserService $userService) {}

    public function index()
    {
        $data = $this->userService->index();
        return $this->responseSuccess(
            200,
            'retrieved successfully',
            UserResource::collection($data)
        );
    }

    public function show(User $user)
    {
        $data = $this->userService->show($user);
        return $this->responseSuccess(
            200,
            "retrieved successfully",
            new UserResource($data)
        );
    }

    public function store(StoreUserRequest $request)
    {
        $data = $this->userService->store($request->validated());
        return $this->responseSuccess(
            201,
            'created successfully',
            new UserResource($data)
        );
    }
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $this->userService->update($user, $request->validated());
        return $this->responseSuccess(
            200,
            'Updated successfully',
            new UserResource($data)
        );
    }

    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);
        $this->userService->destroy($user);
        return $this->responseSuccess(
            200,
            "deleted successfully"
        );
    }


    /*
|--------------------------------------------------------------------------
| Activation
|--------------------------------------------------------------------------
*/
    public function activate(User $user)
    {
        $data = $this->userService->activate($user);
        return $this->responseSuccess(
            200,
            'Updated successfully',
            new UserResource($data)
        );
    }

    public function deactivate(User $user)
    {
        Gate::authorize('deactivate', $user);
        $data = $this->userService->deactivate($user);
        return $this->responseSuccess(
            200,
            'Updated successfully',
            new UserResource($data)
        );
    }
}

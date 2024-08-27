<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class UserService
{
    public function dataTable()
    {
        $data = UserProfile::whereHas('user', function ($query) {
            $query->whereNotIn('username', ['ramacan', 'superadmin']);
        })->with('user.roles')
          ->orderBy('created_at', 'desc')
          ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('fullname', function ($row) {
                return $row->user->name;
            })
            ->addColumn('role', function ($row) {
                $roles = $row->user->roles->pluck('name')->toArray();
                return implode(', ', $roles);
            })
            ->addColumn('username', function ($row) {
                return $row->user->username;
            })
            ->addColumn('email', function ($row) {
                return $row->user->email;
            })
            ->addColumn('isActive', function ($row) {
                return $row->user->isActived ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Not Active</span>';
            })
            ->addColumn('action', function ($row) {
                $actionBtn = '';

                if (Gate::allows('update users')) {
                    $actionBtn .= '<a href="' .route('admin.users.edit', $row->user->id) . '" name="edit" data-id="' . $row->id . '" class="editRole btn btn-warning btn-sm me-2">
                                        <i class="ti-pencil-alt"></i>
                                   </a>';
                }

                if (Gate::allows('delete users')) {
                    $actionBtn .= '<button type="button" name="delete" data-id="' . $row->user->id . '" class="deleteUser btn btn-danger btn-sm">
                                        <i class="ti-trash"></i>
                                   </button>';
                }

                return '<div class="d-flex align-items-center">' . $actionBtn . '</div>';
            })
            ->rawColumns(['action', 'isActive'])
            ->make(true);
    }

    public function getById($id)
    {
        return User::findOrFail($id);
    }

    public function create($data)
    {
        DB::beginTransaction();
        // dd($data);
        try {
            // create user
            $user = $this->createUser($data);

            // create user profile
            $this->createUserProfile($data, $user);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Data is saved successfully.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Failed to save data: ' . $e->getMessage()
            ];
        }
    }

    public function createUser($data)
    {
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_active' => $data['is_active'],
        ]);

        // assign role
        $role = Role::find($data['role']);
        $user->assignRole($role);

        return $user;
    }

    public function createUserProfile($data, $user)
    {
        $userProfile = UserProfile::create([
            'user_id' => $user->id,
            'phone_number' => $data['phone_number'],
            'date_birth' => $data['date_birth'],
            'gender' => $data['gender'],
            'address' => $data['address']
        ]);

        if (isset($data['avatar'])) {
            $image = $data['avatar'];
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('public/assets/images/users', $imageName);

            $userProfile->avatar = $imagePath;
            $userProfile->save();
        }
    }

    public function update($data, $id)
    {
        DB::beginTransaction();

        try {
            // find user
            $user = User::findOrFail($id);

            // update user
            $this->updateUser($data, $user);

            // update user profile
            $this->updateUserProfile($data, $user);

            DB::commit();

            return [
                'success' => true,
                'message' => 'The data was successfully changed.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Failed to change the data: ' . $e->getMessage()
            ];
        }
    }

    public function updateUser($data, $user)
    {
        $user->name = $data['name'];
        $user->username = $data['username'];
        $user->email = $data['email'];

        if (!empty($data['is_active'])) {
            $user->is_active = $data['is_active'];
        }

        // Encrypt and update password only if a new one is provided
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        // Save the updated user
        $user->save();

        // sync role
        if (isset($data['role'])) {
            $role = Role::find($data['role']);
            $user->syncRoles([$role->id]);
        }

        return $user;
    }

    public function updateUserProfile($data, $user)
    {
        // Cek apakah user memiliki profile
        $userProfile = $user->profile;

        if ($userProfile) {
            // Jika profil ada, update
            $userProfile->update([
                'phone_number' => $data['phone_number'],
                'date_birth' => $data['date_birth'],
                'gender' => $data['gender'],
                'address' => $data['address']
            ]);
        } else {
            // Jika profil tidak ada, buat profil baru
            $userProfile = $user->profile()->create([
                'phone_number' => $data['phone_number'],
                'date_birth' => $data['date_birth'],
                'gender' => $data['gender'],
                'address' => $data['address']
            ]);
        }

        // Update image jika ada avatar yang di-upload
        if (isset($data['avatar'])) {
            if ($userProfile->avatar) {
                $oldImagePath = public_path('public/images/users/' . $userProfile->avatar);
                if (Storage::exists($oldImagePath)) {
                    Storage::delete($oldImagePath);
                }
            }

            $image = $data['avatar'];
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('public/images/users', $imageName);

            $userProfile->avatar = basename($imagePath);
            $userProfile->save();
        }

        return $userProfile;
    }


    public function delete($id)
    {
        DB::beginTransaction();

        try {
            // find user
            $user = User::find($id);

            // find user profile
            $userProfile = UserProfile::where('user_id', $id)->first();

            if ($user) {

                // delete user
                $this->deleteUser($user);

                // delete user profile
                $this->deleteUserProfile($userProfile);

                // delete user roles
                $user->roles()->detach();

                DB::commit();

                return [
                    'success' => true,
                    'message' => 'The data was successfully deleted.',
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Data not found.',
                ];
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Failed to delete data: ' . $e->getMessage(),
            ];
        }
    }

    public function deleteUser($user)
    {
        return $user->delete();
    }

    public function deleteUserProfile($userProfile)
    {
        $imagePath = null;
        if ($userProfile->avatar) {
            $imagePath = 'public/assets/images/users/' . $userProfile->avatar;
        }

        $userProfile->delete();

        if ($imagePath && Storage::exists($imagePath)) {
            Storage::delete($imagePath);
        }
    }
}

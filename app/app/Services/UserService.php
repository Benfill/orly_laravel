<?php

namespace App\Services;

use App\Models\User;

class UserService
{
   public function getAllUsers()
   {
       return User::all();
   }

   public function getUserById($id)
   {
       return User::find($id);
   }

   public function createUser(array $data)
   {
       return User::create($data);
   }

   public function updateUser($id, array $data)
   {
       $user = User::find($id);
       if ($user) {
           $user->update($data);
           $user->refresh();
           return $user;
       }
       return null;
   }

   public function deleteUser($id)
   {
       $user = User::find($id);
       if ($user) {
           $user->delete();
           return true;
       }
       return false;
   }
}

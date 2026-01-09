<?php

namespace App\Services;

use App\Models\Cart;

class CartService
{
   public function getAllCarts()
   {
       return Cart::all();
   }

   public function getCartById($id)
   {
       return Cart::find($id);
   }

   public function createCart(array $data)
   {
       return Cart::create($data);
   }

   public function updateCart($id, array $data)
   {
       $cart = Cart::find($id);
       if ($cart) {
           $cart->update($data);
           return $cart;
       }
       return null;
   }

   public function deleteCart($id)
   {
       $cart = Cart::find($id);
       if ($cart) {
           $cart->delete();
           return true;
       }
       return false;
   }
}

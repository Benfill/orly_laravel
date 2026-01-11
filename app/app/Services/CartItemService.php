<?php

namespace App\Services;
use App\Models\CartItem;

class CartItemService
{
   public function getAllCartItems()
   {
       return CartItem::all();
   }

   public function getCartItemById($id)
   {
       return CartItem::find($id);
   }

   public function createCartItem(array $data)
   {
       return CartItem::create($data);
   }

   public function updateCartItem($id, array $data)
   {
       $cartItem = CartItem::find($id);
       if ($cartItem) {
           $cartItem->update($data);
           return $cartItem;
       }
       return null;
   }

   public function deleteCartItem($id)
   {
       $cartItem = CartItem::find($id);
       if ($cartItem) {
           $cartItem->delete();
           return true;
       }
       return false;
   }
}

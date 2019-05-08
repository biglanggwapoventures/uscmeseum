<?php

namespace App\Helpers;

use Session;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Order;

class Cart
{
    const STRATEGY_REPLACE = 'replace';
    const STRATEGY_APPEND = 'append';

    const SESSION_CART_KEY = '_CART_';

    /**
     * updateContents
     *
     * @param string  $itemId
     * @param integer $quantity
     * @param string  $strategy
     * @return void
     */
    public static function updateContents(string $itemId, int $quantity, string $strategy = self::STRATEGY_APPEND)
    {
        if ($strategy === self::STRATEGY_REPLACE) {
            if ($quantity === 0) {
                self::removeFromContents($itemId);
            } else {
                $items = self::getContents()->put($itemId, $quantity);
                Session::put(self::SESSION_CART_KEY, $items);
            }
        } else {
            $currentQuantity = self::getItemQuantity($itemId);
            $items           = self::getContents()->put($itemId, ($quantity + $currentQuantity));
            Session::put(self::SESSION_CART_KEY, $items);
        }
    }

    /**
     * getContents
     *
     * @return Collection
     */
    public static function getContents() : Collection
    {
        return Session::get(self::SESSION_CART_KEY, collect());
    }

    /**
     * removeFromContents
     *
     * @param string $itemId
     * @return Collection
     */
    protected static function removeFromContents(string $itemId) : Collection
    {
        $items = self::getContents();

        $items->pull($itemId);

        return $items;
    }

    /**
     * count
     *
     * @return integer
     */
    public static function count() : int
    {
        return self::getContents()->count();
    }

    /**
     * has
     *
     * @param string $itemId
     * @return boolean
     */
    public static function has(string $itemId) : bool
    {
        return self::getContents()->has($itemId);
    }

    /**
     * getItemQuantity
     *
     * @param string $itemId
     * @return integer
     */
    public static function getItemQuantity(string $itemId) : int
    {
        return self::getContents()->get($itemId, 0);
    }

    /**
     * allContents
     *
     * @return Collection
     */
    public static function allContents() : Collection
    {
        $items = self::getContents();

        $products = \App\Item::with('logs')->find($items->keys()->all());

        $contents = $products->map(function ($product) use ($items) {
            return [
                'product'  => $product,
                'quantity' => $items->get($product->id)
            ];
        });

        return $contents;
    }

    /**
     * @return float
     */
    public static function getTotalAmount(): float
    {
        return static::allContents()->reduce(function ($carry, $item) {
            return $carry + ($item['quantity'] * $item['product']->selling_price);
        }, 0);
    }

    public static function clear()
    {
        Session::put(self::SESSION_CART_KEY, collect());
    }
}

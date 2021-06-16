<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use Illuminate\Http\Request;

class storeController extends Controller
{
    public function addProduct(CreateProductRequest $request)
    {

        if(! $request->ajax()){
            return redirect()->back('error', 'Request has to be submitted via Ajax request');
        }

        $filename = public_path() . '/store/products-listing';

        if(file_exists($filename)){
            $previousStoreContent = (array) json_decode(file_get_contents($filename));
        }

        $newStoreContent = [
            'product_name' => $request->product_name,
            'quantity_in_stock' => $request->quantity_in_stock,
            'price_per_item' => $request->price_per_item,
            'time' => now(),
        ];

        $previousStoreContent[] = $newStoreContent;

        file_put_contents($filename, json_encode($previousStoreContent)."\n");

        return response()->json($newStoreContent);
    }
}

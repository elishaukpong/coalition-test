<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use Illuminate\Http\Request;

class storeController extends Controller
{

    public $filename;

    /**
     * storeController constructor.
     */
    public function __construct()
    {
        $this->filename = public_path() . '/store/products-listing';
    }

    public function showListings()
    {
        $products = [];
        if(file_exists($this->filename)){
            $products = (array) json_decode(file_get_contents($this->filename));
        }

        return view('welcome')->with('products', $products);

    }
    public function addProduct(CreateProductRequest $request)
    {

        if(! $request->ajax()){
            return redirect()->back('error', 'Request has to be submitted via Ajax request');
        }

        if(file_exists($this->filename)){
            $previousStoreContent = (array) json_decode(file_get_contents($this->filename));
        }

        $newStoreContent = [
            'product_name' => $request->product_name,
            'quantity_in_stock' => $request->quantity_in_stock,
            'price_per_item' => $request->price_per_item,
            'time' => now()->format('d-M-Y'),
        ];

        $previousStoreContent[] = $newStoreContent;

        file_put_contents($this->filename, json_encode($previousStoreContent)."\n");

        return response()->json($newStoreContent);
    }
}

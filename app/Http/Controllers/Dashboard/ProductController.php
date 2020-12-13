<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Price;
use App\Product;
use App\Product_Detail;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->lessThan != null) {
          return Product::where('stock', '<=', $request->lessThan)->paginate(10);
        } elseif ($request->name != null && $request->category != null) {
            return Product::where('name', 'like', '%' . $request->name . '%')->where('category_id', $request->category)->latest()->paginate(5);
        } elseif($request->name != null) {
            return Product::where('name', 'like', '%' . $request->name . '%')->latest()->paginate(5);

        } elseif($request->category != null) {
            return Product::where('category_id', $request->category)->latest()->paginate(5);

        } else {
            return Product::latest()->paginate(5);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'  =>  'required|string|max:191|unique:products',
//            'barcode' => 'sometimes|integer',
            'category_id' => 'required|integer'
        ]);

        Product::create([
            'barcode' => $request['barcode'],
            'name'  =>  $request['name'],
            'category_id'  =>  $request['category_id']
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $product->details()->paginate(5);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'name'  =>  'required|string|max:191unique:products,name,'.$product->id,
//            'barcode' => 'sometimes|integer',
            'category_id' => 'required|integer',
            'sale_price' => 'required',
        ]);
        if ($request->sale_price != $product->sale_price) {
            Price::create([
                'product_id' => $product->id,
                'old_price' => $product->sale_price,
                'new_price' => $request->sale_price,
            ]);
        }
        $product->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
    }
}

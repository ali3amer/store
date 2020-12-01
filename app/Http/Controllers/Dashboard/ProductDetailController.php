<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Price;
use App\Product;
use App\Product_Detail;
use Illuminate\Http\Request;

class ProductDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
//
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
     * @param \Illuminate\Http\Request $request
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required|integer',
            'stock' => 'required',
            'purchase_price' => 'required',
            'sale_price' => 'required',
            'discount' => 'required',
        ]);

        $detail = Product_Detail::create([
            'product_id' => $request['product_id'],
            'stock' => $request['stock'],
            'purchase_price' => $request['purchase_price'],
            'sale_price' => $request['sale_price'],
            'discount' => $request['discount']
        ]);

        $product = Product::find($request->product_id);

        if ($request->sale_price != $product->sale_price) {
            Price::create([
                'product_id' => $product->id,
                'old_price' => $product->sale_price,
                'new_price' => $request->sale_price,
            ]);
        }

        $stock = Product::find($request->product_id)->stock;
        if ($stock == null) {
            $stock = $request->stock;
        } else {
            $stock = $stock + $request->stock;
        }
        $detail->product()->update(['sale_price' => $detail->sale_price,'purchase_price' => $detail->purchase_price, 'discount' => $detail->discount, 'stock' => $stock]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Product_Detail $product_Detail
     * @return \Illuminate\Http\Response
     */
    public function show(Product_Detail $product_Detail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Product_Detail $product_Detail
     * @return \Illuminate\Http\Response
     */
    public function edit(Product_Detail $product_Detail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Product_Detail $product_Detail
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, Product_Detail $product_Detail)
    {
        $this->validate($request, [
            'product_id' => 'required|integer',
            'stock' => 'required',
            'purchase_price' => 'required',
            'sale_price' => 'required',
            'discount' => 'required',
        ]);

        $product = Product::find($request->product_id);

        if ($request->sale_price != $product->sale_price) {
            Price::create([
                'product_id' => $product->id,
                'old_price' => $product->sale_price,
                'new_price' => $request->sale_price,
            ]);
        }
        $stock = Product::find($product_Detail->product_id)->stock;
        $stock = $stock - $product_Detail->stock;
        $stock = $stock + $request->stock;
        Product::find($product_Detail->product_id)->update(['stock' => $stock]);
        $product_Detail->update($request->all());

        $product = Product::find($request->product_id)->update(['sale_price' => $request->sale_price,'purchase_price' => $request->purchase_price, 'discount' => $request->discount, 'stock' => $stock]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Product_Detail $product_Detail
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product_Detail $product_Detail)
    {
        $stock = Product::find($product_Detail->product_id)->stock;
        $stock = $stock - $product_Detail->stock;
        Product::find($product_Detail->product_id)->update(['stock' => $stock]);
        $product_Detail->delete();
    }
}

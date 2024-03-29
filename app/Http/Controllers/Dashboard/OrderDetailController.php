<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Order_Detail;
use App\Product;
use Illuminate\Http\Request;

class OrderDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            "order.*" => "required",
        ]);

        foreach ($request->order as $order) {
            $stock = Product::find($order['product_id'])->stock;
            $stock = $stock - $order['quantity'];
            Product::find($order['product_id'])->update(['stock' => $stock]);


            $paid = ($order['sale_price'] * $order['quantity']) - $order['discount'];
            Order_Detail::create([
                'order_id' => $request['order_id'],
                'product_id' => $order['product_id'],
                'sale_price' => $order['sale_price'],
                'quantity' => $order['quantity'],
                'discount' => $order['discount'],
                'paid_price' => $paid
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Order_Detail $order_Detail
     * @return \Illuminate\Http\Response
     */
    public function show(Order_Detail $order_Detail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Order_Detail $order_Detail
     * @return \Illuminate\Http\Response
     */
    public function edit(Order_Detail $order_Detail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Order_Detail $order_Detail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order_Detail $order_Detail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Order_Detail $order_Detail
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order_Detail $order_Detail)
    {
        //
    }
}

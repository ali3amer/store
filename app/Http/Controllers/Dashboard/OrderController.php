<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Order;
use App\Order_Detail;
use App\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->id != '') {
            return Order::where('id', $request->id)->get();
        } else {
            return Order::paginate(5);
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
            'client_id' => 'required|integer'
        ]);
        return Order::create(['client_id' => $request->client_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return $order->details()->get()->keyBy('product_id');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        if (Order_Detail::where('order_id', $order->id)->count() > 0) {
            foreach ($request->order as $item) {
                $order_quantity = Order_Detail::where('order_id', $order->id)->where('product_id', $item['product_id'])->first();
                $stock = Product::find($item['product_id'])->stock;
                $stock = $stock + $order_quantity->quantity;
                Product::find($item['product_id'])->update(['stock' => $stock]);
            }
        }
        Order_Detail::where('order_id', $request->order_id)->delete();
        foreach ($request->order as $item) {
            $stock = Product::find($item['product_id'])->stock;
            $stock = $stock - $item['quantity'];
            Product::find($item['product_id'])->update(['stock' => $stock]);

            $paid = ($item['sale_price'] * $item['quantity']) - $item['discount'];
            Order_Detail::create([
                'order_id' => $request['order_id'],
                'product_id' => $item['product_id'],
                'sale_price' => $item['sale_price'],
                'quantity' => $item['quantity'],
                'discount' => $item['discount'],
                'paid_price' => $paid
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order_details = Order_Detail::where('order_id', $order->id)->get();
        foreach ($order_details as $item) {
            $stock = Product::find($item['product_id'])->stock;
            $stock = $stock + $item->quantity;
            Product::find($item['product_id'])->update(['stock' => $stock]);
        }

        Order_Detail::where('order_id', $order->id)->delete();
        $order->delete();
    }
}

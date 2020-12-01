<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Order;
use App\Order_Detail;
use App\Product;
use App\Update_Order;
use App\Update_Order_Details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'client_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);
        return Order::create(['client_id' => $request->client_id, 'user_id' => $request->user_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Order $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
//        $order = DB::table('order__details')
//            ->join('products', 'order__details.product_id', '=', 'products.id')
//            ->select('order__details.*', 'products.name')->orderBy('order__details.id')
//            ->where('order__details.order_id', $order->id)->get();
//
//        dd($order);

//        return $order->details()->get()->keyBy('product_id');
//        return response()->json(['categories' => $categories, 'sumPrice' => $sumPrice]);
        return $order->details()->join('products', 'product_id', '=', 'products.id')->get(['order__details.*', 'products.name'])->keyBy('product_id');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Order $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Order $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        if (Order_Detail::where('order_id', $order->id)->count() > 0) {

            $order->update(['user_id' => $request->user_id]);

            $update_order = Update_Order::create([
                'order_id' => $order->id,
                'client_id' => $order->client_id,
                'user_id' => $order->user_id
            ]);

            $details = Order_Detail::where('order_id', $order->id)->get();
            foreach ($details as $detail) {
                Update_Order_Details::create([
                    'update_order_id' => $update_order->id,
                    'order_id' => $detail['order_id'],
                    'product_id' => $detail['product_id'],
                    'sale_price' => $detail['sale_price'],
                    'quantity' => $detail['quantity'],
                    'discount' => $detail['discount'],
                    'paid_price' => $detail['paid_price']
                ]);
            }
            foreach ($request->order as $item) {
                $order_quantity = Order_Detail::where('order_id', $order->id)->where('product_id', $item['product_id'])->first();
                if ($order_quantity != null) {
                    $stock = Product::find($item['product_id'])->stock;
                    $stock = $stock + $order_quantity->quantity;
                    Product::find($item['product_id'])->update(['stock' => $stock]);
                }
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
     * @param \App\Order $order
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

//        Order_Detail::where('order_id', $order->id)->delete();
        $order->delete();
    }
}

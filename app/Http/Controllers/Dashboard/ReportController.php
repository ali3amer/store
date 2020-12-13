<?php

namespace App\Http\Controllers\Dashboard;

use App\Category;
use App\Expense;
use App\Http\Controllers\Controller;
use App\Order;
use App\Order_Detail;
use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;


class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->date == 'today') {
            return Order_Detail::where('created_at', 'LIKE', '%' . date('Y-m-d') . '%')->sum('paid_price');
        } elseif ($request->date == 'month') {
            return Order_Detail::where('created_at', 'LIKE', '%' . date('Y-m') . '%')->sum('paid_price');
        } elseif ($request->date == 'expense') {
            return Expense::where('created_at', 'LIKE', '%' . date('Y-m-d') . '%')->sum('price');
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
        if ($request->name == 'inventory') {

            //===========================================================================================================


            if ($request->category_id == 'all') {
                $categories = Category::with('products')->get();
            } else {
                $categories = Category::where('id', $request->category_id)->with('products')->get();
            }
//            $sumPrice = [];
//            $sumStock = [];
            $sumPrice = [];
            foreach ($categories as $category) {
                $multiply = 0;
//                $sumPrice[$category->id] = Product::where('category_id', $category->id)->sum('purchase_price');
//                $sumStock[$category->id] = Product::where('category_id', $category->id)->sum('stock');
                foreach ($category->products as $product) { // was not here
                    $multiply += $product->purchase_price * $product->stock;
                } // was not here
                $sumPrice[$category->id] = $multiply;
            }
//            return response()->json(['categories' => $categories, 'sumPrice' => $sumPrice, 'sumStock' => $sumStock]);
            return response()->json(['categories' => $categories, 'sumPrice' => $sumPrice]);

            //===========================================================================================================


        } elseif ($request->name == 'from_to') {

            $from = $request->from;
            $to = $request->to;
            if ($request->category_id == 'all') {
                $categories = Category::all();
            } else {
                $categories = Category::where('id', $request->category_id)->get();
            }

            $from_to_sum = [];

            foreach ($categories as $category) {
                $multiply = 0;
                foreach ($category->products as $product) { // was not here
                    $multiply += Order_Detail::where('product_id', $product->id)->whereBetween('order__details.created_at', [$from.' 00:00:01', $to.' 23:59:59'] )->sum('paid_price');
                } // was not here
                $from_to_sum[$category->id] = $multiply;
            }

            foreach ($categories as $category) {
                $category['orders'] = DB::table('order__details')
                    ->join('products', 'order__details.product_id', '=', 'products.id')
                    ->join('categories', 'products.category_id', '=', 'categories.id')
                    ->select('order__details.*', 'categories.name as Category_ame', 'products.name as product_name')->orderBy('order__details.created_at')
                    ->whereBetween('order__details.created_at', [$from.' 00:00:01', $to.' 23:59:59'] )->where('categories.id', $category->id)->get();
            }

            $expenses = Expense::whereBetween('created_at', [$from.' 00:00:01', $to.' 23:59:59'])->get();

            $expensesSum = Expense::whereBetween('created_at', [$from.' 00:00:01', $to.' 23:59:59'])->sum('price');

//                return $categories;
            return response()->json(['categories' => $categories, 'from_to_sum' => $from_to_sum, 'expenses' => $expenses, 'expensesSum' => $expensesSum]);

            //===========================================================================================================


        } elseif ($request->name == 'today') {
            if ($request->category_id == 'all') {
                $categories = Category::all();
            } else {
                $categories = Category::where('id', $request->category_id)->get();
            }

            $today_sum = [];

            foreach ($categories as $category) {
                $multiply = 0;
                foreach ($category->products as $product) { // was not here
                    $multiply += Order_Detail::where('product_id', $product->id)->where('order__details.created_at', 'LIKE', '%' . date('Y-m-d') . '%')->sum('paid_price');
                } // was not here
                $today_sum[$category->id] = $multiply;
            }

            foreach ($categories as $category) {
                $category['orders'] = DB::table('order__details')
                    ->join('products', 'order__details.product_id', '=', 'products.id')
                    ->join('categories', 'products.category_id', '=', 'categories.id')
                    ->select('order__details.*', 'categories.name as Category_ame', 'products.name as product_name')
                    ->where('order__details.created_at', 'LIKE', '%' . date('Y-m-d') . '%')->where('categories.id', $category->id)->get();

            }

            $date = date('Y-m-d');

            return response()->json(['categories' => $categories, 'today_sum' => $today_sum, 'date' => $date]);

        }


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

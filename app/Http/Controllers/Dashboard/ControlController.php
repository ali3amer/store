<?php

namespace App\Http\Controllers\Dashboard;

use App\Expense;
use App\Http\Controllers\Controller;
use App\Order;
use App\Order_Detail;
use App\Update_Expense;
use App\Update_Order;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;


class ControlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function ordersIndex(Request $request)
    {
        if ($request->order != null) {
            return Order::where('id', $request->order)->withTrashed()->with('details.product', 'user')->with('orders_update.update_details.product')->with('orders_update.user')->first();
        } else {
            $orders = Update_Order::distinct()->pluck('order_id')->all();
            return Order::whereIn('id', $orders)->paginate(5);
        }
    }

    public function deletedOrders(Request $request)
    {

        if ($request->order != null) {
            return Order::where('id', $request->order)->withTrashed()->with('details.product')->with('user')->first();

        } else {
            return Order::onlyTrashed()->with('user')->paginate(5);
        }
    }

    public function expensesIndex(Request $request)
    {

        if ($request->expense != null) {
            return Expense::where('id', $request->expense)->withTrashed()->with('user')->with('expenses_update.user')->first();
        } else {
            $expenses = Update_Expense::distinct()->pluck('expense_id')->all();
            return Expense::whereIn('id', $expenses)->with('user')->paginate(5);
        }
    }
    public function deletedExpenses(Request $request)
    {

        return Expense::onlyTrashed()->with('user')->paginate(5);

    }
}

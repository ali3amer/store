<?php

namespace App\Http\Controllers\Dashboard;

use App\Expense;
use App\Http\Controllers\Controller;
use App\Update_Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->name != null) {
            return Expense::where('name', 'LIKE', '%' . $request->name . '%')->latest()->paginate(5);
        } else {
            return Expense::paginate(5);
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
            'name' => 'required',
            'price' => 'required'
        ]);

        return Expense::create([
            'name' => $request['name'],
            'price' => $request['price'],
            'user_id' => $request['user_id']
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required'
        ]);
        Update_Expense::create([
            'expense_id' => $expense->id,
            'name' => $expense->name,
            'price' => $expense->price,
            'user_id' => $expense->user_id,
        ]);
        $expense->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();
    }
}

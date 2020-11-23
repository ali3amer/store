<?php

namespace App\Http\Controllers\Dashboard;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->category == 'all') {
            return Category::all()->keyBy('id');
        } elseif ($request->name != null) {
            return Category::where('name', 'like', '%' . $request->name . '%')->paginate(5);
        } elseif ($request->getProducts == 'all') {
            $categories = Category::with('products')->get();
            $products = [];
            foreach ($categories as $category) {
                $rows = $category->products()->get();
                foreach ($rows as $row) {
                    $products[$row->id] = $row;
                    $products[$row->id]->quantity = 1;
                    $products[$row->id]->product_id = $row->id;
                }
            }
            return $products;


        } else {
            return Category::latest()->paginate(5);
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
            'name' => 'required|string|max:191|unique:categories'
        ]);

        return Category::create([
            'name' => $request['name']
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Category $category
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Category $category)
    {
        return $category->products()->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Category $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191unique:categories,name,' . $category->id
        ]);
        $category->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Category $category
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Category $category)
    {
        $category->delete();
    }
}

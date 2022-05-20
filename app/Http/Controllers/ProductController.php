<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = Product::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', 'products.action')
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('products.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $request->validate([
            'product_name' => 'required',
            'product_price' => 'required',
        ]);
        

        $product = Product::updateOrCreate(['id' => $request->product_id],
                ['product_name' => $request->product_name, 'product_price' => $request->product_price]); 

        $product->product_description = $request->product_description;
        $images = [];
         /* validation for images */
        if($request->hasfile('product_image'))
        {
            
            foreach($request->file('product_image') as $file)
            {
                $name = $file->getClientOriginalName();    
                $file->move(storage_path('app/public').'/product-images/', $name); 

                $images[] = $name;
            }
            $product->product_image = json_encode($images);
        }
        $product->save();
        return redirect()->route('products.index')->with('success','Product has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('products.edit',compact('product'));
    }

    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, Request $request)
    {
        $product = Product::where('id',$request->id)->delete();
        return Response()->json($product);
    }
}

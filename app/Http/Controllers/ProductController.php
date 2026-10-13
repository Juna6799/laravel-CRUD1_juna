<?php

namespace App\Http\Controllers;

use App\Models\Product;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Product::latest()->get(),200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'=>'required',
            'harga'=>'required',
            'stok' => 'required',
            'deskripis' => 'required',
            'gambar' => 'nullable|image'
        ]);

        $gambar = "";
        if ($request ->hasFile("gambar")){
           $gambar = time().".".
           $request -> gambar -> extension();
           $request -> gambar -> storeAs(
            "products",
            $gambar,
            "public"
           );
        }

        $product = Product::create([
            'nama'=> $request->nama,
            'harga'=> $request->harga,
            'stok'=> $request->stok,
            'deskripis' => $request -> deskripis,
            'gambar' => $gambar
        ]);

        return response()-> json([
            'message' => 'Data berhasil ditambahkan',
            'data' => $product
        ],201);
    }

    public function show(string $id)
    {
        $product = Product::find($id);
        if(!$product){
            return response()->json([
                'message' => 'DATA TIDAK DITEMUKAN'
            ],404);
        }
        return response()->json(data: $product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $product = Product::find($id);

        if(!$product){
            return response()->json([
                'message' => 'data tidak ditemukan'
            ],404);
        }

        $product -> nama = $request ->nama;
        $product -> harga = $request -> harga;
        $product -> stok = $request -> stok;
        $product -> deskripis = $request -> deskripis;

        if($request -> hasFile("gambar")){
            $gambar = time() ."." . $request -> gambar ->extension();
            $request -> gambar -> storeAs(
                'products',
                $gambar,
                'public'
            );

        }

        $request->validate([
            'nama' => 'required',
            'harga' => 'required',
            'stok' => 'required',
            'deskripis' => 'required'
        ]);

        $product -> update([
            'nama'=> $request->nama,
            'harga'=> $request->harga,
            'stok'=> $request->stok,
            'deskripis' => $request -> deskripis
        ]);

        return response()-> json([
            'message' => 'Data berhasil ditambahkan',
            'data' => $product
        ]);

        if ($request ->hasFile("gambar")){
           $gambar = time().".".
           $request -> gambar -> extension();
           $request -> gambar -> storeAs(
            "products",
            $gambar,
            "public"
           );
           $product->gambar=$gambar;
        }
        $product->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);

        if(!$product){
            return response()->json([
                'message' => 'DATA TIDAK DITEMUKAN'
            ],404);
        }
        $product->delete();
        return response()-> json([
            'message'=> 'DATA BERHASIL DI HAPUS'
        ]);
    }
}

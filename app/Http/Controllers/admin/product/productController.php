<?php

namespace App\Http\Controllers\admin\product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\traits\generalTrait;
use Illuminate\Database\QueryException;

class productController extends Controller
{
    use generalTrait;
    public function index()
    {
        $products = DB::table("products")->select("id","name_en","details_en","price")->get();
        // $products = DB::select("SELECT `products`.* FROM `products`");
        // print_r($products);
        return view('en.admin.products.all',compact('products'));
    }
    public function create()
    {
        $suppliers = DB::table('suppliers')->select("id","name")->get();
        $brands = DB::table('brand')->select("id","name")->get();
        $subcates = DB::table('subcate')->select("id","name")->get();
        return view('en.admin.products.create' ,compact('suppliers','brands','subcates'));
    }
    public function store (Request $request)
    {
        $rules = [
            "name_en"=>"required|uniqe:products,name_en|string|min:2",
            "name_en"=>"required|uniqe:products,name_en|string|min:2",
            "price"=>"required|numeric|min:1",
            // "details_en"=>"nullable",
            // "details_ar"=>"nullable",
            "supplier_id"=>"required|integer|exists:suppliers,id",
            "brand_id"=>"required|integer|exists:brand,id",
            "subcate_id"=>"required|integer|exists:subcates,id",
            "photo"=>"required|image|mimes:png,jpg,jpeg|max:1024"
        ];
        $request->validate($rules);
        $imageName = $this->uploadPhoto($request->photo,'product');
        $data = $request->except('_token','photo');
        $data['photo'] = $imageName;
        DB::table('products')->insert($data);
        return redirect('admin/products/all-products');

    }
    public function edit($id)
    {
        $products = DB::table('products')->select("products.*")->where("products.id",'=',$id)->first();
        $suppliers = DB::table('suppliers')->select("id","name")->get();
        $brands = DB::table('brand')->select("id","name")->get();
        $subcates = DB::table('subcate')->select("id","name")->get();
        return view('en.admin.products.edit',compact('products','suppliers','brands','subcates'));
    }
    public function update(Request $request, $id)
    {
        $rules = [
            "name_en"=>"required|string|min:2",
            "name_en"=>"required|string|min:2",
            "price"=>"required|numeric|min:1",
            // "details_en"=>"nullable",
            // "details_ar"=>"nullable",
            "supplier_id"=>"required|integer|exists:suppliers,id",
            "brand_id"=>"required|integer|exists:brand,id",
            "subcate_id"=>"required|integer|exists:subcates,id",
            "photo"=>"nullable|image|mimes:png,jpg,jpeg|max:1024"
        ];
        $request->validate($rules);
        $data = $request->except('_token', '_method');
        if($request->has('photo')){
            $imageName = $this->uploadPhoto($request->photo,'products');
            $data = $request->except('_token', '_method','photo');
            $data['photo']=$imageName;
        }

        $check = DB::table('products')->where('id','=',$id)->update($data);

        if($check){
            return redirect()->back()->with('Success','The product has been updated');
        }
        return redirect()->back()->with('Error','Something went wrong');
    }

    public function delete(Request $request)
    {
        $rules = [
            'id'=>'required|integer|exists:products,id'
        ];
        $request->validate($rules);
        $photoPath = public_path("images\product\\".$request->photo);
        // return $photoPath;
        if(file_exists($photoPath)){
            unlink($photoPath);
        }
        DB::table('products')->where('id','=', $request->id)->delete();
        return redirect()->back()->with('Success','The product has been deleted successfully');
    }


}

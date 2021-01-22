<?php

namespace App\Http\Controllers\Api\product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Product;
use Illuminate\Support\Facades\Validator;
use App\traits\generalTrait;

class productController extends Controller
{
    use generalTrait;
    // public function index()
    // {
    //     $products = Product ::all();
    //     return response()->json(['data'=>$products],401);
    // }

    public function index()
    {
        $products= Product:: select('id','name_en','name_ar','details_ar','price','photo')->paginate(4);
        return $this->returnData('products',$products,);
    }

    Public function store(Request $request)
    {
        $rules=[
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
        $validator= validator::make($request->all(),$rules);
        if ($validator->fails()){
        // $errors=$validator->errors();
        // return $errors;
        $this->returnValidationError($validator);
        }
        $imageName = $this->uploadPhoto($request->photo,'product');
        $data = $request ->except('photo','subcates');
        $data['photo']=$imageName;
        $data['subcate_id']=$request->subCat;
        Product::insert($data);
        return response()->json(['The product has been successfully saved']);
    }

    public function update(Request $request,$id)
    {
    $rules=[
        'id'=>'required|exists:products,id|integer',
        "name_en"=>"required|string|min:2",
        "name_ar"=>"required|string|min:2",
        "price"=>"required|numeric|min:1",
        "supplier_id"=>"required|integer|exists:suppliers,id",
        "brand_id"=>"required|integer|exists:brand,id",
        "subcate_id"=>"required|integer|exists:subcates,id",
        "photo"=>"image|mimes:png,jpg,jpeg|max:1024"
    ];

    $validator = validator::make($request->all(),$rules);
    if ($validator->fails()) {
    // $errors = $validator->errors();
        //return $errors;
        return $this->returnValidationError($validator);
    }
    $data = $request->except('id');
    if($request->has('photo')){
        $imageName = $this->uploadPhoto($request->photo,'product');
        $data = $request->except('id','photo');
        $data['photo'] = $imageName;
    }
    product::where('id','=',$request->id)->id->update($data);
    }

    // public function update(Request $request, $id){}
    public function unlink(Request $request){
        $rules = [
            'id'=>'required|exists:products,id|integer'
        ];
        $validator = validator::make($request->all(),$rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $errors;
        }
        $product= product::find($request->id);
        $photoPath=public_path('images\product\\$request->photo');
        if (file_exists($photoPath)){
            unlink($photoPath);
        }
        product::destroy($request->id);
        return redirect()->back()->with('message', 'data has been updated');
        // return $this->returnSuccessMessage('msg'=>'data has been updated');
    }
}



?>







    // public function index()
    // {
    //     $products= Product::paginate(2);
    //     return response()->json(['data'=>$products],200);
    // }

    // public function index()
    // {
    //     $products= Product:: select('id','name_en','name_ar','details_ar','price','photo')->paginate(4);
    //     return response()->json([$products],200);
    // }







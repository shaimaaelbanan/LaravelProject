@extends('en.admin.template.layout')
@section('title','Edit Product')
@section('content')
    <div class="col-12">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Edit Product</h3>
            </div>
            @if (Session()->has('Success'))
                <div class="alert alert-success"> {{Session()->get('Success')}} </div>
                @php
                    Session()->forget('Success')
                @endphp
            @endif
            @if (Session()->has('Error'))
                <div class="alert alert-danger"> {{Session()->get('Error')}} </div>
                @php
                    Session()->forget('Error')
                @endphp
            @endif
            <!-- /.card-header -->
            <!-- form start -->
            <form method="POST" action="{{asset('admin/products/update-product/'.$products->id)}}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                {{-- @forelse ($products as $key => $value) --}}
                <div class="card-body">
                <div class="form-group">
                    <label for="exampleInputName1">Product Name En</label>
                    <input type="text" name="name_en" class="form-control" id="exampleInputName1" placeholder="Enter name en" value="{{$products->name_en}}">
                </div>
                @error('name_en')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <div class="form-group">
                    <label for="exampleInputName1">Product Name Ar</label>
                    <input type="text" name="name_ar" class="form-control" id="exampleInputName1" placeholder="Enter name ar" value="{{$products->name_ar}}">
                </div>
                @error('name_ar')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <div class="form-group">
                    <label for="exampleInputNum1">Price</label>
                    <input type="number" name="price" class="form-control" id="exampleInputNum1" placeholder="Enter price" value="{{$products->price}}">
                </div>
                @error('price')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <div class="form-group">
                    <label for="exampleInputName1">Details En</label>
                    <textarea name="details_en" class="form-control" id="exampleInputName1" cols="30" rows="10">{{$products->details_en}}</textarea>
                </div>
                @error('details_en')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <div class="form-group">
                    <label for="exampleInputName1">Details Ar</label>
                    <textarea name="details_ar" class="form-control" id="exampleInputName1" cols="30" rows="10">{{$products->details_ar}}</textarea>
                </div>
                @error('details_ar')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <div class="form-group">
                    <label for="exampleInputName1">Supplier</label>
                    <select name="supplier_id" id="" class="form-control">
                        @foreach ($suppliers as $key => $value)
                        <option {{$products->supplier_id == $value->id ? 'selected' : ''}} value="{{$value->id}}">{{$value->name}}</option>
                        @endforeach
                    </select>
                </div>
                @error('supplier_id')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <div class="form-group">
                    <label for="exampleInputName1">Brand</label>
                    <select name="brand_id" id="" class="form-control">
                        @foreach ($brands as $key => $value)
                        <option {{$products->brand_id == $value->id ? 'selected' : ''}} value="{{$value->id}}">{{$value->name}}</option>
                        @endforeach
                    </select>
                </div>
                @error('brand_id')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <div class="form-group">
                    <label for="exampleInputName1">SubCates</label>
                    <select name="subcate_id" id="" class="form-control">
                        @foreach ($subcates as $key => $value)
                        <option {{$products->subcate_id == $value->id ? 'selected' : ''}} value="{{$value->id}}">{{$value->name}}</option>
                        @endforeach
                    </select>
                </div>
                @error('subcate_id')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <div class="form-row">
                    <div class="col-3">
                        <img src="{{asset('images/product/'.$products->photo)}}" alt="" style="width:100px; height:100px;">
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputFile">Photo input</label>
                    <div class="input-group">
                    <div class="custom-file">
                        <input type="file" name="photo" class="custom-file-input" id="exampleInputFile">
                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                    </div>
                </div>
                @error('photo')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <!-- /.card-body -->
                <div class="card-footer">
                <button type="submit" class="btn btn-primary">Update</button>
                </div>
            {{-- @empty --}}
            {{-- @endforelse --}}
            </form>
        </div>
        <!-- /.card -->
    </div>
@endsection
@section('scripts')
    <!-- bs-custom-file-input -->
    <script src="{{asset('../../plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
    <!-- Page specific script -->
    <script>
        $(function () {
        bsCustomFileInput.init();
        });
    </script>
@endsection

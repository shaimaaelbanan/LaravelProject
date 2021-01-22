@extends('en.admin.template.layout')
@section('title','All Products')
@section('links')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('../../plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection
@section('content')
    <div class="col-12">
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
    </div>
    <div class="col-12">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
            <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Details</th>
            <th>Price</th>
            <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($products as $key => $value)
            <tr>
                <td>{{$value->id}}</td>
                <td>{{$value->name_en}}</td>
                <td>
                @php
                    $wordsCount = 20;
                    $detailsArray = str_word_count($value->details_en, 1);
                    array_splice($detailsArray, $wordsCount, count($detailsArray));
                    $detailsString = implode(" ", $detailsArray);
                    echo $detailsString . '...';
                @endphp
                </td>
                <td>{{$value->price}}</td>
                <td>
                    <div class="row">
                        <div class="col-6">
                            <a href="{{asset('admin/products/edit-product/'.$value->id)}}" class="btn btn-warning">Edit</a>
                        </div>
                        <div class="col-6">
                            {{-- <form method="POST" action="{{asset('admin/products/delete')}}" enctype="multipart/form-data">
                                @method('DELETE')
                                @csrf --}}
                                <input type="hidden" name="id" value="{{$value->id}}">
                                {{-- <input type="hidden" name="photo" value="{{$value->photo}}"> --}}
                                <button class="btn btn-danger"> Delete </button>
                            {{-- </form> --}}
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <p> No Products! </p>
            @endforelse

            </tbody>
            <tfoot>
            <tr>
            <th>Rendering engine</th>
            <th>Browser</th>
            <th>Platform(s)</th>
            <th>Engine version</th>
            <th>CSS grade</th>
            </tr>
            </tfoot>
        </table>
    </div>

@endsection
@section('scripts')
    <!-- DataTables  & Plugins -->
    <script src="{{asset('../../plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('../../plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('../../plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('../../plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('../../plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{asset('../../plugins/pdfmake/vfs_fonts.js')}}"></script>
    <script src="{{asset('../../plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('../../plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('../../plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
    <!-- Page specific script -->
    <script>
        $(function () {
        $("#example1").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        // $('#example2').DataTable({
        //     "paging": true,
        //     "lengthChange": false,
        //     "searching": false,
        //     "ordering": true,
        //     "info": true,
        //     "autoWidth": false,
        //     "responsive": true,
        // });
        });
    </script>
@endsection

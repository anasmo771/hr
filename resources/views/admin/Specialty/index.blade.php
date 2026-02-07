

                                @extends('admin.layout.master')

@section('title')
    <title> التـخـصصات </title>
@endsection
@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">لوحة التحكم</a></li>
                                <li class="breadcrumb-item" aria-current="page">التـخـصصات </li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">التـخـصصات </h2>
                            </div>
                        </div>
                           <div class="text-sm-end">
                          <a href="{{route('Specialties.create')}}" type="button" class="btn btn-primary btn-rounded waves-effect waves-light mb-2 me-2">
                            <i class=""></i> اضافة تخصص جديد</a>
                            </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            @include('admin.layout.validation-messages')

            <!-- [ Main Content ] start -->





                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row mb-2">
                                                    <div class="col-sm-4">

                                                    </div>

                                                    <div class="col-sm-8">
                                                     
                                                    </div><!-- end col-->


                                                </div>

                                                <div class="table-responsive">
                                                    <table class="table align-middle table-nowrap table-check">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th scope="col" style="width: 70px;">#</th>
                                                                <th scope="col">التخصص</th>
                                                                <th scope="col">عدد الموظفين</th>
                                                                <th class="col">تـعـديـل</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($all as $index => $spec)
                                                            <tr>
                                                                <td class="text-center">{{ $index+1 }} </td>
                                                                <td>
                                                                    <h5 class="font-size-14 mb-1"><a href="javascript: void(0);" class="">{{$spec->name}}</a></h5>
                                                                </td>

                                                                <td>
                                                                    <h5 class="font-size-14 mb-1"><a href="javascript: void(0);" class="">{{$spec->employees->where('startout_data',null)->count()}}</a></h5>
                                                                </td>

                                                                {{-- <td>

                                                                    <a href="{{route('monthPayeds.show',[$mini->id])}}" class="btn btn-primary btn-sm btn-rounded">
                                                                        عــرض الـتـفـاصيل
                                                                    </a>

                                                                </td> --}}


                                                                <td>

                                                                    <ul class="list-inline mb-0 me-2">
    <li class="list-inline-item px-2">
        <a href="{{ route('Specialties.edit', [$spec->id]) }}" class="btn btn-sm btn-primary" title="تعديل">
            <i class="mdi mdi-pencil font-size-18"></i>
        </a>
    </li>
</ul>


                                                                </td>
                                                            </tr>
                                                            @endforeach

                                                        </tbody>
                                                    </table>
                                                </div>
                                                <ul class="pagination pagination-rounded justify-content-center mb-2">
                                                    {{ $all->links('pagination::bootstrap-4') }}
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end row -->
                            </div> <!-- container-fluid -->
                        </div>
                        <!-- End Page-content -->

                        <!-- Modal -->
                        <div class="modal fade orderdetailsModal" tabindex="-1" role="dialog" aria-labelledby="orderdetailsModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="orderdetailsModalLabel">البحث بتاريخ معين</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        {{-- <p class="mb-2">Product id: <span class="">#SK2540</span></p>
                                        <p class="mb-4">Billing Name: <span class="">Neal Matthews</span></p> --}}

                                        <form action="" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" value="" id="mini_id" name="id">
                                            <div class="mb-3">
                                                <label for="manufacturerbrand">تحديد التاريخ</label>
                                                <div class="input-daterange input-group" id="project-date-inputgroup" data-provide="datepicker" data-date-format="dd M, yyyy"  data-date-container='#project-date-inputgroup' data-date-autoclose="true">
                                                    <input type="month" class="form-control" style="direction: rtl;" placeholder="تاريخ الاصدار" name="date" required oninvalid="this.setCustomValidity('الرجاء تحديد التاريخ')" oninput="this.setCustomValidity('')" >
                                                </div>
                                            </div>

                                            <div class="modal-footer justify-content-between">

                                                <button type="submit" class=" btn btn-primary waves-effect waves-light">البحث</button>

                                                <button type="button" class=" btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                            </div>

                                        </form>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end modal -->

                        @endsection



                        @section('js')
                <script>
                    $( document ).ready(function() {
                        document.getElementById("rtl-mode-switch").trigger('click');
                });

                    function searchBy(id){
            document.getElementById("mini_id").value = id;
        }
        </script>

        @endsection

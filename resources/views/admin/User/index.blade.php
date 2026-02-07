
        @extends('admin.layout.master')

        @section('title')
            <title> كل المستخدمين </title>
        @endsection


@section('css')
<style>
    .onlyTable {
        counter-reset: rowNumber;
    }

    .onlyTable tr {
        counter-increment: rowNumber;
    }

    .onlyTable tr td:first-child::before {
        content: counter(rowNumber);
        min-width: 1em;
        margin-right: 0.5em;
    }
</style>
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
                                        <li class="breadcrumb-item" aria-current="page">كل المستخدمين</li>
                                    </ul>
                                </div>
                                <div class="col-12">
                                    <div class="page-header-title">
                                        <h2 class="mb-0">كل المستخدمين</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- [ breadcrumb ] end -->

                    @include('admin.layout.validation-messages')

                    <!-- [ Main Content ] start -->





                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table align-middle table-nowrap table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th scope="col" style="width: 70px;">#</th>
                                                        <th scope="col">الإســـم</th>
                                                        <th scope="col">الـبـريـد الالكتـرونـي</th>
                                                        <th scope="col">الحالة</th>
                                                        <th scope="col">الـوظـيفة</th>
                                                        <th scope="col">تــعــديــل</th>
                                                    </tr>
                                                </thead>
                                                
                                                <tbody>
                                                @foreach ($users as $user)
                                                <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>

                                                <td>
                                                    {{-- إن بغيت صورة ارجع افتح الـdiv وأغلقه صح --}}
                                                    {{-- <div class="avatar-xs d-inline-block me-2">
                                                    <a href="javascript:void(0)" class="d-inline-block">
                                                        <img src="{{ asset(Storage::url($user->image)) }}" width="50" alt="" class="rounded-circle avatar-xs">
                                                    </a>
                                                    </div> --}}
                                                    {{ $user->name }}
                                                </td>

                                                <td>
                                                    <h5 class="font-size-14 mb-1"><a href="javascript:void(0)">{{ $user->email }}</a></h5>
                                                </td>

                                                <td>
                                                    <h5 class="font-size-14 mb-1 {{ $user->active ? 'text-success' : 'text-danger' }}">
                                                    {{ $user->active ? 'مفعل' : 'غير مفعل' }}
                                                    </h5>
                                                </td>

                                                <td>
                                                    @if($user->roles->first())
                                                    {{ $user->roles->first()->name }}
                                                    @else
                                                    لايوجد
                                                    @endif
                                                </td>

                                                <td>
                                                    <ul class="list-inline font-size-20 contact-links mb-0">
                                                    <li class="list-inline-item px-2">
                                                        <a href="{{ route('users.edit', $user->id) }}" class="text-primary" title="تعديل">
                                                        <i class="mdi mdi-pencil font-size-18"></i>
                                                        </a>
                                                    </li>

                                                    @if ($user->id != 1)
                                                        <li class="list-inline-item px-2">
                                                        <a href="{{ route('users.show', $user->id) }}" class="btn {{ $user->active ? 'btn-danger' : 'btn-primary' }}">
                                                            {{ $user->active ? 'إلغاء التفعيل' : 'تفعيل' }}
                                                        </a>
                                                        </li>
                                                    @endif
                                                    </ul>
                                                </td>
                                                </tr>
                                                @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                        <ul class="pagination pagination-rounded justify-content-center mb-2">
                                            @if(isset($query))
                                            {{ $users->appends($query)->links('pagination::bootstrap-4') }}
                                          @else
                                          {{ $users->links('pagination::bootstrap-4') }}
                                          @endif
                                                                                </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->
@endsection

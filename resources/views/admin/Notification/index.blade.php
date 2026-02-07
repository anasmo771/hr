@extends('admin.layout.master')


@section('title')
<title>كل الاشعارات</title>
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
                                <li class="breadcrumb-item"><a href="#">كــل الإشــعــارات </a></li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">كــل الإشــعــارات </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            @include('admin.layout.validation-messages')

            <!-- [ Main Content ] start -->



                        <div class="row">
                            @foreach ($notifications as $notif)
                                <div class="col-xl-4 col-sm-6 mb-4">
                                    <div class="card shadow-sm border-0">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <!-- الأيقونة -->
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar-md">
                                                        <span class="avatar-title rounded-circle bg-soft-primary text-primary" style="font-size: 26px;">
                                                            <i class="fa fa-bell"></i>
                                                        </span>
                                                    </div>
                                                </div>

                                                <!-- العنوان والوصف -->
                                                <div class="flex-grow-1">
                                                    <h5 class="text-truncate mb-1">
                                                        <a href="javascript:void(0);" class="text-dark fw-semibold text-decoration-none">
                                                            {{$notif->title}}
                                                        </a>
                                                    </h5>
                                                    <p class="text-muted small mb-0">{{$notif->desc}}</p>
                                                </div>

                                                <!-- زر الحذف -->
                                                <form method="POST" action="{{ route('notifications.destroy', [$notif->num]) }}" class="ms-2">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light text-danger" title="حذف الإشعار">
                                                        <i class="mdi mdi-delete-outline"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- الفوتر -->
                                        <div class="card-footer bg-light d-flex justify-content-between align-items-center py-2">
                                            <!-- الأولوية -->
                                            <div>
                                                @if($notif->priority == 1)
                                                    <span class="badge bg-success">ضــعــيــف</span>
                                                @elseif($notif->priority == 2)
                                                    <span class="badge bg-warning">مــتــوســط</span>
                                                @else
                                                    <span class="badge bg-danger">مـــهــــم</span>
                                                @endif
                                            </div>
                                            <!-- التاريخ -->
                                            <small class="text-muted">
                                                <i class="bx bx-calendar me-1"></i> {{$notif->created_at->format('Y-m-d')}}
                                            </small>
                                        </div>
                                    </div>
                                </div>

                            @endforeach


                        </div>
                        <!-- end row -->

                        <div class="row">
                            <div class="col-lg-12">
                                <ul class="pagination pagination-rounded justify-content-center mb-2">
                                    {{ $notifications->links('pagination::bootstrap-4') }}
                                </ul>
                            </div>
                        </div>
                        <!-- end row -->

                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                @endsection

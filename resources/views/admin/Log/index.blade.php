

                @extends('admin.layout.master')

@section('title')
    <title> سـجــل النظــام </title>
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
                                <li class="breadcrumb-item" aria-current="page">سجـــل النظــام </li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">سجـــل النظــام </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            @include('admin.layout.validation-messages')

            <!-- [ Main Content ] start -->




                @if($logs->count() > 0)


                <div class="row">
                    @foreach ($logs as $log)
                    <div class="col-xl-4 col-sm-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <!-- المحتوى -->
                                <div class="flex-grow-1 overflow-hidden">
                                    <h5 class="text-truncate mb-1 d-flex align-items-center">
                                        <i class="fas fa-history text-primary" style="font-size: 15px; margin-left: 8px;"></i>
                                        <a href="javascript:void(0);" class="text-dark fw-semibold text-decoration-none">
                                            {{$log->title}}
                                        </a>
                                    </h5>
                                    <!-- وصف السجل -->
                                    @if($log->user_id)
                                        @if($log->emp_id)
                                            <p class="text-muted small mb-0">
                                                {{$log->log}} للموظف 
                                                <span class="text-primary fw-semibold">{{$log->employee->person->name}}</span> 
                                                من قبل المستخدم 
                                                <span class="text-primary fw-semibold">{{$log->creator->name}}</span>
                                            </p>
                                        @else
                                            <p class="text-muted small mb-0">
                                                {{$log->log}} من قبل المستخدم 
                                                <span class="text-primary fw-semibold">{{$log->creator->name}}</span>
                                            </p>
                                        @endif
                                    @else
                                        @if($log->emp_id)
                                            <p class="text-muted small mb-0">
                                                {{$log->log}} للموظف 
                                                <span class="text-primary fw-semibold">{{$log->employee->person->name}}</span>
                                            </p>
                                        @else
                                            <p class="text-muted small mb-0">{{$log->log}}</p>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- الفوتر -->
                        <div class="card-footer bg-light d-flex justify-content-between align-items-center py-2">
                            <!-- اسم المستخدم -->
                            <div>
                            @if($log->user_id)
                                <span class="badge bg-primary text-white animate__animated animate__pulse animate__infinite">
                                    {{$log->creator->name}}
                                </span>
                            @endif
                            </div>
                            <!-- التاريخ والوقت -->
                            <small class="text-muted">
                                <i class="bx bx-calendar me-1"></i> {{$log->created_at->format('Y-m-d')}}
                                <i class="bx bx-time me-1 ms-2"></i> {{$log->created_at->format('H:i:s')}}
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
                            {{ $logs->links('pagination::bootstrap-4') }}
                        </ul>
                    </div>
                </div>
                <!-- end row -->


                @else
                <div class="row">
                    <div class="col-lg-12">
                        <h4 class="mb-sm-0 text-center font-size-18">لا يــوجــد حــركــات</h4>
                    </div>
                </div>
                @endif
            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        @endsection

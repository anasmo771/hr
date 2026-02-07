<div class="card pc-user-card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
                <img src="{{ asset('assets/New/images/avatar-1.png') }}" alt="user-image"
                    class="user-avtar wid-45 rounded-circle" />
            </div>
            <div class="flex-grow-1 ms-3 me-2">
                <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                <small></small>
            </div>
            <a class="btn btn-icon btn-link-secondary avtar-s" data-bs-toggle="collapse" href="#pc_sidebar_userlink">
                <svg class="pc-icon">
                    <use xlink:href="#custom-sort-outline"></use>
                </svg>
            </a>
        </div>
        <div class="collapse pc-user-links" id="pc_sidebar_userlink">
            <div class="pt-3">

                <a href="#"
                    onclick="event.preventDefault();
          document.getElementById('logout-form').submit();">
                    <i class="ti ti-logout"></i>
                    <span>تسجيل الخروج</span>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>


            </div>
        </div>
    </div>
</div>

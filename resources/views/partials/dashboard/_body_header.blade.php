<nav class="nav navbar navbar-expand-lg navbar-light iq-navbar">
  <div class="container-fluid navbar-inner">
    <a href="{{route('dashboard')}}" class="navbar-brand">
      <img src="{{asset('e-mercado-logo.png')}}" style="width: 45px; height: 35px;" alt="">
      <h6 class="logo-title">{{env('APP_NAME')}}</h6>
    </a>
    <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
      <i class="icon">
        <svg width="20px" height="20px" viewBox="0 0 24 24">
          <path fill="currentColor" d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z" />
      </svg>
      </i>
    </div>
    <div class="input-group search-input">
      <span class="input-group-text" id="search-input">
        <svg width="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="11.7669" cy="11.7666" r="8.98856" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></circle>
          <path d="M18.0186 18.4851L21.5426 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
      </span>
      <input type="search" class="form-control" placeholder="Search...">
    </div>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
      <span class="navbar-toggler-icon">
        <span class="navbar-toggler-bar bar1 mt-2"></span>
        <span class="navbar-toggler-bar bar2"></span>
        <span class="navbar-toggler-bar bar3"></span>
      </span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto  navbar-list mb-2 mb-lg-0">
        
        <li class="nav-item dropdown">
          @if(Auth::user()->user_type == 'provincial')
            <a class="nav-link py-0 d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="{{asset('storage/images/admin/logo/southernleyte.png')}}" alt="User-Profile" class="theme-color-default-img img-fluid avatar avatar-50 avatar-rounded">
              <div class="caption ms-3 d-none d-md-block ">
                @foreach (Session::get('admin') as $admin)
                   <h6 class="mb-0 caption-title">{{ ucwords($admin->province) }}</h6>
                @endforeach
                <p class="mb-0 caption-sub-title text-capitalize">Province</p>
              </div>
            </a>
          @endif
          @if(Auth::user()->user_type == 'municipal')
            <a class="nav-link py-0 d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="{{asset('storage/images/admin/logo/southernleyte.png')}}" alt="User-Profile" class="theme-color-default-img img-fluid avatar avatar-50 avatar-rounded">
              <div class="caption ms-3 d-none d-md-block ">
                @foreach (Session::get('admin') as $admin)
                   <h6 class="mb-0 caption-title">{{ ucwords($admin->municipal) }}</h6>
                @endforeach
                <p class="mb-0 caption-sub-title text-capitalize">Municipal</p>
              </div>
            </a>
          @endif
          
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="{{route('users.show', auth()->id() || 1 )}}">Profile</a></li>
            <li><a class="dropdown-item" href="{{route('auth.userprivacysetting')}}">Privacy Setting</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><form method="POST" action="{{route('logout')}}">
              @csrf
              <a href="javascript:void(0)" class="dropdown-item"
                onclick="event.preventDefault();
              this.closest('form').submit();">
                  {{ __('Log out') }}
              </a>
              </form>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>



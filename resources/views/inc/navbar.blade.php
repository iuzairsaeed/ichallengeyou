<nav class="navbar navbar-expand-lg navbar-light bg-faded">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" data-toggle="collapse" class="navbar-toggle d-lg-none float-left">
        <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
      </button>
      <span class="d-lg-none navbar-right navbar-collapse-toggle"><a class="open-navbar-container"><i class="ft-more-vertical"></i></a></span>
    </div>
    @php $user = auth()->user(); @endphp
    <div class="navbar-container">
      <div id="navbarSupportedContent" class="collapse navbar-collapse">
        <ul class="navbar-nav">
          <li class="dropdown nav-item mt-1"><a id="dropdownBasic2" href="#" data-toggle="dropdown" class="nav-link position-relative dropdown-toggle"><i class="ft-bell blue-grey darken-4">
            </i><span class="notification badge badge-pill badge-danger">{{ $notifications->count() }}</span>
            <p class="d-none">Notifications</p></a>
            <div class="notification-dropdown dropdown-menu dropdown-menu-right" style="width: 500px; height:500px">
              <div class="arrow_box_right" >
                <div class="noti-list" style="width: 500px; height:450px" >
                  @foreach ($notifications ?? '' as $item)
                    @if ($item->click_action == 'SUBMITED_CHALLENGE_LIST_SCREEN' || $item->click_action == 'CHALLENGE_DETAIL_SCREEN' || $item->click_action == 'SUBMITED_CHALLENGE_DETAIL_SCREEN') 
                      <a href="/challenges/{{$item->data_id}}" class="dropdown-item noti-container py-2">
                    @elseif ($item->click_action == 'TRANSACTION_LIST' ) 
                      <a href="/users/{{$item->data_id}}" class="dropdown-item noti-container py-2">
                    @else
                      <a class="dropdown-item noti-container py-2">
                    @endif
                      <div class="row noti-wrapper">
                        <span class="noti-wrapper">
                          <i class="ft-check-circle info float-left d-block font-medium-4 mt-2 mr-2"></i>
                          <span class="noti-title line-height-1 d-block text-bold-400 info">{{$item->title}}</span>
                          <p class="noti-text danger">{{$item->body}}</p>
                        </span>
                      </div>
                      <span class="noti-text d-block text-bold-400 info" style="float: right">{{$item->created_at}}</span>
                    </a>
                  @endforeach
                </div>
              </div>
              <a href="/notifications" class="noti-footer primary text-center d-block border-top border-top-blue-grey border-top-lighten-1 text-bold-400 py-1">See All</a>
            </div>
          </li>
          <li class="dropdown nav-item mr-0">
            <a id="dropdownBasic3" href="#" data-toggle="dropdown" class="nav-link position-relative dropdown-user-link dropdown-toggle">
              <span class="avatar avatar-online">
                <img id="navbar-avatar" src="{{ asset($user->avatar) }}" alt="avatar"/>
              </span>
            </a>
            <div aria-labelledby="dropdownBasic3" class="dropdown-menu dropdown-menu-right">
              <div class="arrow_box_right">
                <a href="{{ route('profile') }}" class="dropdown-item py-1"><i class="icon-user mr-2"></i><span>{{$user->name}}</span></a>
                <a href="{{ route('changePassword') }}" class="dropdown-item py-1"><i class="ft-lock mr-2"></i><span>Change Password</span></a>
                <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="ft-power mr-2"></i><span>Logout</span></a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
                </form>
              </div>
            </div>
          </li>

        </ul>
      </div>
    </div>
  </div>
</nav>

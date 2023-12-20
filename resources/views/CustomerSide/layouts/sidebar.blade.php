 <!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-light-primary bg-warning elevation-4">
    <!-- Brand Logo -->

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel text-center  mt-3 ">
        <div class="info">
          <h4 class="d-block ">SKI COMPANY</h4>
          <p>
           Welcome
            {{Auth::user()->name}}
          </p>
        </div>
      </div>


      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

               <li class="nav-item">
                <a href="{{route('customer-dashboard')}}" class="nav-link">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                      Dashboard
                    </p>
                  </a>
              </li>
          <li class="nav-item">
            <a href="{{route('customer-trip')}}" class="nav-link">
              <i class="nav-icon fas fa-plane "></i>
              <p>
                Trips
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('customer-membership')}}" class="nav-link">
              <i class="nav-icon far fa-address-card"></i>
              <p>
                Memberships
              </p>
            </a>
          </li>
          <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}">
             @csrf
           <a  href="{{ route('logout') }}"
             onclick="event.preventDefault();
             this.closest('form').submit();" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>
                Log out
            </p>
           </a>
          </form>

        </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

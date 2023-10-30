<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary">
  <!-- Brand Logo -->
  <a class="brand-link">
    <img src="{{ asset('images/logobar1.png') }}" class="brand-image" >
    <span class="brand-text font-weight-light">PORTAL</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{asset('images/iconlion.png')}}" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a class="d-block">{{ session('username') }}</a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        @if(session('departmentid')  == 'DD001')
        <li class="nav-item">
          <a href="{{ url('/') }}" class="nav-link" type="submit" id="m-dashboard"><i class="nav-icon fas fa-home"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>
        @endif
        @if(session('roleid')  == 'RD001' || session('roleid')  == 'RD006' || session('roleid')  == 'RD004')
        <li class="nav-item">
          <a href="#" class="nav-link" >
            <i class="nav-icon fas fa fa-database"></i>
            <p>
              Master
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ url('user') }}" class="nav-link" type="submit" id="m-user"><i class="far fa-circle nav-icon"></i>
                <p>
                  Master User
                </p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ url('counter') }}" class="nav-link" type="submit" id="m-counter"><i class="far fa-circle nav-icon"></i>
                <p>
                  Master Counter
                </p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ url('category') }}" class="nav-link" type="submit" id="m-category"><i class="far fa-circle nav-icon"></i>
                <p>
                  Master Category
                </p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ url('department') }}" class="nav-link" type="submit" id="m-department"><i class="far fa-circle nav-icon"></i>
                <p>
                  Master Department
                </p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ url('role') }}" class="nav-link" type="submit" id="m-role"><i class="far fa-circle nav-icon"></i>
                <p>
                  Master Role
                </p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ url('plant') }}" class="nav-link" type="submit" id="m-plant"><i class="far fa-circle nav-icon"></i>
                <p>
                  Master Plant
                </p>
              </a>
            </li>
          </ul>
        </li>
        @endif
        @if(session('roleid')  != 'RD011')
        <li class="nav-item">
          <a href="{{ url('absensi') }}" class="nav-link" type="submit" id="m-Attendance"><i class="nav-icon fas fa-calendar-check"></i>
            <p>
              Attendance
            </p>
          </a>
        </li>
        @endif
        @if(session('roleid')  == 'RD003' || session('roleid')  == 'RD004' || session('roleid')  == 'RD005' || session('roleid')  == 'RD006' || session('roleid')  == 'RD007' || session('roleid')  == 'RD008' || session('roleid')  == 'RD009' || session('roleid')  == 'RD002' || session('roleid')  == 'RD001')
        <li class="nav-item">
          <a href="#" class="nav-link" >
            <i class="nav-icon fas fa-ticket-alt"></i>
            <p>
              Ticketing
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ url('tiket') }}" class="nav-link" type="submit" id="m-tiket"><i class="far fa-circle nav-icon"></i>
                <p>
                  Ticket All
                </p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ url('mytiket') }}" class="nav-link" type="submit" id="m-mytiket"><i class="far fa-circle nav-icon"></i>
                <p>
                  My Ticket
                </p>
              </a>
            </li>
          </ul>
        </li>
        @endif
        @if(session('roleid')  == 'RD006' || session('departmentid')  == 'DD005' || session('roleid')  == 'RD001')
        <li class="nav-item">
          <a href="#" class="nav-link" >
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              HRIS
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          @if(session('departmentid')  != 'DD005')
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ url('absensipayroll') }}" class="nav-link" type="submit" id="m-absensipayroll"><i class="far fa-circle nav-icon"></i>
                <p>
                  Attendance Payroll
                </p>
              </a>
            </li>
          </ul>
          @endif
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ url('kwitansi') }}" class="nav-link" type="submit" id="m-kwitansi"><i class="far fa-circle nav-icon"></i>
                <p>
                  Print Kwitansi
                </p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ url('kwitansi/cuti') }}" class="nav-link" type="submit" id="m-kwitansicuti"><i class="far fa-circle nav-icon"></i>
                <p>
                  Print Kwitansi Cuti
                </p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ url('karyawan') }}" class="nav-link" type="submit" id="m-karyawan"><i class="far fa-circle nav-icon"></i>
                <p>
                  List Karyawan
                </p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ url('history') }}" class="nav-link" type="submit" id="m-history"><i class="far fa-circle nav-icon"></i>
                <p>
                  History kwitansi
                </p>
              </a>
            </li>
          </ul>
          @endif
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link" >
            <i class="nav-icon fas fa-door-open"></i>
            <p>
              Room Meeting
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          @if(session('roleid')  == 'RD001' || session('roleid')  == 'RD011' || session('roleid')  == 'RD012' || session('roleid')  == 'RD006')
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ url('/admin/index') }}" class="nav-link" type="submit" id="m-adminroom"><i class="far fa-circle nav-icon"></i>
                <p>
                  Admin
                </p>
              </a>
            </li>
          </ul>
          @endif
          @if(session('roleid')  == 'RD003' || session('roleid')  == 'RD004' || session('roleid')  == 'RD005' || session('roleid')  == 'RD006' || session('roleid')  == 'RD007' || session('roleid')  == 'RD008' || session('roleid')  == 'RD009' || session('roleid')  == 'RD002' || session('roleid')  == 'RD001')
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ url('/user/index') }}" class="nav-link" type="submit" id="m-userroom"><i class="far fa-circle nav-icon"></i>
                <p>
                  Booking Room
                </p>
              </a>
            </li>
          </ul>
          @endif
          @if(session('roleid')  == 'RD001' || session('roleid')  == 'RD011' || session('roleid')  == 'RD012' ||  session('roleid')  == 'RD006')
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ url('/view') }}" class="nav-link" target="_blank" type="submit" id="m-viewroom"><i class="far fa-circle nav-icon"></i>
                <p>
                  View Room
                </p>
              </a>
            </li>
          </ul>
        </li>
        @endif
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>

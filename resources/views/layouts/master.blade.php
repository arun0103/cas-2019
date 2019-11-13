<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <link rel="shortcut icon" href="{{ asset('img/AdminLTELogo.png') }}">
  
  <title>| CAS | </title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous"> -->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{asset('js/plugins/font-awesome/css/font-awesome.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('css/adminlte.min.css')}}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

  <!-- <link rel="stylesheet" href="{{asset('js/plugins/bootstrap/css/bootstrap.min.css')}}"> -->
  <!-- <link rel="stylesheet" href="{{asset('js/plugins/bootstrap/css/bootstrap-grid.min.css')}}"> -->
  <!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/> -->
  
  <link rel="stylesheet" href="{{asset('js/plugins/datepicker/datepicker3.css')}}">

  <!-- Custom CSS Files -->
  <link rel="stylesheet" href="{{asset('css/myStyle.css')}}">
  @yield('head')
</head>
<body class="hold-transition sidebar-mini">
  <div class="wrapper" style="overflow-y:hidden">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="{{route('main')}}" class="nav-link">Home</a>
        </li>
        <!-- <li class="nav-item d-none d-sm-inline-block">
          <a href="{{route('reportSelect')}}" class="nav-link">Reports</a>
        </li> -->
        <li class="nav-item d-none d-sm-inline-block">
          <a href="#" class="nav-link">Contact</a>
        </li>
      </ul>

      <!-- SEARCH FORM -->
      <!-- <form class="form-inline ml-3">
        <div class="input-group input-group-sm">
          <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-navbar" type="submit">
              <i class="fa fa-search"></i>
            </button>
          </div>
        </div>
      </form> -->

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto fixed">
        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="dropdown" style="margin-right:20px;">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="link_reports">Reports <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li>
                    <div class="dropdown-submenu">
                      <a href="{{route('reportSelect')}}" >Employee</a>
                      <!-- <div class="dropdown-content"><a href="#">Absent</a> </div> -->
                    </div>
                </li>
                <li class="divider"></li>
                <li><a href="{{route('reportSelectStudent')}}">Student</a></li>
              </ul>
            </li>
            <li style="margin-right:20px;"><a href="#">Contact Us<span class="sr-only">(current)</span></a></li>
            <li style="margin-right:20px;"><a href="#"><i class="fa fa-info-circle"></i> Help</a></li>
          </ul>
        </div>
          <!-- Messages Dropdown Menu -->
            <!-- <li class="nav-item dropdown">
              <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fa fa-comments-o"></i>
                <span class="badge badge-danger navbar-badge">3</span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="#" class="dropdown-item"> -->
                  <!-- Message Start -->
                  <!-- <div class="media">
                    <img src="dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                    <div class="media-body">
                      <h3 class="dropdown-item-title">
                        Brad Diesel
                        <span class="float-right text-sm text-danger"><i class="fa fa-star"></i></span>
                      </h3>
                      <p class="text-sm">Call me whenever you can...</p>
                      <p class="text-sm text-muted"><i class="fa fa-clock-o mr-1"></i> 4 Hours Ago</p>
                    </div>
                  </div> -->
                  <!-- Message End -->
                <!-- </a>
                <div class="dropdown-divider"></div> -->
                <!-- <a href="#" class="dropdown-item"> -->
                  <!-- Message Start -->
                  <!-- <div class="media">
                    <img src="dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                    <div class="media-body">
                      <h3 class="dropdown-item-title">
                        John Pierce
                        <span class="float-right text-sm text-muted"><i class="fa fa-star"></i></span>
                      </h3>
                      <p class="text-sm">I got your message bro</p>
                      <p class="text-sm text-muted"><i class="fa fa-clock-o mr-1"></i> 4 Hours Ago</p>
                    </div>
                  </div> -->
                  <!-- Message End -->
                <!--</a> -->
                <!-- <div class="dropdown-divider"></div> -->
                <!-- <a href="#" class="dropdown-item"> -->
                  <!-- Message Start -->
                  <!-- <div class="media">
                    <img src="dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                    <div class="media-body">
                      <h3 class="dropdown-item-title">
                        Nora Silvester
                        <span class="float-right text-sm text-warning"><i class="fa fa-star"></i></span>
                      </h3>
                      <p class="text-sm">The subject goes here</p>
                      <p class="text-sm text-muted"><i class="fa fa-clock-o mr-1"></i> 4 Hours Ago</p>
                    </div>
                  </div> -->
                  <!-- Message End -->
                <!--</a> -->
                <!-- <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
              </div> 
            </li> -->
            <!-- Notifications Dropdown Menu -->
            <!-- <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
              <i class="fa fa-bell-o"></i>
              <span class="badge badge-warning navbar-badge">15</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
              <span class="dropdown-item dropdown-header">15 Notifications</span>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item">
                <i class="fa fa-envelope mr-2"></i> 4 new messages
                <span class="float-right text-muted text-sm">3 mins</span>
              </a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item">
                <i class="fa fa-users mr-2"></i> 8 friend requests
                <span class="float-right text-muted text-sm">12 hours</span>
              </a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item">
                <i class="fa fa-file mr-2"></i> 3 new reports
                <span class="float-right text-muted text-sm">2 days</span>
              </a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
          </li> -->
        <li>
        @if(Session::get('user_id'))
          <a href="{{ route('logout') }}">
            <i class="fa fa-sign-out icon-4x" id="btn_logout" style="font-size:20px;" aria-hidden="true" data-toggle="tooltip" title="Log Out"></i>
          </a>
        @endif
          <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="#" class="brand-link">
        <img src="{{asset('img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">Core Attendance Sys</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="{{asset('img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="#" class="d-block">{{Session::get('user_name')}}</a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <div id="scrollArea" style="min-height:100% !important">
          <nav class="mt-2" >
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
              <!-- Add icons to the links using the .nav-icon class
                  with font-awesome or any other icon font library -->
              <li class="nav-item ">
                <a href="{{ route('dashboard') }}" class="nav-link ">
                  <i class="nav-icon fa fa-dashboard"></i>
                  <p>
                    Dashboard
                  </p>
                </a>
              </li>
              @if(session()->get('role')=='admin' && session()->get('company_type')=='business')
                <li class="nav-item ">
                  <a href="{{ route('branches') }}" class="nav-link " onclick="checkClicked()">
                    <i class="nav-icon fa fa-pagelines"></i>
                    <p>
                      Branches
                    </p>
                  </a>
                </li>
                <li class="nav-item ">
                  <a href="{{ route('leaveMasterData') }}" class="nav-link ">
                    <i class="nav-icon fa fa-pagelines"></i>
                    <p>
                      Leave Master
                    </p>
                  </a>
                </li>
                <li class="nav-item ">
                  <a href="{{ route('leaveTypes') }}" class="nav-link ">
                    <i class="nav-icon fa fa-pagelines"></i>
                    <p>
                      Leave Types
                    </p>
                  </a>
                </li>
                <li class="nav-item ">
                  <a href="{{ route('departments') }}" class="nav-link ">
                    <i class="nav-icon fa fa-home"></i>
                    <p>
                      Departments
                    </p>
                  </a>
                </li>
                <li class="nav-item ">
                  <a href="{{ route('categories') }}" class="nav-link ">
                    <i class="nav-icon fa fa-home"></i>
                    <p>
                      Categories
                    </p>
                  </a>
                </li>
                <li class="nav-item ">
                  <a href="{{ route('designations') }}" class="nav-link ">
                    <i class="nav-icon fa fa-home"></i>
                    <p>
                    Designations
                    </p>
                  </a>
                </li>
                <li class="nav-item ">
                  <a href="{{ route('shifts') }}" class="nav-link ">
                    <i class="nav-icon fa fa-home"></i>
                    <p>
                    Shifts
                    </p>
                  </a>
                </li>
                <li class="nav-item ">
                  <a href="{{ route('employees') }}" class="nav-link ">
                    <i class="nav-icon fa fa-home"></i>
                    <p>
                    Employees
                    </p>
                  </a>
                </li>
                <li class="nav-item ">
                  <a href="{{ route('leaveQuotas') }}" class="nav-link ">
                    <i class="nav-icon fa fa-home"></i>
                    <p>
                    Leave Quota
                    </p>
                  </a>
                </li>
                <li class="nav-item ">
                  <a href="{{ route('holidays') }}" class="nav-link ">
                    <i class="nav-icon fa fa-home"></i>
                    <p>
                    Holidays
                    </p>
                  </a>
                </li>
                <li class="nav-item ">
                  <a href="{{ route('rosters') }}" class="nav-link ">
                    <i class="nav-icon fa fa-home"></i>
                    <p>
                    Roster
                    </p>
                  </a>
                </li>
                <li class="nav-item ">
                  <a href="{{ route('manualPunch') }}" class="nav-link ">
                    <i class="nav-icon fa fa-home"></i>
                    <p>
                    Manual Punch
                    </p>
                  </a>
                </li>
                <li class="nav-item ">
                  <a href="{{ route('leaveRequests') }}" class="nav-link ">
                    <i class="nav-icon fa fa-home"></i>
                    <p>
                    Leave Requests
                    </p>
                  </a>
                </li>
                <hr>
                <li class="nav-item ">
                  <a href="{{ route('upload') }}" class="nav-link ">
                    <i class="nav-icon fa fa-upload"></i>
                    <p>
                    Upload Raw File
                    </p>
                  </a>
                </li>
                <!--  For employees -->
                <li class="nav-item ">
                  <a href="{{ route('employeeDashboard') }}" class="nav-link ">
                    <i class="nav-icon fa fa-home"></i>
                    <p>
                    Employee Dashboard
                    </p>
                  </a>
                </li>

              @endif
              @if(session()->get('role')=='admin' && session()->get('company_type')=='institute')
                <li class="nav-item has-treeview">
                  <a href="#" class="nav-link">
                    <i class="nav-icon fa fa-user"></i>
                    <p>
                      Employee Pane
                      <i class="right fa fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item ">
                      <a href="{{ route('branches') }}" class="nav-link " onclick="checkClicked()">
                        <i class="nav-icon fa fa-pagelines"></i>
                        <p>
                          Branches
                        </p>
                      </a>
                    </li>
                    <li class="nav-item ">
                      <a href="{{ route('leaveMasterData') }}" class="nav-link ">
                        <i class="nav-icon fa fa-pagelines"></i>
                        <p>
                          Leave Master
                        </p>
                      </a>
                    </li>
                    <li class="nav-item ">
                      <a href="{{ route('leaveTypes') }}" class="nav-link ">
                        <i class="nav-icon fa fa-pagelines"></i>
                        <p>
                          Leave Types
                        </p>
                      </a>
                    </li>
                    <li class="nav-item ">
                      <a href="{{ route('departments') }}" class="nav-link ">
                        <i class="nav-icon fa fa-home"></i>
                        <p>
                          Departments
                        </p>
                      </a>
                    </li>
                    <li class="nav-item ">
                      <a href="{{ route('categories') }}" class="nav-link ">
                        <i class="nav-icon fa fa-home"></i>
                        <p>
                          Categories
                        </p>
                      </a>
                    </li>
                    <li class="nav-item ">
                      <a href="{{ route('designations') }}" class="nav-link ">
                        <i class="nav-icon fa fa-home"></i>
                        <p>
                        Designations
                        </p>
                      </a>
                    </li>
                    <li class="nav-item ">
                      <a href="{{ route('shifts') }}" class="nav-link ">
                        <i class="nav-icon fa fa-home"></i>
                        <p>
                        Shifts
                        </p>
                      </a>
                    </li>
                    <li class="nav-item ">
                      <a href="{{ route('employees') }}" class="nav-link ">
                        <i class="nav-icon fa fa-home"></i>
                        <p>
                        Employees
                        </p>
                      </a>
                    </li>
                    <li class="nav-item ">
                      <a href="{{ route('leaveQuotas') }}" class="nav-link ">
                        <i class="nav-icon fa fa-home"></i>
                        <p>
                        Leave Quota
                        </p>
                      </a>
                    </li>
                    <li class="nav-item ">
                      <a href="{{ route('holidays') }}" class="nav-link ">
                        <i class="nav-icon fa fa-home"></i>
                        <p>
                        Holidays
                        </p>
                      </a>
                    </li>
                    <li class="nav-item ">
                      <a href="{{ route('rosters') }}" class="nav-link ">
                        <i class="nav-icon fa fa-home"></i>
                        <p>
                        Roster
                        </p>
                      </a>
                    </li>
                    <li class="nav-item ">
                      <a href="{{ route('manualPunch') }}" class="nav-link ">
                        <i class="nav-icon fa fa-home"></i>
                        <p>
                        Manual Punch
                        </p>
                      </a>
                    </li>
                    <li class="nav-item ">
                      <a href="{{ route('leaveRequests') }}" class="nav-link ">
                        <i class="nav-icon fa fa-home"></i>
                        <p>
                        Leave Requests
                        </p>
                      </a>
                    </li>
                    <hr>
                    <li class="nav-item ">
                      <a href="{{ route('upload') }}" class="nav-link ">
                        <i class="nav-icon fa fa-upload"></i>
                        <p>
                        Upload Raw File
                        </p>
                      </a>
                    </li>
                    <!--  For employees -->
                    <li class="nav-item ">
                      <a href="{{ route('employeeDashboard') }}" class="nav-link ">
                        <i class="nav-icon fa fa-home"></i>
                        <p>
                        Employee Dashboard
                        </p>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-item has-treeview">
                  <a href="#" class="nav-link">
                    <i class="nav-icon fa fa-user"></i>
                    <p>
                      Student Pane
                      <i class="right fa fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item ">
                      <a href="{{ route('viewInstitutionShifts') }}" class="nav-link ">
                        <i class="nav-icon fa fa-pagelines"></i>
                        <p>
                          Shifts
                        </p>
                      </a>
                    </li>
                  </ul>
                  <ul class="nav nav-treeview">
                    <li class="nav-item ">
                      <a href="{{ route('viewGrades') }}" class="nav-link ">
                        <i class="nav-icon fa fa-pagelines"></i>
                        <p>
                          Grades
                        </p>
                      </a>
                    </li>
                  </ul>
                  <ul class="nav nav-treeview">
                    <li class="nav-item ">
                      <a href="{{ route('viewSections') }}" class="nav-link ">
                        <i class="nav-icon fa fa-pagelines"></i>
                        <p>
                          Sections
                        </p>
                      </a>
                    </li>
                  </ul>
                  <ul class="nav nav-treeview">
                    <li class="nav-item ">
                      <a href="{{ route('viewStudents') }}" class="nav-link ">
                        <i class="nav-icon fa fa-pagelines"></i>
                        <p>
                          Students
                        </p>
                      </a>
                    </li>
                  </ul>
                  <ul class="nav nav-treeview">
                    <li class="nav-item ">
                      <a href="{{ route('viewStudentRoster',\Carbon\Carbon::now()->month) }}" class="nav-link ">
                        <i class="nav-icon fa fa-pagelines"></i>
                        <p>
                          Rosters
                        </p>
                      </a>
                    </li>
                  </ul>

                </li>
              @endif
              @if(session()->get('role')=='super')
                <li class="nav-item has-treeview">
                  <a href="#" class="nav-link">
                    <i class="nav-icon fa fa-user"></i>
                    <p>
                      Companies
                      <i class="right fa fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="{{ route('companies') }}" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p>List View</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="{{ route('addCompany') }}" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p>Add New</p>
                      </a>
                    </li>
                  </ul>
                </li>
              @endif

            </ul>
          </nav>
        </div>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper roundPadding20">
      @yield('content')
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
      

    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer">
      <!-- To the right -->
      <div class="float-right d-sm-none d-md-block">
        Anything you want to automate
      </div>
      <!-- Default to the left -->
      <strong>Copyright &copy; 2014-2018 <a href="https://coretimesolution.com">Core Time Solutions</a>.</strong> All rights reserved.
    </footer>
  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->
  <!-- jQuery -->
  <script src="{{asset('js/plugins/jquery/jquery-3.3.1.js')}}"></script>
  <script src="{{asset('js/plugins/fullcalendar/lib/jquery-ui.min.js')}}"></script>
  <!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
  <!-- <script src="{{asset('js/plugins/bootstrap/js/bootstrap.js')}}"></script> -->
  <!-- <script src="{{asset('js/plugins/fastclick/fastclick.js')}}"></script> -->
  @yield('footer')
  <!-- Bootstrap -->
  <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
  <script src="{{asset('js/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
  <script src="{{asset('js/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <!-- AdminLTE App -->
  <script src="{{asset('js/adminlte.js')}}"></script>

  <!-- OPTIONAL SCRIPTS -->
  <script src="{{asset('js/demo.js')}}"></script>

  <!-- PAGE PLUGINS -->
  <!-- SparkLine -->
  <script src="{{asset('js/plugins/sparkline/jquery.sparkline.min.js')}}"></script>
  <!-- jVectorMap -->
  <script src="{{asset('js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
  <script src="{{asset('js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
  <!-- SlimScroll 1.3.0 -->
  <script src="{{asset('js/plugins/slimScroll/jquery.slimscroll.js')}}"></script>
  <!-- ChartJS 1.0.2 -->
  <script src="{{asset('js/plugins/chartjs-old/Chart.min.js')}}"></script>


  <!-- PAGE SCRIPTS -->
  <!-- <script src="{{asset('js/pages/dashboard2.js')}}"></script> -->
  <script src="{{asset('js/demo.js')}}"></script>
  <script>
    $('#scrollArea').slimscroll({
      position: 'left',
      color: '#00f',
      
    });
    $('#btn_logout').click(function(){

    });

    $('#link_reports').click(function(){
      // alert('Clicked');
      // $('.dropdown-submenu').display('true');
    });
    // jQuery.event.special.touchstart = {
      //   setup: function( _, ns, handle ){
      //     if ( ns.includes("noPreventDefault") ) {
      //       this.addEventListener("touchstart", handle, { passive: false });
      //     } else {
      //       this.addEventListener("touchstart", handle, { passive: true });
      //     }
      //   }
      // };
      // jQuery.event.special.touchmove = {
      //   setup: function( _, ns, handle ){
      //     if ( ns.includes("noPreventDefault") ) {
      //       this.addEventListener("touchmove", handle, { passive: false });
      //     } else {
      //       this.addEventListener("touchmove", handle, { passive: true });
      //     }
      //   }
      // };
      // jQuery.event.special.mousewheel = {
      //   setup: function( _, ns, handle ){
      //     if ( ns.includes("noPreventDefault") ) {
      //       this.addEventListener("mousewheel", handle, { passive: false });
      //     } else {
      //       this.addEventListener("mousewheel", handle, { passive: true });
      //     }
      //   }
    // };
    function checkClicked(){
      //alert("clicked");
    }
  


  </script>
</body>
</html>

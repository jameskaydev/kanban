<!DOCTYPE html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ url('auth') }}/assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>KayTask - {{ @$pageName }}</title>

    <meta name="description" content="" />

    <!-- Favicon -->

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ url('auth') }}/assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="{{ url('auth') }}/assets/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="{{ url('auth') }}/assets/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ url('auth') }}/assets/vendor/css/rtl/core.css"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ url('auth') }}/assets/vendor/css/rtl/theme-default.css"
        class="template-customizer-theme-css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ url('auth') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="{{ url('auth') }}/assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="{{ url('auth') }}/assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="{{ url('auth') }}/assets/vendor/libs/apex-charts/apex-charts.css" />
    <link rel="stylesheet" href="{{ url('auth') }}/assets/vendor/libs/swiper/swiper.css" />
    <link rel="stylesheet" href="{{ url('auth') }}/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ url('auth') }}/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ url('auth') }}/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
    <link rel="stylesheet" href="{{ url('auth') }}/assets/vendor/libs/spinkit/spinkit.css" />


    {{-- my styles  --}}
    <link href="{{ url('auth') }}/assets/css/persian-datepicker.min.css" rel="stylesheet">
    {{-- end my styles  --}}
    <link rel="stylesheet" href="{{ url('auth') }}/assets/css/demo.css" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ url('auth') }}/assets/vendor/css/pages/cards-advance.css" />

    <!-- Helpers -->
    <script src="{{ url('auth') }}/assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="{{ url('auth') }}/assets/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ url('auth') }}/assets/js/config.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    @yield('head')
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
        <div class="layout-container">
            <!-- Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="ti ti-menu-2 ti-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">


                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- Language -->

                            <li class="nav-item dropdown-language dropdown me-2 me-xl-0">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <i
                                        class="fi fi-<?= @$_COOKIE['locale'] != 'fa' ? 'us' : 'ir' ?> fis rounded-circle me-1 fs-3"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" onclick="change_language('false')"
                                            href="{{ url('Admin/Lang/Change/en') }}">
                                            <i class="fi fi-us fis rounded-circle me-1 fs-3"></i>
                                            <span class="align-middle">@lang('admin.English')</span>
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item" onclick="change_language('true')"
                                            href="{{ url('Admin/Lang/Change/fa') }}">
                                            <i class="fi fi-ir fis rounded-circle me-1 fs-3"></i>
                                            <span class="align-middle">@lang('admin.persian')</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!--/ Language -->

                            <!-- Style Switcher -->
                            <li class="nav-item me-2 me-xl-0">
                                <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);">
                                    <i class="ti ti-md"></i>
                                </a>
                            </li>
                            <!--/ Style Switcher -->

                            <!-- Notification -->
                            <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                    <i class="ti ti-bell ti-md"></i>
                                    @if ($GL_Notifications_count > 0)
                                        <span
                                            class="badge bg-danger rounded-pill badge-notifications">{{ $GL_Notifications_count }}</span>
                                    @endif
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end py-0">
                                    <li class="dropdown-menu-header border-bottom">
                                        <div class="dropdown-header d-flex align-items-center py-3">
                                            <h5 class="text-body mb-0 me-auto">@lang('admin.Notifications')</h5>
                                            <a href="{{ url('Admin/Notifications/Status/ALL/read') }}" class="dropdown-notifications-all text-body"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Mark all as read"><i class="ti ti-mail-opened fs-4"></i></a>
                                        </div>
                                    </li>
                                    <li class="dropdown-notifications-list scrollable-container" >
                                        <ul class="list-group list-group-flush">
                                            @foreach ($GL_Notifications as $GLN)
                                            <li
                                            class="list-group-item list-group-item-action dropdown-notifications-item @if($GLN['is_read']) marked-as-read @endif"
                                            onclick="window.location.href = '{{ $GLN['link'] }}'">
                                            <div  class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar">
                                                        <i class="{{ $GLN['icon'] }}"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">
                                                        {{ $GLN['title'] }}
                                                    </h6>
                                                    <p>
                                                    {!! $GLN['message'] !!}
                                                    </p>
                                                    <small class="text-muted">{{ $GL_Jalalian->fromDateTime(strtotime($GLN['created_at']))->format('Y/m/d H:i') }}</small>
                                                </div>
                                                <div class="flex-shrink-0 dropdown-notifications-actions">
                                                    <a href="{{ url('Admin/Notifications/Status/' . $GLN['id'] . '/' . (($GLN['is_read']) ? 'unread' : 'read')) }}"
                                                        class="dropdown-notifications-read"><span
                                                            class="badge badge-dot"></span></a>
                                                </div>
                                            </div>
                                        </li>
                                        @endforeach






                                        </ul>
                                    </li>
                                    <li class="dropdown-menu-footer border-top">
                                        <a href="{{ url('Admin/UsersNotif') }}"
                                            class="dropdown-item d-flex justify-content-center text-primary p-2 h-px-40 mb-1 align-items-center">
                                            View all notifications
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!--/ Notification -->

                               <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                      <div class="avatar avatar-online">
                        <div class="avatar me-2 flex-shrink-0">
                            @if(auth()->user()->avatar != '' && file_exists(public_path().'/uploads/'.auth()->user()->avatar))
                                <img src="{{ url('uploads/' . auth()->user()->avatar) }}" alt="Avatar"
                                class="rounded-circle mt-1" />
    
                            @else
                                <span
                                class="avatar-initial bg-label-{{ $GL_PROFILE_COLOR[ord(substr(auth()->user()->fullname,0,1))] }} rounded-circle mt-1">{{ substr(auth()->user()->fullname,0,2) }}</span>
                            @endif
                        </div>
                      </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                      <li>
                        <a class="dropdown-item" href="{{ url("Admin/Profile") }}">
                          <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                              <div class="avatar avatar-online">
                                <div class="avatar avatar-online">
                                    <div class="avatar me-2 flex-shrink-0">
                                        @if(auth()->user()->avatar != '' && file_exists(public_path().'/uploads/'.auth()->user()->avatar))
                                            <img src="{{ url('uploads/' . auth()->user()->avatar) }}" alt="Avatar"
                                            class="rounded-circle mt-1" />
                
                                        @else
                                            <span
                                            class="avatar-initial bg-label-{{ $GL_PROFILE_COLOR[ord(substr(auth()->user()->fullname,0,1))] }} rounded-circle mt-1">{{ substr(auth()->user()->fullname,0,2) }}</span>
                                        @endif
                                    </div>
                                  </div>                              </div>
                            </div>
                            <div class="flex-grow-1">
                              <span class="fw-semibold d-block">{{ Auth::user()->fullname }}</span>
                              <small class="text-muted">{{ Auth::user()->role }}</small>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <div class="dropdown-divider"></div>
                      </li>
                      <li>
                        <a class="dropdown-item" href="{{ url("Admin/Profile") }}">
                          <i class="ti ti-user-check me-2 ti-sm"></i>
                          <span class="align-middle">My Profile</span>
                        </a>
                      </li>

                    

                      <li>
                        <div class="dropdown-divider"></div>
                      </li>
                      <li>
                        <a class="dropdown-item" href="{{ url("logout") }}">
                          <i class="ti ti-logout me-2 ti-sm"></i>
                          <span class="align-middle">Log Out</span>
                        </a>
                      </li>
                    </ul>
                  </li>
                  <!--/ User -->

                        </ul>
                    </div>

                    <!-- Search Small Screens -->
                    <div class="navbar-search-wrapper search-input-wrapper d-none">
                        <input type="text" class="form-control search-input container-xxl border-0"
                            placeholder="Search..." aria-label="Search..." />
                        <i class="ti ti-x ti-sm search-toggler cursor-pointer"></i>
                    </div>
                </nav>
                

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">

                    @if (Session::has('success'))

                        <div class="alert alert-success" role="alert">
                            <strong>Success!</strong> {{ Session::get('success') }}
                        </div>

                    @endif
                    @if (Session::has('error'))

                        <div class="alert alert-danger" role="alert">
                            <strong>Error!</strong> {{ Session::get('error') }}
                        </div>

                    @endif
                    @if (count($errors) > 0)

                        <div class="alert alert-danger" role="alert">
                            <strong>Error(s)!</strong>
                            <ol id="ul">
                                @foreach ($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ol>
                        </div>
                    @endif
                    @yield('main')
                    <!-- / Content -->

                </div>
                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->


    <!-- delete record from list -->
    <div class="modal fade" style="z-index: 100000" id="delete_record" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
            <form id="delete_record_form" method="post">
                @method('delete')
                @csrf
                <div class="modal-content p-3 p-md-5">
                    <div class="modal-body">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                        <div class="text-center mb-4">
                            <h3 class="mb-2">@lang('admin.confirmation')</h3>
                            <p class="text-muted">@lang('admin.confirmation_decription')</p>
                            <p class="text-muted"><b id="delete_record_message"></b></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-danger me-sm-3 me-1">@lang('admin.Delete')</button>
                            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                                aria-label="Close">
                                @lang('admin.Cancel')
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- delete record from list -->

    <!-- delete File -->
    <div class="modal fade" style="z-index: 100000" id="delete_file" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="mb-2">@lang('admin.confirmation')</h3>
                        <p class="text-muted">@lang('admin.delete_confirmation_decription')</p>
                        <p class="text-muted"><b id="delete_file_record_message"></b></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-danger me-sm-3 me-1" onclick="delete_file()"
                            data-bs-dismiss="modal" aria-label="Close">@lang('admin.Delete')</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                            aria-label="Close">
                            @lang('admin.Cancel')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- delete File-->

      


    <!-- delete record from list -->
    <div class="modal fade" style="z-index: 100000" id="change_status" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
            <form id="change_status_form" method="post">
                @csrf
                <div class="modal-content p-3 p-md-5">
                    <div class="modal-body">
                        <input type="hidden" id="status_value" name="status">
                        <input type="hidden" id="mruid" name="id">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                        <div class="text-center mb-4">
                            <h3 class="mb-2">@lang('admin.confirmation')</h3>
                            <p class="text-muted status_al" id="delete_record_message_active">@lang('admin.change_status_decription_active')</p>
                            <p class="text-muted status_al" id="delete_record_message_deactive">@lang('admin.change_status_decription_deactive')</p>

                            <p class="text-muted" id="delete_record_message_message"><b></b></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-info me-sm-3 me-1">@lang('admin.Yes')</button>
                            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                                aria-label="Close">
                                @lang('admin.Cancel')
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <input type="hidden" id="_token" value="{{ csrf_token() }}">
    <!-- delete record from list -->


    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ url('auth') }}/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ url('auth') }}/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ url('auth') }}/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ url('auth') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ url('auth') }}/assets/vendor/libs/node-waves/node-waves.js"></script>

    <script src="{{ url('auth') }}/assets/vendor/libs/hammer/hammer.js"></script>
    <script src="{{ url('auth') }}/assets/vendor/libs/i18n/i18n.js"></script>
    <script src="{{ url('auth') }}/assets/vendor/libs/typeahead-js/typeahead.js"></script>

    <script src="{{ url('auth') }}/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ url('auth') }}/assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="{{ url('auth') }}/assets/vendor/libs/swiper/swiper.js"></script>
    <script src="{{ url('auth') }}/assets/vendor/libs/datatables/jquery.dataTables.js"></script>
    <script src="{{ url('auth') }}/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="{{ url('auth') }}/assets/vendor/libs/datatables-responsive/datatables.responsive.js"></script>
    <script src="{{ url('auth') }}/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js"></script>
    <script src="{{ url('auth') }}/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.js"></script>

    <!-- Main JS -->
    <script src="{{ url('auth') }}/assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="{{ url('auth') }}/assets/js/dashboards-analytics.js"></script>
    <script src="{{ url('auth') }}/assets/js/cards-actions.js"></script>

    {{-- my scripts  --}}
    <script src="{{ url('auth') }}/assets/js/persian-date.min.js"></script>
    <script src="{{ url('auth') }}/assets/js/persian-datepicker.min.js"></script>
    <script>
        var base_url = '{{ url('/') }}';
    </script>
        <script src="{{ url('auth') }}/assets/js/myscript.js"></script>

            @yield('script')


    <script src="https://www.gstatic.com/firebasejs/10.3.1/firebase-app.js" type="module"></script>
    <script src="https://www.gstatic.com/firebasejs/10.3.1/firebase-messaging.js" type="module"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <!-- TODO: Add SDKs for Firebase products that you want to use
        https://firebase.google.com/docs/web/setup#available-libraries -->
        <script type="module">

            // Import the functions you need from the SDKs you need

            import { initializeApp } from "https://www.gstatic.com/firebasejs/10.3.1/firebase-app.js";

            import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.3.1/firebase-analytics.js";
            import { getMessaging, getToken, onMessage} from "https://www.gstatic.com/firebasejs/10.3.1/firebase-messaging.js";
            // TODO: Add SDKs for Firebase products that you want to use

            // https://firebase.google.com/docs/web/setup#available-libraries


            // Your web app's Firebase configuration
            (async function () {
            // For Firebase JS SDK v7.20.0 and later, measurementId is optional
                const firebaseConfig = {
              apiKey: "AIzaSyBM_u3KcQhs-AMKUsxPKRd-haTQvJGhWeM",
              authDomain: "test-KayTask.firebaseapp.com",
              projectId: "test-KayTask",
              storageBucket: "test-KayTask.appspot.com",
              messagingSenderId: "139283164553",
              appId: "1:139283164553:web:0dc123f07e377d14c34558",
              measurementId: "G-7X7KTGF38E"
            };


            // Initialize Firebase
            const app = initializeApp(firebaseConfig);

            const analytics = getAnalytics(app);
            const messaging = getMessaging(app);
            
            function req_pre(){
                Notification.requestPermission().then((pre) => {
                    if (pre === "granted"){
                        initFirebaseMessagingRegistration();
                    }
                });
            }
            function initFirebaseMessagingRegistration() {
                getToken(messaging,{validKey: "BKVQ4lMc8zqFj-bH3dLl447YalfYWDVypDfgya8SCQ2kUdw7XiHU0SWdxYElWbDxi7u-a6ZBP3y7HvBJX1is8AM"}).then((token) => {
                    console.log("test");
                    if(token){
                        axios.post("{{ route('fcmToken') }}",{
                    _method:"PATCH",
                    token
                }).then(({data})=>{
                    console.log(data)
                }).catch(({response:{data}})=>{
                    console.error(data)
                })
                    }else{
                        req_pre();
                    }
                })
        }

        initFirebaseMessagingRegistration();

        onMessage(messaging, (payload) => {
            const notificationTitle = payload.notification.title;
  const notificationOptions = {
    body: payload.notification.body,
    icon: '/firebase-logo.png'
  };

  self.registration.showNotification(notificationTitle,
    notificationOptions);
        });

    })();
           
          </script>



</body>

</html>

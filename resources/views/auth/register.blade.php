<!DOCTYPE html>

<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ url('auth') }}/assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>KayTask - Register</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ url('auth') }}/assets/img/favicon/favicon.ico" />

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
    <link rel="stylesheet" href="{{ url('auth') }}/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ url('auth') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="{{ url('auth') }}/assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="{{ url('auth') }}/assets/vendor/libs/typeahead-js/typeahead.css" />
    <!-- Vendor -->
    <link rel="stylesheet"
        href="{{ url('auth') }}/assets/vendor/libs/formvalidation/dist/css/formValidation.min.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ url('auth') }}/assets/vendor/css/pages/page-auth.css" />
    <!-- Helpers -->
    <script src="{{ url('auth') }}/assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="{{ url('auth') }}/assets/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ url('auth') }}/assets/js/config.js"></script>
</head>

<body>
    <!-- Content -->

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                <!-- Login -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center mb-4 mt-2">
                            <a href="{{ url('/') }}" class="app-brand-link gap-2">
                                <span class="app-brand-logo">
                                    <img src="{{ url('auth/assets/img/logo.png') }}" height="100px">
                                </span>
                            </a>
                        </div>
                        @if (Session::has('error'))
                <div class="alert alert-success" role="alert">
                    <strong>Error!</strong> {{ Session::get('error') }}
                </div>
            @endif
                        <!-- /Logo -->

                        <form class="mb-3" action="{{ route('register') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="fullname" class="form-label">@lang('auth.fullname')</label>
                                <input type="text" class="form-control" id="fullname" name="fullname"
                                    placeholder="@lang('auth.fullname')" autofocus />
                                @error('fullname')
                                    <div class="">
                                        <div>{{ $message }}</div>
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">@lang('auth.email')</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    placeholder="@lang('auth.email')" autofocus />
                                @error('email')
                                    <div class="fv-plugins-message-container">
                                        <div data-field="email" data-validator="notEmpty">{{ $message }}</div>
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">@lang('auth.password_lable')</label>
                                    <a href="auth-forgot-password-basic.html">
                                    </a>
                                </div>
                                @error('password')
                                <div class="fv-plugins-message-container">
                                    <div data-field="password" data-validator="notEmpty">{{ $message }}</div>
                                </div>
                            @enderror
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                                @error('email')
                                    <div class="fv-plugins-message-container invalid-feedback">
                                        <div data-field="password" data-validator="notEmpty">{{ $message }}
                                        </div>
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" type="submit">@lang('auth.sign_up')</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /Register -->
            </div>
        </div>
    </div>


    !-- Core JS -->
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
    <script src="{{ url('auth') }}/assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
    <script src="{{ url('auth') }}/assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
    <script src="{{ url('auth') }}/assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>

    <!-- Main JS -->
    <script src="{{ url('auth') }}/assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="{{ url('auth') }}/assets/js/pages-auth.js"></script>
  </body>
</html>

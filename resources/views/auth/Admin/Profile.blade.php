@extends('layouts.AdminLayout')
@section('head')
    <link rel="stylesheet" href="{{ url('auth') }}/assets/vendor/css/pages/page-profile.css" />
@endsection
@section('main')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">User Profile /</span> Profile</h4>
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="user-profile-header-banner">
                    <img src="<?= $u->cover == '' ? url('auth/assets/img/pages/profile-banner.png') : url('uploads/' . $u->cover) ?>"
                        alt="Banner image" class="rounded-top" />
                </div>
                <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4"
                    style="overflow: hidden">
                    <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                        <img src="<?= $u->avatar == '' ? url('auth/assets/img/avatars/14.png') : url('uploads/' . $u->avatar) ?>"
                            alt="user image" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img" />
                    </div>
                    <div class="flex-grow-1 mt-3 mt-sm-5">
                        <div
                            class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                            <div class="user-profile-info">
                                <h4>{{ $u->fullname }}</h4>
                                <ul
                                    class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                    <li class="list-inline-item"><i class="ti ti-color-swatch"></i> {{ $u->role }}</li>
                                    <li class="list-inline-item"><i class="ti ti-calendar"></i> Joined
                                        {{ date('M Y', strtotime($u->created_at)) }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Header -->

    <!-- Navbar pills -->
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-pills flex-column flex-sm-row mb-4">
                <li class="nav-item">
                    <a class="nav-link active" href="javascript:void(0);" role="tab" data-bs-toggle="tab"
                        data-bs-target="#profile" aria-controls="profile" aria-selected="true"><i
                            class="ti-xs ti ti-user-check me-1"></i> Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="javascript:void(0);" role="tab" data-bs-toggle="tab"
                        data-bs-target="#edit" aria-controls="edit" aria-selected="true"><i
                            class="ti-xs ti ti-users me-1"></i> Edit</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="javascript:void(0);" role="tab" data-bs-toggle="tab"
                        data-bs-target="#Wallets" aria-controls="Wallets" aria-selected="true"><i
                            class="ti-xs ti ti-currency-bitcoin me-1"></i> Wallets</a>
                </li>
            </ul>
        </div>
    </div>
    <!--/ Navbar pills -->

    <!-- User Profile Content -->
    <form action="{{ url('Admin/Profile') }}" method="post">
      @csrf
    <div class="tab-content">
       
            <div class="tab-pane fade show active" id="profile" role="tabpanel">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-5">
                        <!-- About User -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <small class="card-text text-uppercase">About</small>
                                <ul class="list-unstyled mb-4 mt-3">
                                    <li class="d-flex align-items-center mb-3">
                                        <i class="ti ti-user"></i><span class="fw-bold mx-2">Full Name:</span>
                                        <span>{{ $u->fullname }}</span>
                                    </li>
                                    <li class="d-flex align-items-center mb-3">
                                        <i class="ti ti-crown"></i><span class="fw-bold mx-2">Role:</span>
                                        <span>{{ $u->role }}</span>
                                    </li>
                                    <li class="d-flex align-items-center mb-3">
                                        <i class="ti ti-calendar-event"></i><span class="fw-bold mx-2">Joined:</span>
                                        <span>{{ date('M Y', strtotime($u->created_at)) }}</span>
                                    </li>
                                </ul>
                                <small class="card-text text-uppercase">Contacts</small>
                                <ul class="list-unstyled mb-4 mt-3">
                                    <li class="d-flex align-items-center mb-3">
                                        <i class="ti ti-phone-call"></i><span class="fw-bold mx-2">Contact:</span>
                                        <span>{{ $u->phone }}</span>
                                    </li>
                                    <li class="d-flex align-items-center mb-3">
                                        <i class="ti ti-mail"></i><span class="fw-bold mx-2">Email:</span>
                                        <span>{{ $u->email }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!--/ About User -->
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-5">
                        <!-- Profile Overview -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <p class="card-text text-uppercase">Overview</p>
                                <ul class="list-unstyled mb-0">
                                    <li class="d-flex align-items-center mb-3">
                                        <i class="ti ti-check"></i><span class="fw-bold mx-2">Owned Projects</span>
                                        <span>{{ $projects }}</span>
                                    </li>
                                    <li class="d-flex align-items-center mb-3">
                                        <i class="ti ti-arrow-down"></i><span class="fw-bold mx-2">Assigned Projects:</span>
                                        <span>{{ $assigned_to_user }}</span>
                                    </li>
                                    <li class="d-flex align-items-center mb-3">
                                        <i class="ti ti-arrow-up"></i><span class="fw-bold mx-2">Assigned to others:</span>
                                        <span>{{ $assigned_to_others }}</span>
                                    </li>
                                    <li class="d-flex align-items-center mb-3">
                                        <i class="ti ti-arrows-sort"></i><span class="fw-bold mx-2">All Projects:</span>
                                        <span>{{ $assigned_to_user + $projects }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!--/ Profile Overview -->
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="edit" role="tabpanel">
                <div class="card mb-4">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6 col-sm-12 mb-3">
                                <label class="form-label" for="fullname">@lang('admin.fullname')</label>
                                <input type="text" class="form-control" id="fullname" value="{{ $u->fullname }}"
                                    readonly />
                            </div>

                            <div class="col-md-6 col-sm-12 mb-3">
                                <label class="form-label" for="email">@lang('admin.email')</label>
                                <input type="email" class="form-control" name="email" id="email"
                                    value="{{ old('email') ?? @$u['email'] }}" placeholder="@lang('admin.email')" />
                            </div>

                            <div class="col-md-6 col-sm-12 mb-3">
                                <label class="form-label" for="phone">@lang('admin.phone')</label>
                                <input type="phone" class="form-control" name="phone" id="phone"
                                    value="{{ old('phone') ?? @$u['phone'] }}" placeholder="@lang('admin.phone')" />
                            </div>
                            
                            <div class="col-md-6 col-sm-12 mb-3">
                                <label class="form-label" for="email">@lang('admin.password')</label>
                                <input type="password" class="form-control" name="password" id="password"
                                    value="" placeholder="@lang('admin.password')" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="input-group input-group-dynamic">
                                    <div class="mb-3 col-md-12">
                                        <label for="formFile" class="form-label">avatar</label>
                                        <input class="form-control" id="file_avatar" type="file"
                                            onchange="upload_profile_avatar()" / />
                                    </div>
                                    <div class="progress-wrapper ms-auto w-100" id="upload_loading_avatar"
                                        style="display: none;">
                                        <div class="progress">
                                            <div class="progress-bar bg-gradient-primary" role="progressbar"
                                                aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-primary  text-white " role="alert" id="upload_errolr_avatar"
                                    style="display:none">
                                    <strong>@lang('admin.error')!</strong>
                                    <p></p>
                                </div>
                                <div class="row" style="margin-top: 20px" id="upload_result_avatar">
                                </div>
                            </div>

                            <div class="col-12 col-sm-6">
                                <div class="input-group input-group-dynamic">
                                    <div class="mb-3 col-md-12">
                                        <label for="formFile" class="form-label">cover</label>
                                        <input class="form-control" id="file_cover" type="file"
                                            onchange="upload_profile_cover()" / />
                                    </div>
                                    <div class="progress-wrapper ms-auto w-100" id="upload_loading_cover"
                                        style="display: none;">
                                        <div class="progress">
                                            <div class="progress-bar bg-gradient-primary" role="progressbar"
                                                aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-primary  text-white " role="alert" id="upload_errolr_cover"
                                    style="display:none">
                                    <strong>@lang('admin.error')!</strong>
                                    <p></p>
                                </div>
                                <div class="row" style="margin-top: 20px" id="upload_result_cover">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            @lang('admin.Edit')
                        </button>
                    </div>
                </div>
            </div>
            <!--/ User Profile Content -->


            <div class="tab-pane fade show" id="Wallets" role="tabpanel">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row" id="wallet_users">

                            <?php $count = 0; ?>
                            @foreach ($wallets as $w)
                                <?php $checked = $w['metadata'] == 'active' ? 'checked' : ''; ?>
                                <div class="col-md-12 mb-4 wallet_count">
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                            placeholder="USDT TRC20 Wallet address" name="wallet[{{ $count }}]"
                                            value="{{ $w['attr_content'] }}" aria-label="USDT TRC20 Wallet address">
                                        <div class="input-group-text">
                                            <input class="form-check-input mt-0" {{ $checked }} type="radio"
                                                value="{{ $count++ }}" name="active_wallet"
                                                aria-label="active wallet">
                                        </div>
                                        <button class="btn btn-outline-warning waves-effect" type="button"
                                            onclick="remove_milestone(this)">Remove</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <button class="btn btn-success form-control" type="button"
                                    onclick="Add_Wallet_User()">Add Wallet</button>

                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                          @lang('admin.Edit')
                        </button>
                    </div>
                </div>
            </div>
    </div>
  </form>

@endsection

@section('script')
    <script>
        @if (@$u['avatar'] != '' && file_exists(public_path('uploads/' . @$u['avatar'])))
            insert_file('avatar', 'avatar', 'avatar', '{{ $u['avatar'] }}', 'one', 'true');
        @endif

        @if (@$u['cover'] != '' && file_exists(public_path('uploads/' . @$u['cover'])))
            insert_file('cover', 'cover', 'cover', '{{ $u['cover'] }}', 'one', 'true');
        @endif
    </script>
@endsection

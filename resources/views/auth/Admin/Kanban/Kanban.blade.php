@extends('layouts.AdminLayout')
@section('head')
    <link rel="stylesheet" href="{{ url('auth') }}/assets/vendor/libs/select2/select2.css" />
@endsection
@section('main')
    <!-- Teams Cards -->

                    <div class="row g-4">
                        @foreach ($boards as $b)
                        <div class="col-xl-3 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <a href="javascript:;" class="d-flex align-items-center">
                                            <div class="me-2 text-body h5 mb-0">
                                                <a href="{{ url('Admin/Kanbans/Boards/' . $b['id']) }}">
                                                    {{ $b['title'] }}
                                                </a>
                                            </div>
                                        </a>
                                        <div class="ms-auto">
                                            <ul class="list-inline mb-0 d-flex align-items-center">
                                                <li class="list-inline-item">
                                                    <div class="dropdown">
                                                        <button type="button" class="btn dropdown-toggle hide-arrow p-0"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="ti ti-dots-vertical text-muted"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                           
                                                            <li><a class="dropdown-item" href="{{ url('Admin/Kanbans/Boards/' . $b['id']) }}">View
                                                                    Details</a></li>
                                                        

                                                            @if ($b['user_id'] == auth()->user()->id)
                                                                <li><a class="dropdown-item" href="javascript:void(0);"
                                                                    data-bs-toggle="offcanvas" data-bs-target="#addNewBoard" 
                                                                    onclick='editBoards("{{ $b['title'] }}","{{ $b['description'] }}","{{ $b['id'] }}",@json($b['members_ids']))'>Edit
                                                                        Boartd</a></li>

                                                                <li>
                                                                    <a class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#delete_record"
                                                                    onclick="confirm_delete_recored('{{ url('Admin/Kanbans/Boards/' . $b['id']) }}','{{ $b['title'] }}')" href="javascript:void(0);">Delete Board</a>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <p class="mb-3">
                                        {{ $b['description'] }}
                                    </p>
                                    <div class="d-flex align-items-center pt-1">
                                        <div class="d-flex align-items-center">
                                            <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                                                @foreach ($b['members'] as $m)
                                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                                    data-bs-placement="top" title="{{ $m['fullname'] }}"
                                                    class="avatar avatar-sm pull-up">
                                                    <div class="avatar me-2 flex-shrink-0">
                                                        @if($m['avatar'] != '' && file_exists(public_path().'/uploads/'.$m['avatar']))
                                                            <img src="{{ url('uploads/' . $m['avatar']) }}" alt="Avatar"
                                                            class="rounded-circle mt-1" />
                                
                                                        @else
                                                            <span
                                                            class="avatar-initial bg-label-{{ $GL_PROFILE_COLOR[ord(substr($m['fullname'],0,1))] }} rounded-circle mt-1">{{ substr($m['fullname'],0,2) }}</span>
                                                        @endif
                                                    </div>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                            <div class="col-xl-3 col-lg-3 col-md-6">
                                <div class="card" style="padding: 5px 0">
                                    <div class="card-body" style="padding: 5px 0">
                                        <button type="button" class="btn text-center text-border" style="width: 100%"
                                        data-bs-toggle="offcanvas" data-bs-target="#addNewBoard">
                                            <i class="ti ti-plus ti-xs"></i>
                                            <span class="align-middle">Add new</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                    </div>
                


                    <!-- Offcanvas to add new user -->
                <div
                class="offcanvas offcanvas-end"
                tabindex="-1"
                id="addNewBoard"
                aria-labelledby="addNewBoardLabel"
              >
                <div class="offcanvas-header">
                  <h5 id="addNewBoardLabel" class="offcanvas-title">Add User</h5>
                  <button
                    type="button"
                    class="btn-close text-reset"
                    data-bs-dismiss="offcanvas"
                    aria-label="Close"
                  ></button>
                </div>
                <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
                  <form class="add-new-user pt-0" id="addNewBoardForm" action="{{ url('Admin/Kanbans/Boards') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                      <label class="form-label" for="kanban_title">Title</label>
                      <input
                        type="text"
                        class="form-control"
                        id="kanban_title"
                        placeholder="Title"
                        name="title"
                        aria-label="Title"
                      />
                    </div>
                    <div class="mb-3">
                      <label class="form-label" for="description">Description</label>
                      <textarea class="form-control" name="description" id="description"></textarea>
                    </div>
                    <div class="mb-3 board_members">
                      <label class="form-label" for="Members">Members</label>
                      <select id="Members" class="select2 form-select" name="members[]" multiple>
                        @foreach ($users as $u)
                            <option value="{{ $u['id'] }}">{{ $u['fullname'] }}</option>
                        @endforeach
                      </select>
                    </div>

                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                  </form>
                </div>
              </div>
@endsection
@section('script')
    <script src="{{ url('auth') }}/assets/vendor/libs/select2/select2.js"></script>
    <script>
        $(function() {

            select2 = $('.select2');


            // Select2
            // --------------------------------------------------------------------

            // Default
            if (select2.length) {
                select2.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Select value',
                        dropdownParent: $this.parent()
                    });
                });
            }
        });
    </script>
@endsection


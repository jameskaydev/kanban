@extends('layouts.AdminLayout')
@section('head')
    <link rel="stylesheet" href="{{ url('auth') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="{{ url('auth') }}/assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="{{ url('auth') }}/assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="{{ url('auth') }}/assets/vendor/libs/jkanban/jkanban.css" />
    <link rel="stylesheet" href="{{ url('auth') }}/assets/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="{{ url('auth') }}/assets/vendor/libs/flatpickr/flatpickr.css" />
    <link rel="stylesheet" href="{{ url('auth') }}/assets/vendor/libs/quill/typography.css" />
    <link rel="stylesheet" href="{{ url('auth') }}/assets/vendor/libs/quill/katex.css" />
    <link rel="stylesheet" href="{{ url('auth') }}/assets/vendor/libs/quill/editor.css" />
    <link rel="stylesheet" href="{{ url('auth') }}/assets/vendor/css/pages/app-kanban.css" />

    <script src="{{ url('auth') }}/assets/vendor/js/helpers.js"></script>
@endsection
@section('main')
    <div class="container-xxl flex-grow-1 container-p-y card-action ">

        <div class="card-header mb-3">
            <h4 class="card-action-title">{{ @$pageName }}</h4>
            <div class="card-action-element">
                <ul class="list-inline mb-0">
                    @if ($archive == '')
                        <li class="list-inline-item">
                            <a href="{{ url('Admin/Kanbans/Boards/' . $board_id . '/archived') }}"><i class="tf-icons ti ti-archive-filled ti-sm"></i></a>
                        </li>
                    @else  
                    <li class="list-inline-item">
                        <a href="{{ url('Admin/Kanbans/Boards/' . $board_id) }}"><i class="tf-icons ti ti-refresh ti-sm"></i></a>
                    </li>
                    @endif
                  
                   
                    <li class="list-inline-item">
                        <a data-bs-toggle="offcanvas" data-bs-target="#addNewBoard"><i
                                class="tf-icons ti ti-settings ti-sm"></i></a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="app-kanban loading_blur">
            <!-- Add new board -->
            <div class="row">
                <div class="col-12">
                    <form class="kanban-add-new-board">
                        <label class="kanban-add-board-btn" for="kanban-add-board-input">
                            @if($archive == '')
                            <i class="ti ti-plus ti-xs"></i>
                            <span class="align-middle">Add new</span>
                            @endif
                        </label>
                        <input type="text" class="form-control w-px-250 kanban-add-board-input mb-2 d-none"
                            placeholder="Add Board Title" id="kanban_board_title" required />
                        <div class="mb-3 kanban-add-board-input d-none">
                            <button class="btn btn-primary btn-sm me-2"
                                onclick="addNewCard('{{ $board_id }}')">Add</button>
                            <button type="button" class="btn btn-label-secondary btn-sm kanban-add-board-cancel-btn">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>


            <!-- Kanban Wrapper -->
            <div class="kanban-wrapper"></div>

            <!-- Edit Task & Activities -->
            <div class="offcanvas offcanvas-end kanban-update-item-sidebar">
                <div class="offcanvas-header border-bottom">
                    <h5 class="offcanvas-title">Edit Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="nav nav-tabs tabs-line">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-update">
                                <i class="ti ti-edit me-2"></i>
                                <span class="align-middle">Edit</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-activity">
                                <i class="ti ti-trending-up me-2"></i>
                                <span class="align-middle">Activity</span>
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content px-0 pb-0">
                        <!-- Update item/tasks -->
                        <div class="tab-pane fade show active" id="tab-update" role="tabpanel">
                            <form id="cardEditForm" method="POST">
                                @method('put')
                                @csrf
                                <div class="row">
                                    <div class="mb-3 col-md-12">
                                        <label class="form-label" for="card_title">Title</label>
                                        <input type="text" id="card_title" name="title" class="form-control"
                                            placeholder="Enter Title" />
                                    </div>
                                    <div class="mb-3 col-md-12">
                                        <label class="form-label" for="card_Description">Description</label>
                                        <textarea id="card_Description" name="description" class="form-control" placeholder="Enter Description"></textarea>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="card_due_date">Due Date</label>
                                        <input type="text" id="due-date" name="due_date" class="form-control"
                                            placeholder="Enter Due Date" />
                                    </div>
                                    <div class="mb-3 col-md-6 card_label">
                                        <label class="form-label" for="label"> Label</label>
                                        <select class="select2" name="label" id="card_label" data-planceholder="Select Label...">
                                            @foreach ($board['label_array'] as $k => $bl)
                                                <option data-color="bg-{{ $k }}" value="{{ $k }}">{{ $bl }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-12">
                                        <label class="form-label" for="card_Assign">Assign</label>
                                        <select id="card_Assign" class="select2 form-select" data-planceholder="Select Person" name="assign[]" multiple>
                                            @foreach ($users as $u)
                                                <option value="{{ $u['id'] }}">{{ $u['fullname'] }}</option>
                                            @endforeach
                                        </select>
                                        <label class="switch switch-success mb-3" style="margin-top:10px;margin-left:8px ">
                                            <input type="checkbox" class="switch-input" id="notif_via_sms" name="notif_via_sms" value="True"/>
                                            <span class="switch-toggle-slider">
                                                <span class="switch-on">
                                                    <i class="ti ti-check"></i>
                                                </span>
                                                <span class="switch-off">
                                                <i class="ti ti-x"></i>
                                                </span>
                                            </span>
                                            <span class="switch-label">Notif new users via SMS</span>
                                        </label>
                                    </div>
                                    <div class="mb-3 col-md-12">
                                        <div class="col-12">
                                            <div class="input-group input-group-dynamic">
                                                <div class="mb-3 col-md-12">
                                                    <label for="formFile" class="form-label">Atachment</label>
                                                    <input class="form-control" id="file_atachments" type="file"
                                                        onchange="global_upload('atachments','atachments','atachments','true')"
                                                        / />
                                                </div>
                                                <div class="progress-wrapper ms-auto w-100" id="upload_loading_atachments"
                                                    style="display: none;">
                                                    <div class="progress">
                                                        <div class="progress-bar bg-gradient-primary" role="progressbar"
                                                            aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="alert alert-primary  text-white " role="alert"
                                                id="upload_errolr_atachments" style="display:none">
                                                <strong>@lang('admin.error')!</strong>
                                                <p></p>
                                            </div>
                                            <div class="row" style="margin-top: 20px" id="upload_result_atachments">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex flex-wrap">
                                    <button type="submit" class="btn btn-primary me-3">
                                        Update
                                    </button>
                                    <button type="button" class="btn btn-label-danger me-3" data-bs-dismiss="offcanvas"
                                        id="delete_record_btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#delete_record">
                                        Delete
                                    </button>
                                    @if($archive == '')
                                        <button type="button" id="archive_card_btn" class="btn btn-label-warning ml-5"  data-bs-toggle="modal"
                                        data-bs-target="#archive_card">Archive</button>
                                    @else 
                                        <button type="button" id="archive_card_btn" class="btn btn-label-success ml-5"  data-bs-toggle="modal"
                                        data-bs-target="#archive_card">Restore</button>
                                    @endif
                                </div>
                            </form>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <form id="CommentsForm" method="POST">
                                        @csrf
                                        <div class="input-group">
                                            <input type="text" name="message" class="form-control" placeholder="Message ..." aria-label="Message ..." aria-describedby="button-addon2">
                                            <button type="submit" class="btn btn-outline-primary waves-effect" type="button" id="button-addon2">Send</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="mt-5" id="CommentsFormCon">
                                   
                                </div>
                            </div>
                        </div>
                        <!-- Activities -->
                        <div class="tab-pane fade" id="tab-activity" role="tabpanel">
                            

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="loading_container">
            <div class="loading">
                <div class="sk-wave sk-primary">
                    <div class="sk-wave-rect"></div>
                    <div class="sk-wave-rect"></div>
                    <div class="sk-wave-rect"></div>
                    <div class="sk-wave-rect"></div>
                    <div class="sk-wave-rect"></div>
                </div>
            </div>
        </div>
    </div>

    

    <div class="offcanvas offcanvas-end" tabindex="-1" id="addNewBoard" aria-labelledby="addNewBoardLabel">
        <div class="offcanvas-header">
            <h5 id="addNewBoardLabel" class="offcanvas-title">Edit Board</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
            <ul class="nav nav-tabs tabs-line">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#update_board">
                        <i class="ti ti-edit me-2"></i>
                        <span class="align-middle">Edit</span>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#board_activity">
                        <i class="ti ti-trending-up me-2"></i>
                        <span class="align-middle">Activity</span>
                    </button>
                </li>
            </ul>
            <div class="tab-content px-0 pb-0">
                <!-- Update item/tasks -->
                <div class="tab-pane fade show active" id="update_board" role="tabpanel">
                    <form class="add-new-user pt-0" action="{{ url('/Admin/Kanbans/Boards/' . $board_id) }}"
                        method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="kanban_title">Title</label>
                            <input type="text" class="form-control" id="kanban_title" placeholder="Title"
                                name="title" aria-label="Title" value="{{ $board['title'] }}" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="description">Description</label>
                            <textarea class="form-control" cols="5" name="description" id="description">{{ $board['description'] }}</textarea>
                        </div>
                        <div class="mb-3 board_members">
                            <label class="form-label" for="Members">Members</label>
                            <select id="Members" class="select2 form-select" data-planceholder="Select Person" name="members[]" multiple>
                                @foreach ($allUsers as $u)
                                    <option value="{{ $u['id'] }}" @if (in_array($u['id'], $kanban_members)) selected @endif>
                                        {{ $u['fullname'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6 card_label">
                                <label class="form-label" for="label">Lable</label>
                                <select class="select2" name="label" data-planceholder="Select Lable.." id="card_label_2" onchange="card_title_selected(event)">
                                    @foreach ($board['label_array'] as $k => $bl)
                                        <option data-color="bg-{{ $k }}" value="{{ $k }}">{{ $bl }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="lable_title">Lable Title</label>
                                <input type="text" class="form-control" id="lable_title" placeholder="Lable Title"
                                    name="label_title" aria-label="Title" value="" />
                            </div>
                        </div>
                        

                        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                        <button type="reset" class="btn btn-label-secondary"
                            data-bs-dismiss="offcanvas">Cancel</button>
                    </form>
                </div>
                <div class="tab-pane fade show" id="board_activity" role="tabpanel">
                  @foreach ($events as $e)
                  <div class="media mb-4 d-flex align-items-start">
                      <div class="avatar me-2 flex-shrink-0">
                          @if ($e['profile_image']['type'] == 'text')
                              <span
                                  class="avatar-initial bg-label-success rounded-circle mt-1">{{ $e['profile_image']['text'] }}</span>
                          @else
                              <img src="{{ $e['profile_image']['image'] }}" alt="Avatar"
                                  class="rounded-circle mt-1" />
                          @endif
                      </div>
                      <div class="media-body">
                          <p class="mb-0"><span class="fw-semibold">{{ $e->fullname }}</span>
                              {{ $e->description }}
                              <ul>
                            @foreach ($e->array_content_json as $ac)
                                <li>{{ $ac }}</li>
                            @endforeach
                              </ul>
                            </p>
                          <small
                              class="text-muted">{{ $GL_Jalalian->fromDateTime($e->created_at)->format('Y/m/d H:i') }}</small>
                      </div>
                  </div>
              @endforeach
                </div>
            </div>

        </div>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="editABoard" aria-labelledby="addNewBoardLabel">
        <div class="offcanvas-header">
            <h5 id="addNewBoardLabel" class="offcanvas-title">Edit List</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
                <!-- Update item/tasks -->
                <div class="tab-pane fade show active" id="update_board" role="tabpanel">
                    <form class="add-new-user pt-0" id="Kanban_edit_list_form" action="{{ url('/Admin/Kanbans/Boards/' . $board_id) }}"
                        method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="board_list_title">Title</label>
                            <input type="text" class="form-control" id="board_list_title" placeholder="Title"
                                name="title" aria-label="Title" value="{{ $board['title'] }}" />
                        </div>

                        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                        <button type="reset" class="btn btn-label-secondary"
                            data-bs-dismiss="offcanvas">Cancel</button>
                    </form>
                </div>


        </div>
    </div>

    <div class="modal fade" style="z-index: 100000" id="archive_card" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
            <form method="POST" id="archive_card_form">
                @csrf
                <div class="modal-content p-3 p-md-5">
                    <div class="modal-body">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="text-center mb-4">
                            <h3 class="mb-2">@lang('admin.confirmation')</h3>
                            <p class="text-muted">Do you want to {{ ($archive != '') ? "restore" : 'archive' }} this record?</p>
                            <p class="text-muted"><b id="archive_card_message"></b></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1" id="archive_card_submit">{{ ($archive != '') ? "restore" : 'archive' }}</button>
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
@endsection

@section('script')
    <script>
        var board_id = {{ $board_id }};
        var archive = '{{ $archive }}';
    </script>
    <script src="{{ url('auth') }}/assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="{{ url('auth') }}/assets/vendor/libs/select2/select2.js"></script>
    <script src="{{ url('auth') }}/assets/vendor/libs/jkanban/jkanban.js"></script>
    <script src="{{ url('auth') }}/assets/vendor/libs/quill/katex.js"></script>
    <script src="{{ url('auth') }}/assets/vendor/libs/quill/quill.js"></script>
    <script src="{{ url('auth') }}/assets/js/app-kanban.js"></script>
@endsection

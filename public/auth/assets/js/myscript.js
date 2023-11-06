$(document).ready(function () {
    $('.date-time-picker').persianDatepicker({
        initialValue: false,
        format: "YYYY/MM/DD",
        TimePicker: true,
        timePicker: {
            enabled: true,
            meridiem: {
                enabled: true
            }
        },
        minDate: new persianDate().unix(),
    });

    $('.date-inline-picker').persianDatepicker({
        inline: true,
        altField : '#date_picker_inline',
        altFormat: 'YYYY/MM/DD',
        initialValue: false,
        format: "YYYY/MM/DD",
        minDate: new persianDate().unix(),
        onSelect:submit_form_by_id,
        maxDate: new persianDate()

    });

    $(".apexcharts-heatmap-rect").each(function(el){
        var x = $(this).attr('x');
        var y = $(this).attr('y');
        console.log(x);

        $(this).after('<text x="'+ x +'" y="'+ y +'" fill="#efefef">15</text>');
    });
    var x = 0;
    $(".apexcharts-heatmap text").each(function(el){
        $(this).html(x++);
    });
});
function change_language(lang){
    localStorage.setItem('templateCustomizer-vertical-menu-template--Rtl',lang);
}

function confirm_delete_recored(url, name) {
    $("#delete_record_form").attr('action', url);
    $("#delete_record_message").html(name);
}

function confirm_status_recored(url,name,status){
    $(".status_al").hide();
    $("#delete_record_message_" + status).show();
    $("#delete_record_message_message b").html(name);
    $("#status_value").val(status);
    $("#change_status_form").attr('action',url);
}

function global_upload(unique_id,name,directory, input_disabled, number = 'multiple') {
    var file_data = $("#file_" + unique_id).prop("files")[0];
    upload_loading(unique_id, input_disabled);
    var form_data = new FormData();
    form_data.append('file', file_data);
    form_data.append('directory', directory);
    form_data.append("_token", $("#_token").val());
    $.ajax({
        url: base_url + "/Admin/Upload",
        type: 'post',
        data: form_data,
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
    }).done(function (data) {
        console.log(data);
        upload_loading_off(unique_id, 'true');
        insert_file(unique_id,name,directory, data, number);
    });
}
var delete_id;
var deleted_con;
var deleted_name;
function delete_file_confirm(name, con, id) {
    $("#delete_file_record_message b").html(name);
    delete_id = id;
    deleted_con = con;
    deleted_name = name;
}
function delete_file() {
    upload_loading(deleted_con, false);

    var form_data = new FormData();
    form_data.append(delete_id, deleted_name);
    form_data.append("_token", $("#_token").val());
    $.ajax({
        url: base_url + "/Admin/Delete/" + delete_id,
        type: 'post',
        data: form_data,
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
    }).done(function (data) {
        $("#" + delete_id).remove();
        upload_loading_off(deleted_con, 'false');
    });
}

var upload_animation_interval;
function upload_loading(id, input_disabled) {
    if (input_disabled == 'true') {
        $("#file_" + id).attr('disabled', 'true');
    }
    $('.mr_submit_btns').attr('disabled', 'true');
    $("#upload_loading_" + id).show(200);
    var width = 0;
    var status = 'plus'; // plus || mins
    upload_animation_interval = setInterval(function () {
        if (width < 100 && status == 'plus') {
            $("#upload_loading_" + id + " .progress-bar").css('width', width++ + '%');
        } else if (width >= 100 && status == 'plus') {
            status = 'mins';
            $("#upload_loading_" + id + " .progress-bar").css('width', width + '%');
        } else if (width > 0 && status == 'mins') {
            $("#upload_loading_" + id + " .progress-bar").css('width', width-- + '%');
        } else if (width <= 0 && status == 'mins') {
            status = 'plus';
            $("#upload_loading_" + id + " .progress-bar").css('width', width + '%');
        }
    }, 200);

}
function upload_loading_off(id, input_disabled) {
    if (input_disabled == 'true') {
        $("#file_" + id).attr('disabled', false);
    }
    $('.mr_submit_btns').attr('disabled', false);
    $("#upload_loading_" + id).hide(200);
    clearInterval(upload_animation_interval);
}
function insert_file(unique_id,name,directory, data, number, edit = 'false',size = "2") {
    if (number == 'one') {
        $('#upload_result_' + unique_id).html(' ');
    }else{
        name = name + "[]";
    }
    if (edit == 'true') {
        var url = data;
    } else {
        var url = directory + '/' + data;
    }
    var ftype = data.split('.');
    console.log("vfdvfdvfdfvdfvfvdfvfdv");
    console.log(ftype);
    var fty = ftype[1];
    var file_type_lowercase = fty.toLowerCase();
    var con_id = "mr" + Math.floor(Math.random() * 10000000).toString();
    if (file_type_lowercase == 'jpg' || file_type_lowercase == 'jpeg' || file_type_lowercase == 'png' || file_type_lowercase == 'gif') {
        $('#upload_result_' + unique_id).append('<div class="col-6 col-md-'+ size +'" id="' + con_id + '">' +
            '<input type="hidden" name="'+ name +'" value="' + url + '">' +
            '<div class="uploaded_container">' +
            '<div id="upload_result_'+unique_id+'" class="uploaded_file">' +
            '<img src="' + base_url + '/uploads/' + url + '">' +
            '</div>' +
            '<button type="button" class="btn btn-outline-danger btn-sm"' +
            'data-bs-toggle="modal" data-bs-target="#delete_file"' +
            'onclick="delete_file_confirm(\'' + data + '\',\'' + unique_id + '\',\'' + con_id + '\')">' +
            '<i class="ti ti-trash"></i>' +
            '</button>' +
            '<a type="button" class="btn btn-outline-info btn-sm" href="' + base_url + '/Admin/Download?file=' + url + '" target="_blank">' +
            '<i class="ti ti-download"></i>' +
            '</a>' +
            '</div>' +
            '</div>');
    } else {
        $('#upload_result_' + unique_id).append('<div class="col-6 col-md-'+ size +'" id="' + con_id + '">' +
            '<input type="hidden" name="'+ name +'" value="' + url + '">' +
            '<div class="uploaded_container">' +
            '<div id="upload_result_'+ unique_id +'" class="uploaded_file">' +
            '<div>' + file_type_lowercase + '</div>' +
            '</div>' +
            '<button type="button" class="btn btn-outline-danger btn-sm"' +
            'data-bs-toggle="modal" data-bs-target="#delete_file"' +
            'onclick="delete_file_confirm(\'' + data + '\',\'' + unique_id + '\',\'' + con_id + '\')">' +
            '<i class="ti ti-trash"></i>' +
            '</button>' +
            '<a type="button" class="btn btn-outline-info btn-sm" href="' + base_url + '/Admin/Download?file=' + url + '" target="_blank">' +
            '<i class="ti ti-download"></i>' +
            '</a>' +
            '</div>' +
            '</div>');
    }
}
/*
*
* @params id , assigned_kpis
* this function used for:
* specifies <role id> in the modal<input type=hidden id=role_id>
* showing the selected kpis by changing the checked attribute in the inputs.
*
*/
function assign_kpi(id,assigned_kpis =''){
    $("#role_id").val(id);
    $(".my_kpis").prop('checked',false);

    if(assigned_kpis != '[]') {
        assigned_kpis = JSON.parse(assigned_kpis);
        for(let x = 0;x < assigned_kpis.length;x++){
            assigned_kpi = assigned_kpis[x];
            console.log(assigned_kpi);
            $("#kpi_id_" + assigned_kpi.kpi_id).prop('checked',true);
        }
    }
}
var daley_kpi = '';
function save_kpi_change(ev,kpi_id,type){
    if(daley_kpi != ''){
        clearTimeout(daley_kpi);
    }
    daley_kpi = setTimeout(() => {
        console.log($(ev).attr('type'));
        if($(ev).attr('type') == 'checkbox'){
            if($(ev).prop('checked') == true){
                var kpi_value = $(ev).val(); // input value
            }else{
                var kpi_value = 0;
            }
        }else{
            var kpi_value = $(ev).val(); // input value
        }
        kpi_loading('on');

        //send value to backend by AJAX
        var form_data = new FormData();
        form_data.append('kpi_id', kpi_id);
        form_data.append('kpi_value', kpi_value);
        form_data.append('type', type);
        form_data.append('_date', $("#_date").val());
        form_data.append("_token", $("#_token").val());
        $.ajax({
            url: base_url + "/Admin/MyKPIs/save_kpi",
            type: 'post',
            data: form_data,
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
        }).done(function (data) {
            console.log(data);
            data = JSON.parse(data);
            setTimeout(() => {
                kpi_loading('off');

                kpi_result('on',kpi_id,data['success']);
                setTimeout(() => {
                    kpi_result('off',kpi_id,data['success']);
                },1000);
            },700);
        });
    },1300);
}
var daley_kpi = '';
function save_kpi_change_admin(ev,kpi_id,type,date,user_id){
    if(daley_kpi != ''){
        clearTimeout(daley_kpi);
    }
    daley_kpi = setTimeout(() => {
        let kpi_value = $(ev).val(); // input value
        kpi_loading('on');

        //send value to backend by AJAX
        var form_data = new FormData();
        form_data.append('kpi_id', kpi_id);
        form_data.append('kpi_value', kpi_value);
        form_data.append('type', type);
        form_data.append('date', date);
        form_data.append("_token", $("#_token").val());
        $.ajax({
            url: base_url + "/Admin/Users/"+user_id+"/Edit_kpi_value/save_kpi",
            type: 'post',
            data: form_data,
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
        }).done(function (data) {
            console.log(data);
            setTimeout(() => {
                kpi_loading('off');

                kpi_result('on',kpi_id,data['success']);
                setTimeout(() => {
                    kpi_result('off',kpi_id,data['success']);
                },1000);
            },700);
        });
    },1300);
}
function send_request_save(){

}
function kpi_loading(status){
    if(status == 'on'){
        $(".kpi_blur").addClass('filter-blur');
        $(".loading_container").show();
    }else{
        $(".kpi_blur").removeClass('filter-blur');
        $(".loading_container").hide();
    }
}


function kpi_result(status,id,success = 'true'){
    if(status == 'on'){
        $("#kpi_data_" + id).addClass('filter-blur');
        if(success == 'true'){
            $("#result_container_success_" + id).show();
        }else{
            $("#result_container_error_" + id).show();
        }
    }else{
        $("#kpi_data_" + id).removeClass('filter-blur');
        if(success == 'true'){
            $("#result_container_success_" + id).hide();
        }else{
            $("#result_container_error_" + id).hide();
        }
    }
}


function input_counter(ev,type){
    var parent = $(ev).parent();
    var input = $(parent).children("input");
    var val = $(input).val();
    if(type == 'plus'){
        $(input).val(++val);
    }else{
        $(input).val(--val);
    }
    $(input).change();
}

function update_statistic(ev,url){
    var month = $(ev).val();
    window.location.href = url + "/" + month;
}

function update_call_statistic(ev,url){
    var month = $(ev).val();
    window.location.href = url + "/" + month;
}
function leave_cheker(ev){
    if($(ev).prop('checked') == true){
        var value = 10;
    }else{
        var value = 0;
    }

    send_request_save();
}

var calendar_data = [];
function set_holidays(month,ev){
    calendar_data = [];
    var title = $(ev).html();
    $("#holiday_title").html(title);
    $("#holidays_month").val(month);
    calendar_loading('on');

    //send request

    var form_data = new FormData();
        form_data.append('month', month);
        form_data.append('year', $("#this_year").val());
        form_data.append("_token", $("#_token").val());
        $.ajax({
            url: base_url + "/Admin/Vacations/Calender/Get",
            type: 'post',
            data: form_data,
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
        }).done(function (data) {
            console.log(data);
            data = JSON.parse(data);
            create_calander(data);
            calendar_loading('off');

        });

}

function create_calander(data){
    var html = '';
    var rows = 1;
    var day = 1;
    var days = JSON.parse(data['days']);
    days = Object.values(days);
    while(true){
        html += "<tr>";
        for(let col = 1;col <= 7;col++){
            if(col < data['first_day_name'] && rows == 1){
                html += '<td></td>';
                continue;
            }
            if(day > data['months_day']){
                html += '<td></td>';
                continue;
            }
            var calss_selected = '';
            var mr_selected = '';
            if(days.indexOf(day) != -1){
                calendar_data.push(day.toString());
                calss_selected = 'class="cal__active"';
                mr_selected = 'mr-selected="true"';

            }
            html += '<td '+ calss_selected +' onclick="select_multiple_days(this)" '+ mr_selected +'>' + day++ + '</td>';
        }
        rows++;
        html += "</tr>";
        if(day >= data['months_day']){
            break;
        }
    }
    $("#calender_table_body").html(html);
}
function calendar_loading(status){
    if(status == 'on'){
        $("#holiday_calender_select").addClass('filter-blur');
        $(".loading_container").show();
    }else{
        $("#holiday_calender_select").removeClass('filter-blur');
        $(".loading_container").hide();
    }
}
function select_multiple_days(ev){
    var td_val = $(ev).html();

    if(td_val != ''){
        if($(ev).attr('mr-selected') == 'true'){
            var index=  calendar_data.indexOf(td_val);
            if(index != -1){
                calendar_data.splice(index,1);
            }
            $(ev).removeClass('cal__active');
            $(ev).attr('mr-selected','false');
        }else{
            calendar_data.push(td_val);
            $(ev).addClass('cal__active');
            $(ev).attr('mr-selected','true');
        }
    }
    var json_data = JSON.stringify(calendar_data);
    $("#holidays_days").val(json_data);
}
function submit_form_by_id(){

    console.log('ya ali');
    $("#form_go_to_date").submit();
}

function reorder_kpis(){
    reorder_loading('on');
    var items = [];
    var reorder = $("#reorder_list tbody tr").each(function(index){
        items.push($(this).attr('item-id'));
    });
    items = JSON.stringify(items);
    var form_data = new FormData();
    form_data.append('items', items);
    form_data.append("_token", $("#_token").val());
    $.ajax({
        url: base_url + "/Admin/KPIs/Reorder/Set",
        type: 'post',
        data: form_data,
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
    }).done(function (data) {
        console.log(data);
        data = JSON.parse(data);
        setTimeout(() => {
            reorder_loading('off');
        }, 400);
    });

}
function reorder_loading(status){
    if(status == 'on'){
        $("#reorder_list").addClass('filter-blur');
        $(".loading_container").show();
    }else{
        $("#reorder_list").removeClass('filter-blur');
        $(".loading_container").hide();
    }
}
function remove_milestone(ev){
    var parent = $(ev).parent();
    parent = $(parent).parent();
    $(parent).remove();
}
function add_milestone(title = '',price = ''){
    var num = $(".milestone_counter").length;

    var html = '<input type="hidden" name="milestone['+ num +'][id]" value="0">\n'+
    '<div class="row milestone_counter">\n'+
    '<input type="hidden" name="milestone['+num+'][id]" value="">\n'+
    '<div class="col-md-6 col-sm-12 mb-3">\n'+
        '<input type="text" class="form-control" name="milestone['+ num +'][title]" id="milestone_title"\n'+
             'placeholder="title" value="'+ title +'"/>\n'+
    '</div>\n'+
    '<div class="col-md-5 col-sm-12 mb-3">\n'+
        '<input type="text" class="form-control" name="milestone['+ num +'][price]" id="milestone_price"\n'+
            'placeholder="price" value="'+ price +'"/>\n'+
    '</div>\n'+
    '<div class="col-md-1 col-sm-12 mb-3">\n'+
        '<button type="button" class="btn btn-outline-danger form-control" onclick="remove_milestone(this)"><i class="fa fa-trash"></i></button>\n'+
    '</div>\n'+
'</div>';

    $("#milestones_list").append(html);

}
function show_milestone(id){
    var form_data = new FormData();
    milestone_loading('on');
    $.ajax({
        url: base_url + "/Admin/Projects/milestones/" + id + "/get",
        type: 'get',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
    }).done(function (data) {
        data = JSON.parse(data);
        create_milestone_table(data['content']);
        milestone_loading('off');
    });
}
function milestone_loading(status){
    if(status == 'on'){
        $("#milestone_list").addClass('filter-blur');
        $(".loading_container").show();
    }else{
        $("#milestone_list").removeClass('filter-blur');
        $(".loading_container").hide();
    }
}
function create_milestone_table(content){
    console.log(content);
    html = '';
    for(let x = 0;x < content.length;x++){
        var checked = '';
        data = content[x];
        if(data['status'] == 'done'){
            checked = 'checked';
        }

        html += '<tr>\n'+
        '<td>'+ data['title'] +'</td>\n'+
       ' <td>'+ data['price'] +'</td>\n'+
       ' <td>'+ data['end_time'] +'</td>\n'+
        '<td>\n'+
            '<div class="form-check-primary" style="text-align:center">\n'+
                '<input class="form-check-input" type="checkbox" value="1" onchange="milestone_set('+ data['id'] +','+ data['project_id'] +',this)" '+checked+'/>\n'+
              '</div>\n'+
        '</td>\n'+
    '</tr>';
    }
    $("#milestone_content").html(html);
}
function milestone_set(milestone_id,project_id,ev){
    milestone_loading('on');
    var status = 'proccess';
    if($(ev).prop('checked')){
        status = 'done';
    }
    console.log(status);
    var form_data = new FormData();
    form_data.append('milestone_id', milestone_id);
    form_data.append('status', status);
    form_data.append("_token", $("#_token").val());
    $.ajax({
        url: base_url + "/Admin/Projects/milestones/" + project_id + "/set",
        type: 'post',
        data: form_data,
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
    }).done(function (data) {
        data = JSON.parse(data);
        console.log(data);
        setTimeout(() => {
            milestone_loading('off');
            show_milestone(project_id);
        }, 500);
    });
}
function search_users(type,user='user'){
        var search_value = $("#search_"+ type +"_input").val();
        var search_value_length = search_value.length;
        if(search_value_length > 3){
            search_users_loading('on',type);
            var form_data = new FormData();
            form_data.append('search_value', search_value);
            form_data.append("_token", $("#_token").val());
            $.ajax({
                url: base_url + "/Admin/Projects/Users/Users/Get",
                type: 'post',
                data: form_data,
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
            }).done(function (data) {
                data = JSON.parse(data);
                console.log(data);
                setTimeout(() => {
                    search_users_loading('off',type);
                    show_searched_users(data['content'],type,user);
                }, 200);
            });
        }
}

function search_users_loading(status,type){
    if(status == 'on'){
        $("#"+ type +"_table").addClass('filter-blur');
        $("#loading_container_" + type).show();
    }else{
        $("#"+ type +"_table").removeClass('filter-blur');
        $("#loading_container_" + type).hide();
    }
}
function show_searched_users(content,type,user='user'){
    console.log(content);
    html = '';
   
    for(let x = 0;x < content.length;x++){
        var checked = '';
        data = content[x];
        if(user == 'admin'){
            var url = base_url +'/Admin/AdminProject/'+ ownership_project_id +'/transfer/'+ data['id'];
        }else{
            var url = base_url +'/Admin/Projects/'+ ownership_project_id +'/transfer/'+ data['id'];
        }
        html += '<tr>\n'+
        '<td>'+ (x + 1) +'</td>\n'+
        '<td>'+ data['fullname'] +'</td>\n'+
        '<td>\n';
        if(type == 'ownership'){
            html += '<a href="'+url+'" class="btn btn-primary btn-sm">Transfer</a>\n';
        }else{
            html += '<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" onclick="set_user_id_in_assign('+ data['id'] +')" data-bs-target="#assignment" data-bs-dismiss="modal" aria-label="Close">Assign</button>\n';
        }
        html += '</td>\n'+
    '</tr>';
    }
    $("#"+ type +"_list").html(html);
}
function assign_project(project_id){
    var url = base_url + "/Admin/Projects/assign/"+project_id+"/Set";
    $("#form_assignment").attr('action',url);

}
function submit_project_form(){
    var milestone_counter = $(".milestone_counter").length;
    if(milestone_counter == 0){
        var title = 'Project Milestone';
        var price = $("#price_milestone").val();
        add_milestone(title,price);
    }
}
var search__employer = '';
function search_employers(){
    search__employer = $("#search_users_input").val();
    var search_value_length = search__employer.length;
    if(search_value_length > 2){
        search_users_loading('on');
        var form_data = new FormData();
        form_data.append('search_value', search__employer);
        form_data.append("_token", $("#_token").val());
        $.ajax({
            url: base_url + "/Admin/Projects/Employers/Employers/Get",
            type: 'post',
            data: form_data,
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
        }).done(function (data) {
            data = JSON.parse(data);
            console.log(data);
            setTimeout(() => {
                search_users_loading('off');
                if(data['add_one'] == 'true'){
                    add_one_employer();
                }else{
                    show_searched_employers(data['Content']);
                }
            }, 200);
        });
    }
}
function show_searched_employers(content){
    $("#user_table").show();
    $("#add_one_employer").hide();
    console.log(content);
    html = '';
    for(let x = 0;x < content.length;x++){
        data = content[x];
        html += '<tr>\n'+
        '<td>'+ (x + 1) +'</td>\n'+
        '<td>'+ data['name'] +'</td>\n'+
        '<td>'+ data['title'] +'</td>\n'+
        '<td>'+ data['username'] +'</td>\n'+
        '<td>'+ data['country'] +'</td>\n'+
        '<td>\n'+
        '<button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal" aria-label="Close" onclick="select_emplyer('+ data['id'] +',\'' + data['name'] + '\',\'' + data['username'] + '\')">\n'+
        'select\n'+
        '</button>\n'+
        '</td>\n'+
    '</tr>';
    }
    $("#users_list").html(html);
}
function select_emplyer(id,name,username){
    $("#selected_employer_id").val(id);
    $("#select_button").html(name + "  (" + username + ")");

    $("#select_button").removeClass('btn-primary');
    $("#select_button").addClass('btn-success');
}
function add_one_employer(){
    $("#user_table").hide();
    $("#add_one_employer").show();

    $("#fullname_con input").val(search__employer);

}

function add_new_employer(){
    $("#add_employer .alert").hide();
    var fullname_val = $("#fullname_con input").val();
    var username_val = $("#username_con input").val();
    var country_val = $("#country_con input").val();
    var Platform_val = $("#Platform_con select").val();
    if(username_val == ''){
        $("#username_con .alert").show();
    }
        add_emplpoyer_loading('on');
        var form_data = new FormData();
        form_data.append('platform', Platform_val);
        form_data.append('name', fullname_val);
        form_data.append('username', username_val);
        form_data.append('country', country_val);

        form_data.append("_token", $("#_token").val());
        $.ajax({
            url: base_url + "/Admin/Employers/Ajax/save",
            type: 'post',
            data: form_data,
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
        }).done(function (data) {
            data = JSON.parse(data);
            console.log(data);
            if(data['success'] == 'false'){
                show_add_employers_errors(data['errors']);
                add_emplpoyer_loading('off');
            }else{
                select_emplyer(data['content']['id'],data['content']['name'],data['content']['username']);
                setTimeout(() => {
                    add_emplpoyer_loading('off');
                    $("#add_employer").modal('hide');
                }, 200);

            }

        });

}
function show_add_employers_errors(errors){
    console.log(errors['username']);
    if(errors['platform'] != '' && errors['platform'] != undefined){
        $("#Platform_con .alert").show();
    }
    if(errors['username'] != '' && errors['username'] != undefined){
        $("#username_con .alert").show();
        $("#username_con .alert").html(errors['username']);
    }
}
function add_emplpoyer_loading(status){
    if(status == 'on'){
        $("#add_new_em_form").addClass('filter-blur');
        $("#loading_container_add_new_emp").show();
    }else{
        $("#add_new_em_form").removeClass('filter-blur');
        $("#loading_container_add_new_emp").hide();
    }
}
function set_user_id_in_assign(user_id){
    $("#cast_user_id").val(user_id);
}

var ownership_project_id = '';
function teansfer_ownership(project_id){
    ownership_project_id = project_id;
}


function add_assign_project(){
    var num = $(".assign_counter").length;
    var html ='<div class="row assign_counter">\n'+
    '<input type="hidden" name="assign['+ num +'][id]" value="">\n'+
    '<div class="col-md-6 mb-4">\n'+
        '<label for="select2Basic" class="form-label">user</label>\n'+
        '<select id="select2Basic" class="select2 form-select form-select-lg"\n'+
            'data-allow-clear="true" name="assign['+ num +'][to_user]">\n'+
            create_user_list_option() +
        '</select>\n'+
    '</div>\n'+
    '<div class="col-md-6 mb-4">\n'+
        '<label class="form-label">type</label>\n'+
        '<select class="form-select form-select-lg " name="assign['+ num +'][price_type]"  onchange="toggle_assign_project_type(this)">\n'+
                '<option value="variable">variable</option>\n'+
                '<option value="fixed">fixed</option>\n'+
        '</select>\n'+
    '</div>\n'+
    '<div class="col-md-6 col-sm-12 mb-3 assign_rate">\n'+
        '<input type="text" class="form-control " name="assign['+ num +'][rate]" id="milestone_title"\n'+
            'placeholder="rate" value="40"/>\n'+
    '</div>\n'+
    '<div class="col-md-6  col-sm-12 mb-3 assign_price">\n'+
                                        '<div class="input-group">\n'+
                                            '<div class="input-group-text">\n'+
                                                '<input class="form-check-input mt-0 checkbox_assign" type="checkbox" name="assign['+ num +'][entire]" onchange="check_entire_priceof_assigned_project_checkbox(this)" value="true" checked title="entire price of the project" aria-label="entire price of the project">\n'+
                                            '</div>\n'+
                                            '<input type="text" class="form-control input_asssign" value="-1" name="assign['+ num +'][price]" onkeyup="check_entire_priceof_assigned_project_input(this)" placeholder="price" aria-label="price">\n'+
                                        '</div>\n'+
                                    '</div>\n'+
    '<div class="col-md-5 col-sm-12 mb-3">\n'+
    '<input type="text" class="form-control flatpickr-validation" autocomplete="off" name="assign['+ num +'][deadline]" id="deadline_assign"\n'+
        'placeholder="deadline" value="" />\n'+
    '</div>\n'+

    '<div class="col-md-1 col-sm-12 mb-3">\n'+
        '<button type="button" class="btn btn-outline-danger form-control" onclick="remove_milestone(this)"><i class="fa fa-trash"></i></button>\n'+
    '</div>\n'+
    '<div class="col-md-1 col-sm-12 mb-3 assign_pay"  style="display:none">\n'+
    '<label class="form-label">pay</label>\n'+
        '<input type="checkbox" class="" name="assign['+ num +'][pay]" id="assign_pay" value="pay"/>\n'+
    '</div>\n'+
'</div>';

    $("#assing_list").append(html);
    select2 = $('.select2');

    select2.each(function() {
        var $this = $(this);
        $this.wrap('<div class="position-relative"></div>').select2({
            placeholder: 'Select value',
            dropdownParent: $this.parent()
        });
    });

    const flatPickrList = [].slice.call(document.querySelectorAll('.flatpickr-validation'));
            // Flat pickr
            if (flatPickrList) {
                flatPickrList.forEach(flatPickr => {
                    flatPickr.flatpickr({
                        allowInput: true,
                        monthSelectorType: 'static'
                    });
                });
            }
}
function create_user_list_option(){
    var html = '';
    for(let x = 0;x < users_list_json.length;x++){
        html += '<option value="'+ users_list_json[x]['id'] +'">' + users_list_json[x]['fullname'] + '</option>';
    }
    return html;
}
function add_new_kpi_task(){
    var num = $(".task_counter").length;
    var html ='<div class="row task_counter">\n'+
    '<div class="col-md-11 col-sm-12 mb-3">\n'+
        '<input type="text" class="form-control" mr-note-id="" name="kpi[tasks][][note]" id="milestone_title"\n'+
            'placeholder="title" value=""/>\n'+
    '</div>\n'+
    '<div class="col-md-1 col-sm-12 mb-3">\n'+
        '<button type="button" class="btn btn-outline-danger form-control" kpi-task-id="" onclick="remove_kpi_task(this,\'im\')"><i class="fa fa-trash"></i></button>\n'+
    '</div>\n'+
'</div>';

    $("#tasks_list").append(html);
}
function save_kpi_task(ev){
    var task_title = $(ev).val();
    var note_id = $(ev).attr('mr-note-id');

    task_loading('on');

    var form_data = new FormData();
    form_data.append('note_id', note_id);
    form_data.append('note', task_title);
    form_data.append('_date', $("#_date").val());
    form_data.append("_token", $("#_token").val());
    $.ajax({
        url: base_url + "/Admin/MyKPIs/save_note",
        type: 'post',
        data: form_data,
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
    }).done(function (data) {
        data = JSON.parse(data);
        console.log(data);
        if(data['success'] == 'false'){
            console.log('somthing went wrong...')
        }else{
            $(ev).attr('mr-note-id',data['content']['id']);
            setTimeout(() => {
                task_loading('off');
            }, 350);
        }


    });
}
function task_loading(status){
    if(status == 'on'){
        $("#tasks_list").addClass('filter-blur');
        $("#task_loading").show();
    }else{
        $("#tasks_list").removeClass('filter-blur');
        $("#task_loading").hide();
    }
}
function remove_kpi_task(ev,im)
{
    if(im == "im"){
        var parent = $(ev).parent();
        parent = $(parent).parent();
        $(parent).remove();
        return "";
    }
    task_loading('on');
    var note_id = $(ev).attr('mr-note-id');
    var form_data = new FormData();
    form_data.append('note_id', note_id);

    form_data.append("_token", $("#_token").val());
    $.ajax({
        url: base_url + "/Admin/MyKPIs/remove_note",
        type: 'post',
        data: form_data,
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
    }).done(function (data) {
        data = JSON.parse(data);
        console.log(data);
        if(data['success'] == 'false'){
            console.log('somthing went wrong...');
        }else{
            setTimeout(() => {
                task_loading('off');
                var parent = $(ev).parent();
                parent = $(parent).parent();
                $(parent).remove();
            }, 350);
        }


    });
}


function save_kpi_task_admin(ev,user_id){
    if(im == "im"){
        var parent = $(ev).parent();
        parent = $(parent).parent();
        $(parent).remove();
        return "";
    }
    var task_title = $(ev).val();
    var note_id = $(ev).attr('mr-note-id');

    task_loading('on');


    console.log(user_id);
    var form_data = new FormData();
    form_data.append('note_id', note_id);
    form_data.append('note', task_title);
    form_data.append('_date', $("#_date").val());
    form_data.append("_token", $("#_token").val());
    $.ajax({
        url: base_url + "/Admin/Users/" + user_id + "/Edit_note_value",
        type: 'post',
        data: form_data,
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
    }).done(function (data) {
        data = JSON.parse(data);
        console.log(data);
        if(data['success'] == 'false'){
            console.log('somthing went wrong...')
        }else{
            $(ev).attr('mr-note-id',data['content']['id']);
            setTimeout(() => {
                task_loading('off');
            }, 350);
        }


    });
}

function add_new_kpi_task_admin(ev){
    var user_id = $(ev).attr('mr-user-id');
    var num = $(".task_counter").length;
    var html ='<div class="row task_counter">\n'+
    '<div class="col-md-11 col-sm-12 mb-3">\n'+
        '<input type="text" class="form-control" mr-note-id="" onchange="save_kpi_task_admin(this,'+ user_id +',\'im\')" name="kpi[tasks][][note]" id="milestone_title"\n'+
            'placeholder="title" value=""/>\n'+
    '</div>\n'+
    '<div class="col-md-1 col-sm-12 mb-3">\n'+
        '<button type="button" class="btn btn-outline-danger form-control" kpi-task-id="" onclick="remove_kpi_task_admin(this,'+ user_id +',\'im\')"><i class="fa fa-trash"></i></button>\n'+
    '</div>\n'+
'</div>';

    $("#tasks_list").append(html);
}
function remove_kpi_task_admin(ev,user_id,im)
{
    if(im == "im"){
        var parent = $(ev).parent();
        parent = $(parent).parent();
        $(parent).remove();
        return "";
    }
    task_loading('on');
    var note_id = $(ev).attr('mr-note-id');
    var form_data = new FormData();
    form_data.append('note_id', note_id);

    form_data.append("_token", $("#_token").val());
    $.ajax({
        url: base_url + "/Admin/Users/" + user_id + "/Del_note_value",
        type: 'post',
        data: form_data,
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
    }).done(function (data) {
        data = JSON.parse(data);
        console.log(data);
        if(data['success'] == 'false'){
            console.log('somthing went wrong...');
        }else{
            setTimeout(() => {
                task_loading('off');
                var parent = $(ev).parent();
                parent = $(parent).parent();
                $(parent).remove();
            }, 350);
        }


    });
}


function add_percentage(){
    var num = $(".per_counter").length;
    if($("#special_ser_check").prop('checked')){
        var html ='<div class="row per_counter">\n'+
        '<input type="hidden" name="percentage[][id]" value="">\n'+
        '<div class="col-md-3 col-sm-12 mb-3">\n'+
            '<input type="text" class="form-control" name="percentage['+ num +'][min]"\n'+
                'placeholder="min" value=""/>\n'+
        '</div>\n'+
        '<div class="col-md-3 col-sm-12 mb-3">\n'+
            '<input type="text" class="form-control" name="percentage['+ num +'][max]"\n'+
                'placeholder="max" value="" />\n'+
        '</div>\n'+
        '<div class="col-md-3 col-sm-12 mb-3 last_per" mr-num="'+ num +'">\n'+
            '<input type="text" class="form-control" name="percentage['+ num +'][rate]"\n'+
                'placeholder="rate" value="" />\n'+
        '</div>\n'+
        '<div class="col-md-2 col-sm-12 mb-3 last_per appended_col">\n'+
                '<input type="text" class="form-control" name="percentage['+ num +'][s_rate]" id="percentage_price"\n'+
                    'placeholder="rate" value="" />\n'+
        '</div>'+
        '<div class="col-md-1 col-sm-12 mb-3">\n'+
            '<button type="button" class="btn btn-outline-danger form-control" onclick="remove_milestone(this)"><i class="fa fa-trash"></i></button>\n'+
        '</div>\n'+
    '</div>';
    }else{

        var html ='<div class="row per_counter">\n'+
    '<input type="hidden" name="percentage[][id]" value="">\n'+
    '<div class="col-md-4 col-sm-12 mb-3">\n'+
        '<input type="text" class="form-control" name="percentage['+ num +'][min]"\n'+
            'placeholder="min" value=""/>\n'+
    '</div>\n'+
    '<div class="col-md-4 col-sm-12 mb-3">\n'+
        '<input type="text" class="form-control" name="percentage['+ num +'][max]"\n'+
            'placeholder="max" value="" />\n'+
    '</div>\n'+
    '<div class="col-md-3 col-sm-12 mb-3 last_per" mr-num="'+ num +'">\n'+
        '<input type="text" class="form-control" name="percentage['+ num +'][rate]"\n'+
            'placeholder="rate" value="" />\n'+
    '</div>\n'+
    '<div class="col-md-1 col-sm-12 mb-3">\n'+
        '<button type="button" class="btn btn-outline-danger form-control" onclick="remove_milestone(this)"><i class="fa fa-trash"></i></button>\n'+
    '</div>\n'+
'</div>';
    }
    $("#per_list").append(html);

}
function set_project_enddate_form_url(project_id,abbr,rate = '',time = ''){
    var url = base_url + '/Admin/Projects/' + project_id + "/status/done/set";
    $("#form_go_to_date").attr('action',url);
    $("#end_time_date").val(time);
    $("#end_time_rate").val(rate);
    if(abbr != 'USD'){
        console.log('ya ali');
        $("#dollar_rate_con").show();
    }else{
        console.log('ya ali12');
        $("#dollar_rate_con").hide();
    }
}
function set_exchange(month,ev){
    var title = $(ev).html();
    $("#exchange_title").html(title);
    $("#exchange_month").val(month);

    exchange_loading('on');

    //send request
    var form_data = new FormData();
        form_data.append('month', month);
        form_data.append('year', $("#this_year").val());
        form_data.append("_token", $("#_token").val());
        $.ajax({
            url: base_url + "/Admin/Accounting/Exchange/Rate/Get",
            type: 'post',
            data: form_data,
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
        }).done(function (data) {
            console.log(data);
            data = JSON.parse(data);
            exchange_loading('off');
            $("#rate").val(data['rate'])
        });
}
function exchange_loading(status){
    if(status == 'on'){
        $("#exchange_blur").addClass('filter-blur');
        $(".loading_container").show();
    }else{
        $("#exchange_blur").removeClass('filter-blur');
        $(".loading_container").hide();
    }
}
function special_user_percentage(){
    if($("#special_ser_check").prop('checked')){

        $(".per_counter .col-md-4").addClass('col-md-3');
        $(".per_counter .col-md-4").removeClass('col-md-4');

        $(".last_per").each(function(){
            var num = $(this).attr('mr-num');
            $(this).after('<div class="col-md-2 col-sm-12 mb-3 last_per appended_col">\n'+
            '<input type="text" class="form-control" name="percentage['+ num +'][s_rate]" id="percentage_price"\n'+
                'placeholder="rate" value="" />\n'+
        '</div>');
        });
    }else{
        $(".per_counter .col-md-3").addClass('col-md-4');
        $(".per_counter .col-md-3").removeClass('col-md-3');
        $(".last_per").removeClass('col-md-4');
        $(".last_per").addClass('col-md-3');
        $(".appended_col").remove();
    }
}
function compensation_set_form_url(id){
    $("#compensation_form").attr('action',base_url + '/Admin/Users/' + id + "/compensation/Set");

}


function show_milestone_admin(id){
    milestone_loading('on');
    $.ajax({
        url: base_url + "/Admin/AdminProject/milestones/" + id + "/get",
        type: 'get',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
    }).done(function (data) {
        data = JSON.parse(data);
        create_milestone_table_admin(data['content']);
        milestone_loading('off');
    });
}
function create_milestone_table_admin(content){
    console.log(content);
    html = '';
    for(let x = 0;x < content.length;x++){
        var checked = '';
        data = content[x];
        if(data['status'] == 'done'){
            checked = 'checked';
        }

        html += '<tr>\n'+
        '<td>'+ data['title'] +'</td>\n'+
       ' <td>'+ data['price'] +'</td>\n'+
       ' <td>'+ data['end_time'] +'</td>\n'+
        '<td>\n'+
            '<div class="form-check-primary" style="text-align:center">\n'+
                '<input class="form-check-input" type="checkbox" value="1" onchange="milestone_set_admin('+ data['id'] +','+ data['project_id'] +',this)" '+checked+'/>\n'+
              '</div>\n'+
        '</td>\n'+
    '</tr>';
    }
    $("#milestone_content").html(html);
}
function milestone_set_admin(milestone_id,project_id,ev){
    milestone_loading('on');
    var status = 'proccess';
    if($(ev).prop('checked')){
        status = 'done';
    }
    var form_data = new FormData();
    form_data.append('milestone_id', milestone_id);
    form_data.append('status', status);
    form_data.append("_token", $("#_token").val());
    $.ajax({
        url: base_url + "/Admin/AdminProject/milestones/" + project_id + "/set",
        type: 'post',
        data: form_data,
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
    }).done(function (data) {
        data = JSON.parse(data);
        console.log(data);
        setTimeout(() => {
            milestone_loading('off');
            show_milestone_admin(project_id);
        }, 500);
    });
}

function set_project_enddate_form_url_admin(project_id,abbr,rate = '',time = ''){
    var url = base_url + '/Admin/AdminProject/' + project_id + "/status/done/set";
    $("#form_go_to_date").attr('action',url);
    $("#end_time_date").val(time);
    $("#end_time_rate").val(rate);
    if(abbr != 'USD'){
        $("#dollar_rate_con").show();
    }else{
        console.log('ya ali12');
        $("#dollar_rate_con").hide();
    }
}


function show_percentages_admin(id){
    percentages_loading('on');
    $("#set_percentage_form").attr('action',base_url + "/Admin/AdminProject/percentages/" + id + "/set");
    $.ajax({
        url: base_url + "/Admin/AdminProject/percentages/" + id + "/get",
        type: 'get',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
    }).done(function (data) {
        data = JSON.parse(data);
        create_percentages_table_admin(data['Content']);
        percentages_loading('off');
    });
}
function percentages_loading(status){
    if(status == 'on'){
        $("#percentages_list").addClass('filter-blur');
        $(".loading_container").show();
    }else{
        $("#percentages_list").removeClass('filter-blur');
        $(".loading_container").hide();
    }
}
function create_percentages_table_admin(content){
    console.log(content);
    html = '';
    for(let x = 0;x < content.length;x++){
        data = content[x];

        html += '<tr>\n'+
        '<td>'+ data['min_amount'] +'</td>\n'+
       ' <td>'+ data['max_amount'] +'</td>\n'+
       ' <td><input class="form-control" style="text-align:center;" name="rate['+ data['id'] +']" type="text" value="'+ data['rate'] +'"></td>\n'+
    '</tr>';
    }
    $("#percentages_content").html(html);
}


function cancel_des(ex_des,project_id){
    $("#ex_des").html(ex_des);
    var url = base_url + '/Admin/Projects/' + project_id + "/status/cancel/set";
    $("#form_cancel").attr('action',url);
}
function cancel_des_admin(ex_des,project_id){

    $("#ex_des").html(ex_des);
    var url = base_url + '/Admin/AdminProject/' + project_id + "/status/cancel/set";
    console.log(url);
    $("#form_cancel").attr('action',url);
}
$("#summary_report .my-pagination .page-link").click(function(event){
    event.preventDefault();

    send_pagination_reqest('user',event.target);
});
$("#admin_summary_report .my-pagination .page-link").click(function(event){
    event.preventDefault();

    send_pagination_reqest('admin',event.target);
});
function send_pagination_reqest(user,target){
    

    var href = $(target).attr('href');
    var url = href;
    href = href.split("?");
    href = href[1].split("=");
    console.log(url);
    var form_data = new FormData();
    form_data.append('name', href[0]);
    form_data.append('value', href[1]);
    form_data.append("_token", $("#_token").val());
    Ajax_loading("on","#" + href[0]);
    $.ajax({
        url: url,
        type: 'post',
        data: form_data,
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
    }).done(function (data) {
        console.log(data);
        data = JSON.parse(data);
        Ajax_loading("off","#" + href[0]);
        update_Project_list_in_summary_report(data.Content.data,href[0],target,user);
    });
}
function update_Project_list_in_summary_report(Content,id,target,user){
    var html = '';
    var parent_one = $(target).parent();
    var parent_two = $(parent_one).parent();
    var child = parent_two.children('.active');
    $(child).removeClass('active');
    $(parent_one).addClass('active');
    $(target).attr('href',"|javascript:void(0);");
    var child_a  = $(child).children('a');
    
    $(child_a).attr('href',$("#page_aadr").val() + "?" + id + "=" + $(child_a).html());

    for(let x = 0;x < Content.length;x++){
        var C = Content[x];
        if(user == "admin"){
            var url_project = base_url + "/Admin/AdminProject/" + C.id + "/edit";
        }else{
            var url_project = base_url + "/Admin/Projects/" + C.id + "/edit";
        }
        var title =(C.title.length > 30) ? C.title.substr(0,30)  + ' ...' : C.title;
        var user_id = $("#user_id").val();
        var assigned = (C.from_id == user_id) ? 'style="background:rgba(000,000,000,0.3)"' : '';
        html += '<li class="d-flex mb-4 pb-1" '+ assigned +'>\n'+
        '<div class="me-3">\n'+
            '<img src="'+ base_url + "/uploads/" + C.icon + '" height="25px">\n'+
        '</div>\n'+
        '<div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">\n'+
            '<div class="me-2">\n'+
                '<h6 class="mb-0">\n'+
                    '<a href="'+ C.link +'" target="_blank">\n'+
                    title +
                    '</a>\n'+
                '</h6>\n'+
                '<small class="text-muted d-block">\n'+
                    '<a class="dropdown-item"\n'+
                        'href="'+ url_project +'">\n'+
                        C.employer_name.name +
                    '</a>\n'+
                '</small>\n'+
            '</div>\n'+
            '<div class="user-progress d-flex align-items-center gap-1">\n'+
                '<p class="mb-0 fw-semibold">\n'+
                    C.price_in_dollar + "(" + C.dispaly_symbpl + ")\n" +
                '</p>\n'+
            '</div>\n'+
        '</div>\n'+
    '</li>';
    }
    $("#" + id).html(html);
}

function Ajax_loading(status,target){
    var parent = $(target).parent();
    var loading_target = $(parent).children('.loading_container');
    if(status == 'on'){
        $(target).addClass('filter-blur');
        $(loading_target).show();
    }else{
        $(target).removeClass('filter-blur');
        $(loading_target).hide();
    }
}

function toggle_assign_project_type(ev){
    var val = $(ev).val();
    var parent = $(ev).parent();
    parent = $(parent).parent();
    var pay_ele = $(parent).children('.assign_pay');
    var assign_ele = $(parent).children('.assign_rate');
    var price_ele = $(parent).children('.assign_price');
    if(val == "fixed"){
        $(pay_ele).show(200);
        $(assign_ele).hide(200);
        $(price_ele).val("");
    }else{
        $(pay_ele).hide(200);
        $(assign_ele).show(200);
        $(price_ele).val("-1");
        $(assign_ele).val("40");
    }
}

function check_entire_priceof_assigned_project_checkbox(ev){
    var parent = $(ev).parent();
    parent = $(parent).parent();
    var input_assign = $(parent).children('.input_asssign');
    if($(ev).prop('checked')){
        $(input_assign).val('-1');
    }else{
        $(input_assign).val('');
    }
}

function check_entire_priceof_assigned_project_input(ev){
    var val = $(ev).val();
    var parent = $(ev).parent();
    var child = $(parent).children('.input-group-text');
    var checkbox_ele = $(child).children('input');
    console.log(child);
    if(val == "-1"){
        $(checkbox_ele).prop('checked',true);
    }else{
        $(checkbox_ele).prop('checked',false);
    }
}

function toggle_payment_select(ev){
    var in_val = $(ev).val();
    var all_count = $(".input_select_pay").length;
    var checked_count = $(".input_select_pay").filter(':checked').length;
    if(in_val == "true"){
        if(all_count == checked_count){
            $(".input_select_pay").prop('checked',false);
        }else{
            $(".input_select_pay").prop('checked',true);
            $(ev).prop('checked',true);
        }
    }else{
        if(all_count == checked_count){
            $(".input_select_pay").prop('checked',true);
        }else{
            $(".input_select_pay").prop('checked',false);
        }
    }
}


function toggle_payment_select_fixed_pay(ev){
    var in_val = $(ev).val();
    var all_count = $(".input_select_fixed_pay").length;
    var checked_count = $(".input_select_fixed_pay").filter(':checked').length;
    if(in_val == "true"){
        if(all_count == checked_count){
            $(".input_select_fixed_pay").prop('checked',false);
        }else{
            $(".input_select_fixed_pay").prop('checked',true);
            $(ev).prop('checked',true);
        }
    }else{
        if(all_count == checked_count){
            $(".input_select_fixed_pay").prop('checked',true);
        }else{
            $(".input_select_fixed_pay").prop('checked',false);
        }
    }
}

function confirm_reject_project(url,title,description){
    $("#form_reject").attr("action",url);
    $("#reject_project_title").html(title);
    $("#reject_description").html(description);
}


function upload_profile_avatar() {
    global_upload("avatar","avatar","avatar","yes","one");
}

function upload_profile_cover() {
    global_upload("cover","cover","cover","yes","one");
}

var pay_amount = 0;
var pay_remainder = '';
var pay_endmonth = '';
var global_wallet = '';
var pay_endmonth_switch = false;
function pay_to_user(user_id,fullname,amount,endMonthnAmount,wallet){
    var url = base_url + "/Admin/Accounting/Billing/Pay/"+ user_id +"/Income";
    $("#pay_user").html(fullname);
    $("#pay_amount").val(amount);
    pay_amount = parseFloat(endMonthnAmount);
    pay_remainder = amount;
    pay_endmonth = endMonthnAmount;
    global_wallet = wallet;
    pay_endmonth_switch = false;
    $("#pay_to_user_form").attr('action',url);
    $("#pay_user_wallet_address").val(wallet);
    $("#span_emndMonth_id").html("End M");


    var form_data = new FormData();
    form_data.append('year', GLOBAL_BILLING_YEAR);
    form_data.append('month', GLOBAL_BILLING_MONTH);
    form_data.append('today', GLOBAL_BILLING_TODAY);
    form_data.append("_token", $("#_token").val());
    $.ajax({
        url: base_url + "/Admin/Accounting/Billing/GetProjects/" + user_id,
        type: 'post',
        dataType: 'text',
        data: form_data,
        cache: false,
        contentType: false,
        processData: false,
    }).done(function (data) {
        console.log(data);
        data = JSON.parse(data);
        let projects_today = data['projects_today'];
        let projects_endmonth = data['projects_endmonth'];
        let debits = data['debit_records'];
        let reminader_salary = data['reminader_salary'];

        createBillingPayUserProjects(projects_today,"billingPayUserIncomeToday","billingPayUserProjectsToday","checked","income",reminader_salary);
        createBillingPayUserProjects(projects_endmonth,"billingPayUserIncomeEndmonth","billingPayUserProjectsEndMomth","",'endmonth');
        createBillingPayUserDebits(debits,"billingPayUserDebits","billingPayUserDebitsTable");
    });
}
total_price_amount_billing = 0;
function createBillingPayUserProjects(data,button_id,table_id,checked,name,salary_remaind){
    let html = '';
    let total_price = 0;
    var count = 0
    var s_remander = parseFloat(salary_remaind);
;    data.forEach(function(d){
        let price = d.income;
        let special_price = 0;
        let special_tag = false; // for reduce salary reminder

        price = Math.round(price * 100) / 100;
        console.log(price);
        if(price - s_remander < 0){
            special_price = 0;
            special_tag = true;
            s_remander = s_remander - price;
        }else{
            if(s_remander > 0){
                special_price = price - s_remander;
                special_price = Math.round(special_price * 100) / 100;
                special_tag = true;
                s_remander = 0;
            }else{
                special_price = price;
            }
        }
        total_price += special_price;
        if(d.assigned === true){
            ownership_type = "assigned";
        }else{
            ownership_type = "owner";
        }

        let price_tag = '';
        let disabled = "";
        if(special_tag == true){
            disabled = 'style="display:none"';
            price_tag = "<del>$" + price + "</del><br>$<span>" + special_price + "</span>";
        }else{
            price_tag = "$<span>" + price + "</span>";
        }

        html += '  <tr>\n'+
        '<td>\n'+
            '<input type="hidden" name="'+ name +'['+ count +'][assigned]"\n'+
            'value="'+ d.assigned +'">\n'+
            '<input class="form-check-input" type="checkbox" name="'+ name +'['+ count++ +'][id]"\n'+
            'value="'+ d.id +'" onclick="reCalculteProjectsBillingPatToUser(this,\''+ button_id +'\')" '+ checked +' '+ disabled +'>\n'+
        '</td>\n'+
        '<td>'+ d.id +'</td>\n'+
        '<td>\n'+
            d.title +
        '</td>\n'+
        '<td class="price">\n'+
            price_tag +
        '</td>\n'+
        '<td class="price">\n'+
            ownership_type +
        '</td>\n'+
    '</tr>';
    });
    $("#" + table_id).html(html);
    let splited_number = total_price.toString();
    splited_number = splited_number.split(".");
    splited_number = splited_number[1];
    if(splited_number != undefined && splited_number != "undefined"){
        if(parseInt(splited_number) > 99){
            total_price += 0.01;
        }
    }
    console.log(splited_number);
    if(checked == "checked"){
        total_price = Math.round(total_price * 100) / 100;
    }else{
        $("#endmonth_all").html(total_price);
        total_price = 0;
    }
    $("#" + button_id).html(total_price);
    if(checked == "checked"){
        $("#total_dollar_billing_pay").html(total_price);
        if(salary_remaind != '' && salary_remaind != ""){
            salary_remaind = parseFloat(salary_remaind);
            salary_remaind = Math.round(salary_remaind * 100) / 100;
            $("#total_dollar_billing_REMINDER").html(salary_remaind);
        }
        total_price_amount_billing = total_price;
    }
}
function createBillingPayUserDebits(data,button_id,table_id){
    let html = '';
    let total_remainder = 0;
    data.forEach(function(d){
        let remainder = d.remainder;
        remainder = Math.round(remainder * 100) / 100;
        total_remainder += remainder;
        html += '  <tr>\n'+
        '<td>\n'+
            '<input class="form-check-input" type="checkbox" name="debits[]"\n'+
            'value="'+ d.id +'" onclick="reCalculteProjectsBillingPatToUser(this,\''+ button_id +'\',\'debit\')">\n'+
        '</td>\n'+
        '<td>\n'+
            d.description +
        '</td>\n'+
        '<td>\n'+
            d.year +
        '</td>\n'+
        '<td>\n'+
            d.month +
        '</td>\n'+
        '<td class="price">\n <span>'+
            remainder +
        '</span></td>\n'+
    '</tr>';
    });
    $("#" + table_id).html(html);
    let splited_number = total_remainder.toString();
    splited_number = splited_number.split(".");
    splited_number = splited_number[1];
    if(splited_number != undefined && splited_number != "undefined"){
        if(parseInt(splited_number) > 99){
            total_remainder += 0.01;
        }
    }
    console.log(splited_number);
    $("#debit_all").html(total_remainder);
    total_remainder = 0;
    $("#" + button_id).html(total_remainder);
}
function reCalculteProjectsBillingPatToUser(th,button_id,type="project"){
    let parent = $(th).parent();
    parent = $(parent).parent();
    let price = $(parent).find('.price span').html();
    var total_price = $("#" + button_id).html();

    //parse float
    total_price = parseFloat(total_price);
    price = parseFloat(price);


    if($(th).prop('checked') === true){
        total_price += price;
        if(type != "debit"){
            total_price_amount_billing += price;
        }
    }else{
        total_price -= price;
        if(type != "debit"){
            total_price_amount_billing -= price;
        }
    }
    total_price = Math.round(total_price * 100) / 100;
    if(parseInt(total_price) == 0){
        total_price = 0;
    }
    $("#" + button_id).html(total_price);
    total_price_amount_billing = Math.round(total_price_amount_billing * 100) / 100;
    if(type == "debit"){
        reCalculteProjectsBillingPatToUser_DEBITS(th,price);
    }else{
        $("#total_dollar_billing_pay").html(total_price_amount_billing);
    }
}
function reCalculteProjectsBillingPatToUser_DEBITS(th,price){
    if($(th).prop('checked') === true){
        total_price_amount_billing -= price;
    }else{
        total_price_amount_billing += price;
    }
    $("#total_dollar_billing_pay").html(total_price_amount_billing);

}
function add_endmonth_amount_to_paying_amount(){
    if(pay_endmonth_switch === false){
        pay_endmonth_switch = true;
        $("#pay_amount").val(pay_endmonth);
        $("#span_emndMonth_id").html("Today");
    }else{
        pay_endmonth_switch = false;
        $("#pay_amount").val(pay_remainder);
        $("#span_emndMonth_id").html("End M");
    }
}
function debit_billing(user_id,fullname,amount,debit,salary){
    var url = base_url + "/Admin/Accounting/Billing/Pay/"+ user_id +"/Debit";
    $("#pay_user_debit").html(fullname);
    $("#pay_amount_debit").val(amount);
    $("#billing_salary_pay").val(salary);
    $("#debit_billing_form").attr('action',url);
    $("#pay_debit_amount").html("DEBIT: " + debit);
}

function check_pay_amount(){
    var input_amount = parseInt($("#pay_amount").val());
    if(input_amount > parseInt(pay_amount)){
        $("#pay_btn_billing").attr("disabled",true);
        $("#alert_pay_amnount").show(100);
    }else{
        $("#pay_btn_billing").attr("disabled",false);
        $("#alert_pay_amnount").hide(100);
    }
}

function copy_wallet_address(){
    navigator.clipboard.writeText(global_wallet);
    $("#copied_success").show();
    setTimeout(function(){
        $("#copied_success").hide();
    },2000);
}

function call_queue(en,category_id){
    var checked = $(en).prop('checked');
    console.log(checked);

    call_queue_loading('on');

    var form_data = new FormData();
    form_data.append('checked', checked);
    form_data.append('category_id', category_id);
    form_data.append("_token", $("#_token").val());

    $.ajax({
        url: base_url + "/Admin/ToogleQueue",
        type: 'post',
        dataType: 'text',
        data: form_data,
        cache: false,
        contentType: false,
        processData: false,
    }).done(function (data) {
        call_queue_loading('off');
        data = JSON.parse(data);
        $("#alert_message_call_queue").show(100);
        setTimeout(function(){
            $("#alert_message_call_queue").hide(100);
        },2000);
        $("#alert_message_call_queue").removeClass("alert-success");
        $("#alert_message_call_queue").removeClass("alert-primary");
        $("#alert_message_call_queue").removeClass("alert-warning");

        $("#alert_message_call_queue").addClass("alert-" + data['status']);

        $("#alert_message_call_queue").html(data['message']);

    });
}

function call_queue_loading(status){
    if(status == 'on'){
        $(".call_queue_blur").addClass('filter-blur');
        $("#loading_call_queue").show();
    }else{
        $(".call_queue_blur").removeClass('filter-blur');
        $("#loading_call_queue").hide();
    }
}

function add_account_category(){
    var num  = $('.account_category_list').length;
    html = '<div class="row account_category_list">\n'+
    '<input type="hidden" name="category['+ num +'][id]" value="">\n'+
    '<div class="col-md-11">\n'+
        '<input type="text" class="form-control" placeholder="title" name="category['+ num +'][title]">\n'+
    '</div>\n'+
    '<div class="col-md-1 col-sm-12 mb-3">\n'+
        '<button type="button" class="btn btn-outline-danger form-control" onclick="remove_milestone(this)"><i class="fa fa-trash"></i></button>\n'+
    '</div>\n'+
        '</div>';

    $("#account_category_list").append(html);
}

function add_tags_category(en){
    if(en.key == "Enter" || en.type == "click"){
        en.preventDefault();
        var categories = $("#tags_input_category").val();
        var cat_array = categories.split(",");
        cat_array.forEach(function(tag){
            var html = '<a class="mx-1 hover_tag" onclick="remove_this_element(this)"><input type="hidden" value="'+ tag +'" name="tags[]">'+ tag +'</a>';

            $("#category_tags").append(html);
        });
    }

    
}
function remove_this_element(ele){
    $(ele).remove();
}
function call_logs_loading(status){
    if(status == 'on'){
        $(".call_logs_blur").addClass('filter-blur');
        $("#loading_call_logs").show();
    }else{
        $(".call_logs_blur").removeClass('filter-blur');
        $("#loading_call_logs").hide();
    }
}
function call_spam_init(username,spam_id){
    $("#spam_call_username").html(username);
    $("#spam_call_id").val(spam_id);
}

function Add_Wallet_User(){
    var count = $(".wallet_count").length;
    checked = '';
    if(count == 0){
        checked = "checked";
    }
    var html = '<div class="col-md-12 mb-4 wallet_count">\n'+
    '<div class="input-group">\n'+
        '<input type="text" class="form-control" placeholder="USDT TRC20 Wallet address" name="wallet['+ count +']" aria-label="USDT TRC20 Wallet address">\n'+
        '<div class="input-group-text">\n'+
            '<input class="form-check-input mt-0" '+ checked +' type="radio" value="'+ count +'" name="active_wallet"  aria-label="active wallet">\n'+
        '</div>\n'+
        '<button class="btn btn-outline-warning waves-effect" type="button"  onclick="remove_milestone(this)">Remove</button>\n'+
    '</div>\n'+
    '</div>';

    $("#wallet_users").append(html);
}
function pay_btn_debit(fullname,amount,user_id,fill_amount){
   $("#pay_debit_user").html(fullname);
   $("#pay_debit_amount").html("$" + amount);
   $("#pay_debit_form_url").attr('action',base_url + "/Admin/Debits/"+ user_id +"/settle_up");
   $("#modal_amount_pay").val(fill_amount);
}

function change_category_init(username,id){
    $("#change_call_username").html(username);
    $("#change_call_id").val(id);
}

function change_element_attr(selector,attr,value){
    $(selector).attr(attr,value);
}
remove_ele = '';
function queue_drag_start(el){
    remove_ele = el;
    $("#trush_div").show();
}

function queue_drag_end(el){
    $("#trush_div").hide();
}
function drop_queue(ev){
    $(remove_ele).remove();
}
$("#trush_div").on('drop dragdrop',drop_queue);
$("#trush_div").on('dragenter',function(event){
    event.preventDefault();
});
$("#trush_div").on('dragover',function(event){
    event.preventDefault();
});
var delete_user_from_the_queue_element = '';
function delete_queue(element,fullname){
    $("#delete_queue_text").html("Do you want to delete <b>" + fullname + "</b> from the queue?");
    delete_user_from_the_queue_element = element;
}

function delete_user_from_the_queue(){
    var parent = $(delete_user_from_the_queue_element).parent();
    parent = $(parent).parent();
    parent = $(parent).parent();
    $(parent).remove();
}

function penalty_management(priority,fullname,queue_id){
    $("#penalty_management_text").html(fullname);
    $("#penalty_management_from").attr('action',base_url + '/Admin/Call_Queue_List/' + queue_id + "/Penaly/Set");

    if(priority > 100000){
        var penalty = priority -= 100000;
    }else{
        var penalty = priority = 0;
    }
    $("#penalty_management_input").val(penalty);
}

function editBoards(title,description,board_id,selected_members){
    console.log(description);

    $("#description").html(description);
    $("#kanban_title").val(title);
    $("#addNewBoardForm").attr('action',base_url + "/Admin/Kanbans/Boards/" + board_id);
    $('.board_members').find("#Members").val(selected_members).trigger('change');

}

function loading_status(status){
    if(status == 'on'){
        $(".loading_blur").addClass('filter-blur');
        $(".loading_container").show();
    }else{
        $(".loading_blur").removeClass('filter-blur');
        $(".loading_container").hide();
    }
}

function checkForCorrectHour(th,max){
    var val = $(th).val();
    if(!$.isNumeric(val)){
        $(th).val(0);
    }
    if(val > max){
        $(th).val(max);
    }
}

function chnage_swich_check_status(th,target_input_hidden_id){
    if($(th).prop('checked')){
        $(target_input_hidden_id).val(10);
    }else{
        $(target_input_hidden_id).val(0);
    }
}

function card_title_selected(ev){
    var val = $(ev.target).val();
    console.log(val);
}

function archive_card(url,title,type){
    $("#archive_card_form").attr('action', url);
    $("#archive_card_message").html(title);
    $("#archive_card_type").html(type);
    $("#archive_card_submit").html(type);
}
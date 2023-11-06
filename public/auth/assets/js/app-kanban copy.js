/**
 * App Kanban
 */

'use strict';

(async function () {
  
  let boards;
  const kanbanSidebar = document.querySelector('.kanban-update-item-sidebar'),
    kanbanWrapper = document.querySelector('.kanban-wrapper'),
    commentEditor = document.querySelector('.comment-editor'),
    kanbanAddNewBoard = document.querySelector('.kanban-add-new-board'),
    kanbanAddNewInput = [].slice.call(document.querySelectorAll('.kanban-add-board-input')),
    kanbanAddBoardBtn = document.querySelector('.kanban-add-board-btn'),
    datePicker = document.querySelector('#due-date'),
    select2 = $('.select2') // ! Using jquery vars due to select2 jQuery dependency
    assetsPath = document.querySelector('html').getAttribute('data-assets-path');
  // Init kanban Offcanvas
  const kanbanOffcanvas = new bootstrap.Offcanvas(kanbanSidebar);

  const kanbanResponse = await fetch(base_url + "/Admin/Kanbans/Boards/"+ board_id +"/Lists");
  if (!kanbanResponse.ok) {
    console.error('error', kanbanResponse);
  }
  boards = await kanbanResponse.json();

  // datepicker init
  if (datePicker) {
    datePicker.flatpickr({
      monthSelectorType: 'static',
      altInput: true,
      altFormat: 'j F, Y',
      dateFormat: 'Y-m-d',
      defaultDate:'null'
    });
  }
  

  //! TODO: Update Event label and guest code to JS once select removes jQuery dependency
  // select2
  if (select2.length) {
    function renderLabels(option) {
      if (!option.id) {
        return option.text;
      }
      var $badge = "<div class='badge " + $(option.element).data('color') + " rounded-pill'> " + option.text + '</div>';
      return $badge;
    }

    select2.each(function () {
      var $this = $(this);
      $this.wrap("<div class='position-relative'></div>").select2({
        placeholder: 'Select Label',
        dropdownParent: $this.parent(),
        templateResult: renderLabels,
        templateSelection: renderLabels,
        escapeMarkup: function (es) {
          return es;
        }
      });
    });
  }
  // Comment editor
  if (commentEditor) {
    new Quill(commentEditor, {
      modules: {
        toolbar: '.comment-toolbar'
      },
      placeholder: 'Write a Comment... ',
      theme: 'snow'
    });
  }
  console.log('run');

  // Render board dropdown
  function renderBoardDropdown() {
    return (
      "<div class='dropdown'>" +
      "<i class='dropdown-toggle ti ti-dots-vertical cursor-pointer' id='board-dropdown' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'></i>" +
      "<div class='dropdown-menu dropdown-menu-end' aria-labelledby='board-dropdown'>" +
      "<a class='dropdown-item delete-board' href='javascript:void(0)'> <i class='ti ti-trash ti-xs' me-1></i> <span class='align-middle'>Delete</span></a>" +
      "<a class='dropdown-item' href='javascript:void(0)'><i class='ti ti-edit ti-xs' me-1></i> <span class='align-middle'>Rename</span></a>" +
      "<a class='dropdown-item' href='javascript:void(0)'><i class='ti ti-archive ti-xs' me-1></i> <span class='align-middle'>Archive</span></a>" +
      '</div>' +
      '</div>'
    );
  }
  // Render item dropdown
  function renderDropdown() {
    return (
      "<div class='dropdown kanban-tasks-item-dropdown'>" +
      "<i class='dropdown-toggle ti ti-dots-vertical' id='kanban-tasks-item-dropdown' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'></i>" +
      "<div class='dropdown-menu dropdown-menu-end' aria-labelledby='kanban-tasks-item-dropdown'>" +
      "<a class='dropdown-item' href='javascript:void(0)'>Copy task link</a>" +
      "<a class='dropdown-item' href='javascript:void(0)'>Duplicate task</a>" +
      "<a class='dropdown-item delete-task' href='javascript:void(0)'>Delete</a>" +
      '</div>' +
      '</div>'
    );
  }
  // Render header
  function renderHeader(color, text) {
    return (
      "<div class='d-flex justify-content-between flex-wrap align-items-center mb-2 pb-1'>" +
      "<div class='item-badges'> " +
      "<div class='badge bg-label-" +
      color +
      "'> " +
      text +
      '</div>' +
      '</div>' +
      // renderDropdown() +
      '</div>'
    );
  }

  // Render avatar
  function renderAvatar(images, pullUp, size, margin, members) {
    var $transition = pullUp ? ' pull-up' : '',
      $size = size ? 'avatar-' + size + '' : '',
      member = members == undefined ? ' ' : members.split(',');

    return images == undefined
      ? ' '
      : images
          .split(',')
          .map(function (img, index, arr) {
            if(img == undefined){
              return "";
            }
            var $margin = margin && index !== arr.length - 1 ? ' me-' + margin + '' : '';
            var img_tag = '';
            console.log('img');
            img = img.split('-');
            console.log(img);

            if(img[0] == "text"){
              img_tag = '<span class="avatar-initial bg-label-success rounded-circle mt-1" '+ $transition +'>'+ img[1] +'</span>';
            }else{
              img_tag = '<img src="'+ img[1] +'" alt="Avatar" class="rounded-circle mt-1" '+ $transition +'  />';
            }

            return (
              "<div class='avatar " +
              $size +
              $margin +
              "'" +
              "data-bs-toggle='tooltip' data-bs-placement='top'" +
              "title='" +
              member[index] +
              "'" +
              '>' +
              '<div class="avatar me-2 flex-shrink-0">\n'+
              img_tag +
              '</div>\n'+
              '</div>'
            );
          })
          .join(' ');
  }

  // Render footer
  function renderFooter(attachments, comments, assigned, members) {
    return (
      "<div class='d-flex justify-content-between align-items-center flex-wrap mt-2 pt-1'>" +
      "<div class='d-flex'> <span class='d-flex align-items-center me-2'><i class='ti ti-paperclip ti-xs me-1'></i>" +
      "<span class='attachments'>" +
      attachments +
      '</span>' +
      "</span> <span class='d-flex align-items-center ms-1'><i class='ti ti-message-dots ti-xs me-1'></i>" +
      '<span> ' +
      comments +
      ' </span>' +
      '</span></div>' +
      "<div class='avatar-group d-flex align-items-center assigned-avatar'>" +
      renderAvatar(assigned, true, 'xs', null, members) +
      '</div>' +
      '</div>'
    );
  }

  function renderCardActivities(card_id){
    $.ajax({
      url: base_url + "/Admin/Kanbans/getCardsActivities/" + card_id,
      type: 'get',
      dataType: 'text',
      cache: false,
      contentType: false,
      processData: false,
    }).done(function (data) {
        console.log(data);
        data = JSON.parse(data);
        data = data['Content'];
        var profile_tag = '';
      
        var html = '';
        data.forEach(function(e){
          if(e.profile_image['type'] == "text"){
            profile_tag = '<span class="avatar-initial bg-label-success rounded-circle mt-1">'+ e.profile_image['text'] +'</span>';
          }else{
            profile_tag = '<img src="'+ e.profile_image['image'] +'" alt="Avatar" class="rounded-circle mt-1" />';
          }
          html += '<div class="media mb-4 d-flex align-items-start">\n'+
          '<div class="avatar me-2 flex-shrink-0">\n'+
              profile_tag +
          '</div>\n'+
          '<div class="media-body">\n'+
            '<p class="mb-0"><span class="fw-semibold">'+ e.fullname +'</span>'+ e.description +'</p>\n'+
            '<small class="text-muted">'+ e.jalali_time +'</small>\n'+
          '</div>\n'+
        '</div>';
        });
        $("#tab-activity").html(html);
    });
  }
  function handelDropElEvent(target,source,siblings){
    var item = $(target).attr('data-eid');
    var item_id = item.split('-')[1];

    source = $(source).parent();
    var list = $(source).attr('data-id');
    var list_id = list.split("-")[1];
    console.log(item_id);
    console.log(list_id);

    var form_data = new FormData();
    form_data.append("_token", $("#_token").val());
    $.ajax({
        url: base_url + "/Admin/Kanbans/Boards/"+ board_id +"/List/"+ list_id +"/Card/"+ item_id +"/Move",
        type: 'post',
        data: form_data,
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
    }).done(function (data) {
        console.log(data);
    });
  }
  // Init kanban
  const kanban = new jKanban({
    element: '.kanban-wrapper',
    gutter: '15px',
    widthBoard: '250px',
    dragItems: true,
    boards: boards,
    dragBoards: true,
    addItemButton: true,
    buttonContent: '+ Add Item',
    itemAddOptions: {
      enabled: true, // add a button to board for easy item creation
      content: '+ Add New Item', // text or html content of the board button
      class: 'kanban-title-button btn', // default class of the button
      footer: false // position the button on footer
    },
    dropEl: handelDropElEvent,
    click: function (el) {
      let card_id = el.getAttribute('data-eid');
      card_id = card_id.split("-")[1];
      $("#cardEditForm").attr('action',base_url + "/Admin/Kanbans/Boards/Card/" + card_id);
      let element = el;
      $('.kanban-update-item-sidebar').find("#card_label").val(null).trigger('change');
      $('.kanban-update-item-sidebar').find("#card_Assign").val(null).trigger('change');
      $("#upload_result_atachments").html("");
      let title = element.getAttribute('data-eid')
          ? element.querySelector('.kanban-text').textContent
          : element.textContent;
        
      let date = '';
      if(element.getAttribute('data-due-date') !== undefined){
        date = element.getAttribute('data-due-date');
      }
      let label = "";
      if(element.getAttribute('data-badge') !== undefined){
        label = element.getAttribute('data-badge');
      }
      let assigned = "";
      if(element.getAttribute('data-members_id') !== undefined){
        assigned = element.getAttribute('data-members_id');
      }
      let files = "";
      if(element.getAttribute('data-files') !== undefined){
        files = element.getAttribute('data-files');
      }
      let dateObj = new Date();
      let year = dateObj.getFullYear();
      let dateToUse = date
          ? date
          : dateObj.getDate() + ' ' + dateObj.toLocaleString('en', { month: 'long' });
      let avatars = element.getAttribute('data-assigned');
      
      // Show kanban offcanvas
      kanbanOffcanvas.show();
      if(files != null){
        files = files.split(",");
      }

      if(assigned != null){
        assigned = assigned.split(",");
      }
      // To get data on sidebar
      kanbanSidebar.querySelector('#card_title').value = title;
      kanbanSidebar.querySelector('#due-date').nextSibling.value = date;
      kanbanSidebar.querySelector('#card_Description').innerHTML = (element.getAttribute('data-description') != 'null') ? element.getAttribute('data-description') : "";
      // ! Using jQuery method to get sidebar due to select2 dependency
 
      renderCardActivities(card_id);
      $('.kanban-update-item-sidebar').find("#card_label").val(label).trigger('change');
      $('.kanban-update-item-sidebar').find("#card_Assign").val(assigned).trigger('change');

      //insert atachments
      if(files != ''){
        files.forEach(function(file){
          console.log(file);
          insert_file("atachments","atachments","atachments",file,"multiple","true");
        });
      }
      // Remove & Update assigned
      kanbanSidebar.querySelector('.assigned').innerHTML = '';
      kanbanSidebar
        .querySelector('.assigned')
        .insertAdjacentHTML(
          'afterbegin',
          renderAvatar(avatars, false, 'xs', '1', el.getAttribute('data-members')) +
            "<div class='avatar avatar-xs ms-1'>" +
            "<span class='avatar-initial rounded-circle bg-label-secondary'><i class='ti ti-plus ti-xs text-heading'></i></span>" +
            '</div>'
        );
    },

    buttonClick: function (el, boardId) {
      const addNew = document.createElement('form');
      addNew.setAttribute('class', 'new-item-form');
      addNew.innerHTML =
        '<div class="mb-3">' +
        '<textarea class="form-control add-new-item" rows="2" placeholder="Add Content" autofocus required></textarea>' +
        '</div>' +
        '<div class="mb-3">' +
        '<button type="submit" class="btn btn-primary btn-sm me-2">Add</button>' +
        '<button type="button" class="btn btn-label-secondary btn-sm cancel-add-item">Cancel</button>' +
        '</div>';
      kanban.addForm(boardId, addNew);

      addNew.addEventListener('submit', function (e) {
        e.preventDefault();
        let list_id = boardId.split("-")[1];
    
        var title = e.target[0].value;
        var form_data = new FormData();
        form_data.append('title', title);
        form_data.append("_token", $("#_token").val());
        console.log(board_id);
        console.log('title');

        $.ajax({
            url: base_url + "/Admin/Kanbans/Boards/"+ board_id +"/List/"+ list_id +"/Card",
            type: 'post',
            data: form_data,
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
        }).done(function (data) {
            console.log(data);
        });
        const currentBoard = [].slice.call(
          document.querySelectorAll('.kanban-board[data-id=' + boardId + '] .kanban-item')
        );
        kanban.addElement(boardId, {
          title: "<span class='kanban-text'>" + e.target[0].value + '</span>',
          id: boardId + '-' + currentBoard.length + 1
        });

        // add dropdown in new boards
        const kanbanText = [].slice.call(
          document.querySelectorAll('.kanban-board[data-id=' + boardId + '] .kanban-text')
        );
        kanbanText.forEach(function (e) {
          e.insertAdjacentHTML('beforebegin', renderDropdown());
        });

        // prevent sidebar to open onclick dropdown buttons of new tasks
        const newTaskDropdown = [].slice.call(document.querySelectorAll('.kanban-item .kanban-tasks-item-dropdown'));
        if (newTaskDropdown) {
          newTaskDropdown.forEach(function (e) {
            e.addEventListener('click', function (el) {
              el.stopPropagation();
            });
          });
        }

        // delete tasks for new boards
        const deleteTask = [].slice.call(
          document.querySelectorAll('.kanban-board[data-id=' + boardId + '] .delete-task')
        );
        deleteTask.forEach(function (e) {
          e.addEventListener('click', function () {
            const id = this.closest('.kanban-item').getAttribute('data-eid');
            kanban.removeElement(id);
          });
        });
        addNew.remove();
      });

      // Remove form on clicking cancel button
      addNew.querySelector('.cancel-add-item').addEventListener('click', function (e) {
        addNew.remove();
      });
    }
  });

  // Kanban Wrapper scrollbar
  if (kanbanWrapper) {
    new PerfectScrollbar(kanbanWrapper);
  }

  const kanbanContainer = document.querySelector('.kanban-container'),
    kanbanTitleBoard = [].slice.call(document.querySelectorAll('.kanban-title-board')),
    kanbanItem = [].slice.call(document.querySelectorAll('.kanban-item'));

  // Render custom items
  if (kanbanItem) {
    kanbanItem.forEach(function (el) {
      let element = "<span class='kanban-text'>" + el.textContent + '</span>';
      let description = '';
      if(el.getAttribute('data-description') != null && el.getAttribute('data-description') != 'null' ){
        description = "<p class='kanban-text'>" + el.getAttribute('data-description') + '</p>';
      }
      let img = '';
      if (el.getAttribute('data-image') !== null) {
        img = "<img class='img-fluid rounded mb-2' src='" + el.getAttribute('data-image') + "'>";
      }
      el.textContent = '';
      let badge = '';
      let badge_text = '';
      if (el.getAttribute('data-badge') !== null) {
        badge = el.getAttribute('data-badge');
      }
        el.insertAdjacentHTML(
          'afterbegin',
          renderHeader(badge, element) + img + description
        );

     
      if (
        el.getAttribute('data-comments') !== undefined ||
        el.getAttribute('data-due-date') !== undefined ||
        el.getAttribute('data-assigned') !== undefined
      ) {
        el.insertAdjacentHTML(
          'beforeend',
          renderFooter(
            el.getAttribute('data-attachments'),
            el.getAttribute('data-comments'),
            el.getAttribute('data-assigned'),
            el.getAttribute('data-members')
          )
        );
      }
    });
  }

  // To initialize tooltips for rendered items
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // prevent sidebar to open onclick dropdown buttons of tasks
  const tasksItemDropdown = [].slice.call(document.querySelectorAll('.kanban-tasks-item-dropdown'));
  if (tasksItemDropdown) {
    tasksItemDropdown.forEach(function (e) {
      e.addEventListener('click', function (el) {
        el.stopPropagation();
      });
    });
  }

  // Toggle add new input and actions add-new-btn
  if (kanbanAddBoardBtn) {
    console.log("exist");
    kanbanAddBoardBtn.addEventListener('click', () => {
      kanbanAddNewInput.forEach(el => {
        el.value = '';
        el.classList.toggle('d-none');
      });
    });
  }

  // Render add new inline with boards
  if (kanbanContainer) {
    kanbanContainer.appendChild(kanbanAddNewBoard);
  }

  // Makes kanban title editable for rendered boards
  if (kanbanTitleBoard) {
    kanbanTitleBoard.forEach(function (elem) {
      elem.addEventListener('mouseenter', function () {
        this.contentEditable = 'true';
      });

      // Appends delete icon with title
      elem.insertAdjacentHTML('afterend', renderBoardDropdown());
    });
  }

  // To delete Board for rendered boards
  const deleteBoards = [].slice.call(document.querySelectorAll('.delete-board'));
  if (deleteBoards) {
    deleteBoards.forEach(function (elem) {
      elem.addEventListener('click', function () {
        const id = this.closest('.kanban-board').getAttribute('data-id');
        kanban.removeBoard(id);
      });
    });
  }

  // Delete task for rendered boards
  const deleteTask = [].slice.call(document.querySelectorAll('.delete-task'));
  if (deleteTask) {
    deleteTask.forEach(function (e) {
      e.addEventListener('click', function () {
        const id = this.closest('.kanban-item').getAttribute('data-eid');
        kanban.removeElement(id);
      });
    });
  }

  // Cancel btn add new input
  const cancelAddNew = document.querySelector('.kanban-add-board-cancel-btn');
  if (cancelAddNew) {
    cancelAddNew.addEventListener('click', function () {
      kanbanAddNewInput.forEach(el => {
        el.classList.toggle('d-none');
      });
    });
  }

  // Add new board
  if (kanbanAddNewBoard) {
    kanbanAddNewBoard.addEventListener('submit', function (e) {
      e.preventDefault();
      const thisEle = this,
        value = thisEle.querySelector('.form-control').value,
        id = value.replace(/\s+/g, '-').toLowerCase();
      kanban.addBoards([
        {
          id: id,
          title: value
        }
      ]);

      // Adds delete board option to new board, delete new boards & updates data-order
      const kanbanBoardLastChild = document.querySelectorAll('.kanban-board:last-child')[0];
      if (kanbanBoardLastChild) {
        const header = kanbanBoardLastChild.querySelector('.kanban-title-board');
        header.insertAdjacentHTML('afterend', renderBoardDropdown());

        // To make newly added boards title editable
        kanbanBoardLastChild.querySelector('.kanban-title-board').addEventListener('mouseenter', function () {
          this.contentEditable = 'true';
        });
      }

      // Add delete event to delete newly added boards
      const deleteNewBoards = kanbanBoardLastChild.querySelector('.delete-board');
      if (deleteNewBoards) {
        deleteNewBoards.addEventListener('click', function () {
          const id = this.closest('.kanban-board').getAttribute('data-id');
          kanban.removeBoard(id);
        });
      }

      // Remove current append new add new form
      if (kanbanAddNewInput) {
        kanbanAddNewInput.forEach(el => {
          el.classList.add('d-none');
        });
      }

      // To place inline add new btn after clicking add btn
      if (kanbanContainer) {
        kanbanContainer.appendChild(kanbanAddNewBoard);
      }
    });
  }

  // Clear comment editor on close
  kanbanSidebar.addEventListener('hidden.bs.offcanvas', function () {
    kanbanSidebar.querySelector('.ql-editor').firstElementChild.innerHTML = '';
  });

  // Re-init tooltip when offcanvas opens(Bootstrap bug)
  if (kanbanSidebar) {
    kanbanSidebar.addEventListener('shown.bs.offcanvas', function () {
      const tooltipTriggerList = [].slice.call(kanbanSidebar.querySelectorAll('[data-bs-toggle="tooltip"]'));
      tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });
    });
  }
})();

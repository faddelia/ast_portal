var currentStatusCount = 0;
var rowCount           = 0;
var add                = false;
var edit               = false;
var editAssignedTo     = false;
var sortedBy           = '';

$(document).ready(function(){

  if($('#respond-to').length > 0) {
    $('#viewing-product-file').val($('#respond-to').text());
    $('#viewing-data-file').val($('#respond-to').text());
  }  

  $('#product-upload').click(function(){
    $('#products').append("<li class=\"list-group-item\"><a href=\"#\">New Product File Here</a></li>");
  });

  $('#data-file-upload').click(function(){
    $('#test-data-files').append("<li class=\"list-group-item\"><a href=\"#\">New Test Data File Here</a></li>");
  });

  $('.list-group-item').click(function(){
    $(this).addClass('list-group-item-info');
  });

  $('.list-group-item').mouseover(function(){
    $(this).addClass('list-group-item-info');
  });

  $('.list-group-item').mouseout(function(){
    $(this).removeClass('list-group-item-info');
  });

  $('#left-nav a').each(function(){
    $(this).removeClass('active');

    var href = $(this).attr('href');

    if(href.indexOf('#') != -1) {
      $(this).click(function(){
        $('#left-nav a').each(function(){
          $(this).removeClass('active');
        });

        $(this).addClass('active');
      });
    }
    else {
      var url = decodeURIComponent(window.location.href);
      
      if(url.indexOf($(this).attr('href')) != -1) {
        $(this).addClass('active');
      }
    }
  });

  $('.action-nav').each(function(){
    //remove any icons from sort options to start over
    $(this).html($(this).text());
  });

  // figure out if a sort option has been clicked
  var url = decodeURIComponent(window.location.href);
  var sortdirection = undefined;

  // gets parameters from URL to see which column to sort by, if one is chosen
  if(url.indexOf('?') != -1) {
    url = url.split('?');
    //second half of url contains parameters
    url = url[1].split('&');

    sortedBy = url[0].split('sortby=');
    sortedBy = sortedBy[1];

    var sortdirection = url[1].split('sortdirection=');
    sortdirection = sortdirection[1];
  }

  // toggles ascending/descending order and adds chevron icon to show the user what column is being sorted and in which direction
  if(sortdirection !== undefined) {

    if(sortdirection == 'desc') {
      // change icon to chevron-up
      $('#sortby-' + sortedBy).append('<span class="span-sortby" data-feather="chevron-down">');

      //force feather replace (change data-feather to icon)
      feather.replace();

      // change url to asc
      var href = $('#sortby-' + sortedBy).attr('href');
      href = href.replace('desc', 'asc');
      $('#sortby-' + sortedBy).attr('href', href);
    }

    if(sortdirection == 'asc') {
      // change icon to chevron-down
      $('#sortby-' + sortedBy).append('<span class="span-sortby" data-feather="chevron-up">');
        
      //force feather replace (change data-feather to icon)
      feather.replace();

      // change url to desc
      var href = $('#sortby-' + sortedBy).attr('href');
      href = href.replace('asc', 'desc');
      $('#sortby-' + sortedBy).attr('href', href);
    }

  }

  $('.archive').each(function(){
    $(this).click(function(){

      if($(this).is(':checked')) {
        //get action log id from id
        var actionLogId = $(this).attr('id').split('-')[1];

        //ajax request to archive this record
        jQuery.ajaxSetup({ 
          headers: { 
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') 
          }
        });

        $.ajax({
          url: "/archive",
          method: 'post',
          data: {
            id: actionLogId
          },
          success: function(result){
            //alert("success: " + result.success);
          },
          error: function(result) {
            //alert("error: " + result.error);
          }
        });

        //remove row from table
        $(this).closest('#action-log-' + actionLogId).remove();
      }

    });
  });

  $('.delete').each(function(){
    $(this).click(function(){

      if($(this).is(':checked')) {

        //get action log id from id
        var deleteActionLogId = $(this).attr('id').split('-')[1];

        //ajax request to archive this record
        jQuery.ajaxSetup({ 
          headers: { 
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') 
          }
        });

        $.ajax({
          url: "/delete",
          method: 'post',
          data: {
            id: deleteActionLogId
          },
          success: function(result){
            //alert("success: " + result.success);
          },
          error: function(result) {
            //alert("error: " + result.error);
          }
        });

        //remove row from table
        $(this).closest('#action-log-' + deleteActionLogId).remove();
      }

    });
  });

  $('.assigned-to').click(function(){

    if(!editAssignedTo) {
      var text = $(this).text();
      $(this).html('<input type="text" id="edit-assigned-to" name="assigned-to" value="' + text + '">');
      editAssignedTo = true;
    }

    $('#edit-assigned-to').focus(function(){
      $(this).keypress(function(e){
        if(e.which == 13) {
          e.preventDefault();
          $(this).trigger('focusout');
        }
      });
    });

  });

  $('.assigned-to').focusout(function(){
    var text = $('#edit-assigned-to').val();
    var lookupId = $(this).attr('id');

    $(this).html(text);
    editAssignedTo = false;

    jQuery.ajaxSetup({ 
      headers: { 
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') 
      }
    });

    $.ajax({
      url: "/assign-to",
      method: 'post',
      data: {
        lookup_id: lookupId,
        assigned_to: text
      },
      success: function(result){
        //alert("success: " + result.success + "\nrequest: " + result.request + "\nlookup_id: " + result.lookup_id);
        //success
      }
    });

  });

  /*
  $('#quickLook .edit').click(function(){
    $(this).closest('div').find('ul').append('<li><input id="cs-' + currentStatusCount + '" class="form-control form-control-sm" type="text"></li>');
    $('#cs-' + currentStatusCount).focus(function(){
      $(this).keypress(function(event){
        if(event.which == 13) {
          event.preventDefault();
          $(this).trigger('blur');
        }
      });

      $(this).blur(function(event){
        var status = $(this).val();
        $(this).closest('li').remove();
        
        event.preventDefault();
        
        jQuery.ajaxSetup({ 
          headers: { 
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') 
          }
        });
        console.log(status);

        $.ajax({
          url: "/current-status",
          method: 'post',
          data: {
            status: status
          },
          success: function(result){
            console.log(result);
            $('#status ul li').each(function(){
              $(this).remove();
            });
            for(var i = 0; i < result.statuses.length; i++)
            {
               //console.log(result.statuses[i].current_status);
               $('#status ul').append('<li>' + result.statuses[i].current_status + '</li>');
            }
          },
          error: function(result) {
            console.log(result);
          }
        });
      });
    });
    currentStatusCount++;
  });
  */

  $('#action-log-add').click(function(){

    if(add) {
      return;
    }

    var companyName = $('#company-name').text();
    companyName = companyName.split('-');
    var name = $('#welcome').text();
    name = name.split(',');

    $(this).closest('div').find('tbody').append(
      '<tr>' +
      '<td><input id="date" class="form-control form-control-sm" type="text" placeholder="MM/DD/YYYY" required></td>' +
      '<td><input id="company" class="form-control form-control-sm" type="text" value="' + (companyName[companyName.length - 1].trim()) + '" required readonly></td>' +
      '<td><input id="name" class="form-control form-control-sm" type="text" value="' + (name[name.length - 1].trim() ) + '" required readonly></td>' +
      //'<td><input id="communication-type" class="form-control form-control-sm" type="text" required></td>' + 
      '<td><select id="communication-type" name="communication-type" class="custom-select mr-sm-2"><option value="not-selected" selected>Choose...</option><option value="phone">Phone</option><option value="email">Email</option><option value="in-person">In Person</option><option value="fax">Fax</option><option value="customer-site">Customer Site</option></select></td>' + 
      '<td><input id="contact" class="form-control form-control-sm" type="text" required></td>' +
      '<td><input id="current-status" class="form-control form-control-sm" type="text" required></td>' +
      //'<td><textarea id="current-status" class="form-control form-control-sm"></textarea></td>' +
      '<td><input id="action-item" class="form-control form-control-sm" type="text" required></td>' +
      '</tr>'
      );

    //add submit button
    $('#add-action').prepend(
      '<p id="actions"><button id="action-submit" class="btn btn-primary">Add Action</button>&nbsp;&nbsp;<button id="cancel" class="btn btn-danger">Cancel</button></p><p id="action-error" style="display:none;"><p>'
      );

    $('#action-submit').click(function(event){
      var respondingTo = $('#company-name span').text();

      if($('#respond-to').length) {
        respondingTo = $('#respond-to').text();
      }

      var isValid = validateAction();

      if(isValid) {
        //alert("will be ready to save record");

        event.preventDefault();
        
        jQuery.ajaxSetup({ 
          headers: { 
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') 
          }
        });

        $.ajax({
          url: "/action-log",
          method: 'post',
          data: {
            date: $('#date').val(),
            company: $('#company').val(),
            name: $('#name').val(),
            communication_type: $('#communication-type').val(),
            contact: $('#contact').val(),
            current_status: $('#current-status').val(),
            action_item: $('#action-item').val(),
            action_key_client_name: respondingTo,
            assigned_to: $('.assigned-to').val()
          },
          success: function(result){
            $('#action-table tbody tr').each(function(){
              $(this).remove();
            });

            if(result.role == 'admin') {
              for(var i = 0; i < result.actions.length; i++) {
               $('#action-table tbody').append(
                '<tr id="action-log-' + result.actions[i].id + '">' +
                '<td>' + result.actions[i].string_date + '</td>' +
                '<td>' + result.actions[i].company_name + '</td>' +
                '<td>' + result.actions[i].name + '</td>' +
                '<td>' + result.actions[i].communication_type + '</td>' +
                '<td>' + result.actions[i].contact + '</td>' +
                '<td>' + result.actions[i].status + '</td>' +
                '<td>' + result.actions[i].action_item + '</td>' +
                '<td><input type="checkbox" class="archive" name="archive-' + result.actions[i].id + '" id="archive-' + result.actions[i].id + '"></td>' +
                '<td id="record-' + result.actions[i].id + '" class="assigned-to">' + result.actions[i].assigned_to + '</td>' +
                '</tr>'
                );
             }

             $('.archive').each(function(){
                $(this).click(function(){

                  if($(this).is(':checked')) {
                    //get action log id from id
                    var actionLogId = $(this).attr('id').split('-')[1];

                    //ajax request to archive this record
                    jQuery.ajaxSetup({ 
                      headers: { 
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') 
                      }
                    });

                    $.ajax({
                      url: "/archive",
                      method: 'post',
                      data: {
                        id: actionLogId
                      },
                      success: function(result){
                        //alert("success: " + result.success);
                      },
                      error: function(result) {
                        //alert("error: " + result.error);
                      }
                    });

                    //remove row from table
                    $(this).closest('#action-log-' + actionLogId).remove();
                  }

                });
              });
           }
           else {
            for(var i = 0; i < result.actions.length; i++) {
             $('#action-table tbody').append(
              '<tr>' +
              '<td>' + result.actions[i].string_date + '</td>' +
              '<td>' + result.actions[i].company_name + '</td>' +
              '<td>' + result.actions[i].name + '</td>' +
              '<td>' + result.actions[i].communication_type + '</td>' +
              '<td>' + result.actions[i].contact + '</td>' +
              '<td>' + result.actions[i].status + '</td>' +
              '<td>' + result.actions[i].action_item + '</td>' +
              '</tr>'
              );
           }
         }

         $('.assigned-to').click(function(){

          if(!editAssignedTo) {
            var text = $(this).text();
            $(this).html('<input type="text" id="edit-assigned-to" name="assigned-to" value="' + text + '">');
            editAssignedTo = true;
          }

          $('#edit-assigned-to').focus(function(){
            $(this).keypress(function(e){
              if(e.which == 13) {
                e.preventDefault();
                $(this).trigger('focusout');
              }
            });
          });

        });

         $('.assigned-to').focusout(function(){
          var text = $('#edit-assigned-to').val();
          var lookupId = $(this).attr('id');

          $(this).html(text);
          editAssignedTo = false;

          jQuery.ajaxSetup({ 
            headers: { 
              'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') 
            }
          });

          $.ajax({
            url: "/assign-to",
            method: 'post',
            data: {
              lookup_id: lookupId,
              assigned_to: text
            },
            success: function(result){
              //alert("success: " + result.success + "\nrequest: " + result.request + "\nlookup_id: " + result.lookup_id);
              //success
            }
          });

        });

         $('#action-error').css('display', 'none');
         $('#actions').remove();

         add = false;

       }
     }); //end ajax

      } // end if
      else {
        $('#action-error').css('display', 'block');
        $('#action-error').html('One or more fields are not entered properly. Please try again.');
      }

    }); //action-submit

    $('#cancel').click(function(){
      add = false;
      $('#action-table tr:last').remove();
      $('#actions').remove();
      $('#action-error').remove();
    });

    //turn date field into datepicker
    $('#date').datepicker();

    add = true;

  });

});

$("#action-log-edit").click(function() {

  if(!edit) {
    edit = true;

    $('#action-table thead tr').prepend("<th>Delete</th>");
    $('#action-table tbody tr').each(function(){
      $(this).prepend('<td><input id="delete-' + $(this).attr('id') + '" type="checkbox"></td>');
    });

    $('#action-table [id^=delete]').each(function(){
      $(this).click(function(){

        if($(this).is(':checked')) {

          var confirmation = window.confirm("Are you sure you would like to delete this action item? Only Advanced Spectral Technology, Inc. can undo this action.");

          if(!confirmation) {
            // user does not want to delete the action.
            return;
          }

            //get action log id from id
            var deleteActionLogId = $(this).attr('id').split('-')[3];

            // //ajax request to delete this record
            jQuery.ajaxSetup({ 
              headers: { 
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') 
              }
            });

            $.ajax({
                url: "/delete",
                method: 'post',
                data: {
                id: deleteActionLogId
              },
              success: function(result){
                //alert("success: " + result.success);
              },
              error: function(result) {
                //alert("error: " + result.error);
              }
            });

          //remove row from table
          $(this).closest('#action-log-' + deleteActionLogId).remove();
        }

      });
    });
  }
});

$(function(){
  $('.removeProductDocument').click(function(){
    $(this).each(function(){

      var confirmation = window.confirm("WARNING! For security and privacy purposes, deleting files from the system is permanent and the file cannot be restored. Only click \"OK\" if you are certain that you would like to delete the file.");

      if(!confirmation) {
        return;
      }

      var file = $(this).closest('li').find('a').text();
      var elem = $(this).closest('.list-group-item');
      var productDocumentId = $(this).attr('id').split('-')[2];
      
      jQuery.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
      });

      $.ajax({
        url: "/removeProductDocument",
        method: 'post',
        data: {
          id: productDocumentId,
          name: file
        },
        success: function(result) {
          //alert("success: " + result.success);
          elem.remove();
        },
        error: function(result) {
          //alert("error: " + result.error);
        }
      });

    });

    // $(this).closest('.list-group-item').remove();
  });
});

$(function(){
  $('.removeTestDocument').click(function(){
    $(this).each(function(){

      var confirmation = window.confirm("WARNING! For security and privacy purposes, deleting files from the system is permanent and the file cannot be restored. Only click \"OK\" if you are certain that you would like to delete the file.");

      if(!confirmation) {
        return;
      }

      var file = $(this).closest('li').find('a').text();
      var elem = $(this).closest('.list-group-item');
      var testDocumentId = $(this).attr('id').split('-')[2];

      jQuery.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
      });

      $.ajax({
        url: "/removeTestDocument",
        method: 'post',
        data: {
          id: testDocumentId,
          name: file
        },
        success: function(result) {
          //alert("success: " + result.success);
          elem.remove();
        },
        error: function(result) {
          //alert("error: " + result.error);
        }
      });

    });
  });
});


$(function () {
  $('.example-popover').popover({
    container: 'body'
  })
});

function validateAction() {

  if($('#date').val() == '') {
    alert('date wrong');
    return false;
  }

  if($('#company').val() == '') {
    alert('company wrong');
    return false;
  }
  
  if($('#name').val() == '') {
    alert('name wrong');
    return false;
  }

  if($('#communication-type').val() == '') {
    alert('communication-type wrong');
    return false;
  }

  if($('#contact').val() == '') {
    alert('contact wrong');
    return false;
  }
  
  if($('#current-status').val() == '') {
    alert('status wrong => ' + $('#status').val());
    return false;
  }

  if($('#action-item').val() == '') {
    alert('action-item wrong');
    return false;
  }

  if($('#action-item').val().length > 191) {
    alert('Action item is too long. Please make sure your item is fewer than 192 characters.');
    return false;
  }

  if($('#assigned-to').val() == '') {
    alert('assigned-to wrong');
    return false;
  }

  return true;
}


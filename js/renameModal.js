(function ($) {

  var fields = ['Title', 'Slug', 'Filename'];

  // Cycle through fields and disable them if their corresponding checkbox isn't checked
  function enableFields() {
    for (var index in fields) {
      var field = fields[index];
      $('#renameModal' + field).attr('disabled', !$('#renameModalEnable' + field).is(':checked'));
    }
  }

  // Post updates to fields whose corresponding checkboxes are checked
  function postUpdates(callback) {
    var updatedFields = {};

    for (var index in fields) {
      var field = fields[index];
      if ($('#renameModalEnable' + field).is(':checked')) {
        updatedFields[field.toLowerCase()] = $('#renameModal' + field).val();
      }
    }

    $.ajax({
      'url': window.location + '/rename?a=1',
      'type': 'PUT',
      'cache': false,
      'data': updatedFields,
      'success': function() {
        callback();
      },
      'error': function() {
        callback(true, 'Error sending update');
      }
    });
  }

  $(function() {
    enableFields();

    // Add click handlers
    $('#renameModal').bind('show', function() {
      $('#renameModalSubmit').click(function(e) {
        postUpdates(function(error, message) {
          if (error !== undefined) {
            alert(message);
          } else {
            // remove last element of URL
            var urlParts = window.location.href.split('/');
            var urlBase = urlParts.slice(0, urlParts.length - 1).join('/');

            // redirect to current location
            window.location = urlBase + '/' + $('#renameModalSlug').val();
          }
        });

        $('#renameModal').modal('hide');
      });

      $('#renameModalCancel').click(function(e) {
        $('#renameModal').modal('hide');
      });

      $('#renameModal form input[type=checkbox]').click(function(e) {
        enableFields();
      });
    });

    // Remove click handlers
    $('#renameModal').bind('hide', function() {
      $('#renameModalSubmit').unbind();
      $('#renameModalCancel').unbind();
      $('#renameModal form input[type=checkbox]').unbind();
    });
  });

})(window.jQuery);
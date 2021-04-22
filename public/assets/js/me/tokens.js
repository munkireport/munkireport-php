$(document).ready(function() {
  $('#tokens').DataTable({
    "ajax": "/api/v6/me/tokens",
    "columns": [
      { "data": "name" },
      { "data": "created_at", "render": function(data, type, row, meta) {
        return moment(data).format();
        } },
      { "data": "last_used_at", "render": function(data) {
        if (data) {
          return moment(data).fromNow();
        } else {
          return "Never";
        }
        } },
    ]
  });

  var $modal = $('#createTokenModal');

  $modal.on('show.bs.modal', function (event) {
    $('#createTokenForm').trigger('reset');
  });

  $modal.on('shown.bs.modal', function (event) {
    $('#createTokenForm').find("input[name='token_name']").focus();
  })

  $modal.on('hidden.bs.modal', function (event) {
    $('#token').val('');
    $('#createTokenResult').hide();
  });

  $('#createTokenForm').submit(function (event) {
    event.preventDefault();
    event.stopPropagation();

    var $form = $(this),
      tokenName = $form.find("input[name='token_name']").val();

    var xhr = $.post("/api/v6/me/tokens", { token_name: tokenName, });

    xhr.done((function (data) {
      console.log('request done');
      if (data.token) {
        $('#token').val(data.token);
        $('#createTokenResult').show();
      }
    }));
  })
});

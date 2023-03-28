$(document).ready(function() {
  $('#relation_id').on('change', function() {
    var relation_id = $(this).val();
    $.ajax({
      url: 'get_relation_address.php',
      type: 'post',
      data: {relation_id: relation_id},
      success: function(response) {
        $('#relation_address').val(response);
      }
    });
  });
});
$(document).ready(function() {
  $('#article_id').on('change', function() {
    var article_id = $(this).val();
    $.ajax({
      url: 'get_article_description.php',
      type: 'post',
      data: {article_id: article_id},
      success: function(response) {
        $('#article_description').val(response);
      }
    });
  });
});
<?php include 'header.php';?>

<form id="uploadForm" action="upload.php" method="post" enctype="multipart/form-data">
    <input type="file" name="image" accept="image/*" required size="20971520">
    <button type="submit">Submit</button>
</form>
  <div id="result">
    <img id="imagePreview" src="#" alt="Image Preview">
  </div>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script>
  $(document).ready(function() {
    $('#uploadForm').submit(function(e) {
      e.preventDefault();

      var formData = new FormData(this);

      $.ajax({
        url: $(this).attr('action'),
        type: $(this).attr('method'),
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          // 处理服务器响应
          $('#result').html(response);
        },
        error: function(error) {
          console.log('Error:', error);
        }
      });
    });


    
    // 预览上传的图片
    $('input[name="image"]').change(function() {
      var input = this;
      var reader = new FileReader();

      reader.onload = function(e) {
        $('#imagePreview').attr('src', e.target.result);
      };

      reader.readAsDataURL(input.files[0]);
    });

  
  });
</script>
<?php include 'footer.php';?>
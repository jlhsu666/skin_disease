<?php include 'header.php';?>
<p>關於我</p>
<?php
echo "<img src='/usr/share/nginx/html/uploads/0_2.jpg' />";

$image = '/usr/share/nginx/html/uploads/0_2.jpg';
// Read image path, convert to base64 encoding
$imageData = base64_encode(file_get_contents($image));

// Format the image SRC:  data:{mime};base64,{data};
$src = 'data: '.mime_content_type($image).';base64,'.$imageData;

// Echo out a sample image
echo '<img src="' . $src . '">';
?>
<?php include 'footer.php';?>
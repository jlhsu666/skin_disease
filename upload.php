<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 检查是否有文件上传
if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
    // 获取上传的文件名
    $filename = $_FILES['image']['name'];
    
    // 获取文件在服务器上的临时存储路径
    $tmp_path = $_FILES['image']['tmp_name'];

    // 指定服务器上存储上传文件的目录，确保该目录存在
    $upload_dir = '/usr/share/nginx/html/uploads';

    // 将文件从临时路径移动到指定的目录
    $target_path = $upload_dir .'/'. $filename;
    $result = move_uploaded_file($tmp_path, $target_path);

    // 显示上传的图片
    // Read image path, convert to base64 encoding
    $imageData = base64_encode(file_get_contents($target_path));

    // Format the image SRC:  data:{mime};base64,{data};
    $src = 'data: '.mime_content_type($target_path).';base64,'.$imageData;
    // Echo out a sample image
    echo '<img src="' . $src . '">';



    // 调用图像识别 Python 脚本
    $imagePath = $target_path; // 替换为实际的图像路径
    $command = escapeshellcmd("python3 recognize_image.py " . $imagePath);
    $recognition_result = shell_exec($command);

    // 处理 Python 脚本的输出
    $python_result = json_decode($recognition_result, true);

    if ($python_result["success"]) {
        echo "Predicted class: " . $python_result["predicted_class"];
    } else {
        echo "Error: " . $python_result["error_message"];
    }
    
} else {
    // 返回错误信息给前端
    echo '<p>文件上传失败！</p>';
    echo '<p>错误代码：' . $_FILES['image']['error'] . '</p>';
    echo '<p>错误信息：' . error_get_last()['message'] . '</p>';
}

?>

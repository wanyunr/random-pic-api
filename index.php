<?php
$pcPath = 'landscape';
$mobilePath = 'portrait';

// 函数：从目录中获取图片列表
function getImagesFromDir($path) {
    $images = array();
    if ($img_dir = @opendir($path)) {
        while (false !== ($img_file = readdir($img_dir))) {
            // 匹配 webp、jpg、jpeg、png、gif 格式的图片
            if (preg_match("/\.(webp|jpg|jpeg|png|gif)$/i", $img_file)) {
                $images[] = $img_file;
            }
        }
        closedir($img_dir);
    }
    return $images;
}

// 函数：生成完整的图片路径
function generateImagePath($path, $img) {
    return $path . '/' . $img;
}

// 检测用户代理以区分手机和电脑访问
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$isMobile = preg_match('/(android|iphone|ipad|ipod|blackberry|windows phone)/i', $userAgent);

// 根据访问设备设置图片路径
if ($isMobile) {
    $path = $mobilePath;
} else {
    $path = $pcPath;
}

// 缓存图片列表
$imgList = getImagesFromDir($path);

// 从列表中随机选择一张图片
shuffle($imgList);
$img = reset($imgList);

// 获取图片的格式
$img_extension = pathinfo($img, PATHINFO_EXTENSION);

// 根据图片的格式设置 Content-Type
switch ($img_extension) {
    case 'webp':
        $img_mime = 'image/webp';
        break;
    case 'jpg':
    case 'jpeg':
        $img_mime = 'image/jpeg';
        break;
    case 'png':
        $img_mime = 'image/png';
        break;
    case 'gif':
        $img_mime = 'image/gif';
        break;
    // 添加其他格式的处理方式
    // case 'bmp':
    //     $img_mime = 'image/bmp';
    //     break;
}

// 生成完整的图片路径
$img_path = generateImagePath($path, $img);

// 如果是手机端，输出对应的 HTML 结构
if ($isMobile) {
    // 获取图片尺寸
    list($width, $height) = getimagesize($img_path);

    // 设置标题为图片尺寸
    echo "<title>{$width}x{$height}</title>";

    // 输出手机端的 HTML 结构
    echo "<!DOCTYPE html>
        <html lang=\"en\">
        <head>
            <meta charset=\"UTF-8\">
            <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
            <style>
                body {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                    background-color: #000000;
                }
                .responsive-img {
                    max-width: 100%;
                    max-height: 100%;
                    object-fit: contain;
                }
            </style>
        </head>
        <body>
            <img src=\"data:{$img_mime};base64," . base64_encode(file_get_contents($img_path)) . "\" class=\"responsive-img\" alt=\"随机图片\">
            <script>
                window.addEventListener(\"resize\", function() {
                    var img = document.querySelector('.responsive-img');
                    if (window.innerHeight > window.innerWidth) {
                        // 竖屏状态，按照宽度适应
                        img.style.maxWidth = \"100%\";
                        img.style.height = \"auto\";
                    } else {
                        // 横屏状态，按照高度适应
                        img.style.maxHeight = \"100%\";
                        img.style.width = \"auto\";
                    }
                });
            </script>
        </body>
        </html>";
} else {
    // 如果是电脑端，直接输出图片
    header("Content-Type: {$img_mime}");
    readfile($img_path);
}
?>

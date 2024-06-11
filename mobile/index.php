<?php
$pcPath = '../portrait';

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

// 设置图片路径为 portrait
$path = $pcPath;

// 缓存图片列表
$imgList = getImagesFromDir($path);

// 从列表中随机选择一张图片
shuffle($imgList);
$img = reset($imgList);

// 获取图片的格式
$img_extension = pathinfo($img, PATHINFO_EXTENSION);

// 根据图片的格式设置 MIME 类型
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
    default:
        $img_mime = 'image/jpeg';
}

// 生成完整的图片路径
$img_path = generateImagePath($path, $img);

// 获取图片尺寸
list($width, $height) = getimagesize($img_path);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    // 获取图片尺寸
    list($width, $height) = getimagesize($img_path);

    // 根据图片尺寸设置初始的显示方式
    $initial_style = $width > $height ? 'max-width: 100%; height: auto;' : 'max-height: 100%; width: auto;';

    // 设置标题为图片尺寸
    echo "<title>{$width}x{$height}</title>";

    // 根据初始的显示方式设置样式
    echo "<style>
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                background-color: #000000;
            }
            .responsive-img {
                {$initial_style}
                object-fit: contain;
            }
        </style>";
    ?>
</head>
<body>
    <img src="data:<?php echo $img_mime; ?>;base64,<?php echo base64_encode(file_get_contents($img_path)); ?>" class="responsive-img" alt="随机图片">
    <script>
        window.addEventListener("resize", function() {
            var img = document.querySelector('.responsive-img');
            if (window.innerHeight > window.innerWidth) {
                // 竖屏状态，按照宽度适应
                img.style.maxWidth = "100%";
                img.style.height = "auto";
                img.style.maxHeight = "none";
                img.style.width = "auto";
            } else {
                // 横屏状态，按照高度适应
                img.style.maxHeight = "100%";
                img.style.width = "auto";
                img.style.maxWidth = "none";
                img.style.height = "auto";
            }
        });
    </script>
</body>
</html>
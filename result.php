<?php
    $url = $_POST['url'];
    preg_match('/(https?:\/\/.*?)\//', $url, $matches);
    $domen = $matches[1];
    $images = getImagesFromPage($url, $domen);
    $totalSize = getTotalImageSize($images);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task-1 Page-2</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <main class="grid">
        <?
            foreach ($images as $image) {
                if (preg_match("/^\/\//", $image) || preg_match("/^https?:\/\//", $image)) {
                    echo "<div class='cell'><img src=$image></div>";
                }
                else{
                    echo "<div class='cell'><img src=$domen$image></div>";
                }
            }
        ?>
    </main>
    <? echo "<h4>На странице обнаружено " . count($images) . " изображений на $totalSize Мб</h4>" ?>
</body>
</html>

<?php
    function getImagesFromPage($url, $domen) {
        $html = file_get_contents($url);
        $doc = new DOMDocument();
        @$doc->loadHTML($html);
        $images = array();
        foreach ($doc->getElementsByTagName('img') as $img) {
            $image = $img->getAttribute('src');
            if (preg_match("/^\/\//", $image) || preg_match("/^https?:\/\//", $image)) {
                array_push($images, $image);
            }
            else{
                array_push($images, $domen.$image);
            }
        }
        return $images;
    }

    function getTotalImageSize($images) {
        $totalSize = 0;
        foreach ($images as $image) {
            $imageSize = @getimagesize($image);
            if ($imageSize !== false) {
                $totalSize += $imageSize[1] * $imageSize[0] / 1024 / 1024;
            }
        }
        return round($totalSize, 2);
    }
?>


<?php

$postsDirectory = 'posts/';

if (isset($_GET['slug'])) {
    $slug = $_GET['slug'];
    $filePath = $postsDirectory . $slug . '.txt';

    if (file_exists($filePath)) {
        $postContent = file_get_contents($filePath);
        $lines = explode("\n", $postContent);
        $title = array_shift($lines);
        $content = implode("\n", $lines);
    } else {
        echo "Post not found.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>
</head>
<body>
    <h1><?php echo $title; ?></h1>
    <p><?php echo $content; ?></p>
    <a href="index.php">Back to Home</a>
</body>
</html>

<?php

$postsDirectory = 'posts/';
$posts = [];

// Function to add a new post
function addPost($title, $content, $postsDirectory) {
    $slug = slugify($title); // Generate slug from title
    $filePath = $postsDirectory . $slug . '.txt';
    $postContent = $title . "\n" . $content;

    if (file_put_contents($filePath, $postContent) !== false) {
        return true;
    } else {
        return false;
    }
}

// Helper function to generate slug from title
function slugify($text) {
    // Replace non-alphanumeric characters with -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    // Transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    // Remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
    // Trim
    $text = trim($text, '-');
    // Remove duplicate -
    $text = preg_replace('~-+~', '-', $text);
    // Lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}

// Get all files in the posts directory
$files = scandir($postsDirectory);

// Loop through files and read post data
foreach ($files as $file) {
    if ($file !== '.' && $file !== '..') {
        $postContent = file_get_contents($postsDirectory . $file);
        $lines = explode("\n", $postContent);
        $title = array_shift($lines); // First line is the title
        $content = implode("\n", $lines); // The rest is the content

        $posts[] = [
            'title' => $title,
            'content' => $content,
            'slug' => str_replace('.txt', '', $file), // Filename without extension
        ];
    }
}

// Handle post creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    if (addPost($title, $content, $postsDirectory)) {
        echo "Post added successfully!";
    } else {
        echo "Error adding post.";
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="styles.css">
    <title>My Simple Gemini Blog Blog</title>
    
</head>
<body>
    <h1>Welcome to my Gemini blog!</h1>

    <h2>Add New Post</h2>
    <button id="addPostBtn">Add New Post</button>

    <div id="addPostForm" style="display: none;"> 
        <h2>Add New Post</h2>
        <form method="POST" action="" >
            <label for="title">Title:</label><br>
            <input type="text" id="title" name="title" required><br><br>
            <label for="content">Content:</label><br>
            <textarea id="content" name="content" rows="5" required></textarea><br><br>
            <input type="submit" value="Add Post">
        </form>
    </div>

    <h2>Posts</h2>
    <?php foreach ($posts as $post): ?>
        <h2><a href="post.php?slug=<?php echo $post['slug']; ?>"><?php echo $post['title']; ?></a></h2>
        <p><?php echo substr($post['content'], 0, 200); ?>...</p> 
        <a href="post.php?slug=<?php echo $post['slug']; ?>">Read more</a>
    <?php endforeach; ?>

    <script>
        const addPostBtn = document.getElementById('addPostBtn');
        const addPostForm = document.getElementById('addPostForm');

        addPostBtn.addEventListener('click', () => {
            if (addPostForm.style.display === 'none') {
                addPostForm.style.display = 'block';
                addPostBtn.textContent = 'Hide Form';
            } else {
                addPostForm.style.display = 'none';
                addPostBtn.textContent = 'Add New Post';
            }
        });
    </script>
</body>
</html>

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

include 'login_status.php';
include 'config.php';

$categoryName = isset($_GET['category']) ? $_GET['category'] : '';

// Fetch category ID based on the category name
$stmt = $db->prepare("SELECT category_id FROM categories WHERE name = ?");
$stmt->bind_param("s", $categoryName);
$stmt->execute();
$result = $stmt->get_result();
$category = $result->fetch_assoc();
$category_id = $category['category_id'];
$stmt->close();

// Fetch posts based on category ID
$postsStmt = $db->prepare("SELECT * FROM posts WHERE category_id = ? AND is_hidden = 0 ORDER BY timestamp DESC");
$postsStmt->bind_param("i", $category_id);
$postsStmt->execute();
$posts = $postsStmt->get_result()->fetch_all(MYSQLI_ASSOC);
$postsStmt->close();

// Fetch threads based on category ID
$threadsStmt = $db->prepare("SELECT * FROM threads WHERE category_id = ? AND is_hidden = 0 ORDER BY creation_date DESC");
$threadsStmt->bind_param("i", $category_id);
$threadsStmt->execute();
$threads = $threadsStmt->get_result()->fetch_all(MYSQLI_ASSOC);
$threadsStmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Include your head content here -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MessiIsTheGoat - Home</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <!-- <script src="https://kit.fontawesome.com/69d2da3707.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script> -->
</head>
</head>
<body>
    <header>
        <!-- Include your header content here -->
        <nav class="navbar">
            <div class="logo-container">
                <img src="https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcQHnhWFBFpqpDEQE_DyEaYEXHwa8QY4mAsBTeZaif0XvmL1sXI2" alt="MessiIsTheGoat Logo" class="logo-image">
                <div class="logo-title">MessiIsTheGoat</div>
            </div>
            <a href="index.php" class="nav-link">Home</a>
            <!-- <a href="categories.php" class="nav-link">Categories</a> -->
            <form action="search.php" method="post" class="search-container">
                <input type="text" name="search_query" placeholder="Search..." class="search-input">
                <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
            </form>
            <?php if ($userLoggedIn): ?>
                <a href="User_Profile.php" class="nav-link">Welcome, <?php echo htmlspecialchars($username); ?></a>
                <form action="logout.php" method="POST" style="display: inline;">
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
                <?php if ($isAdmin): ?>
                    <!-- Additional Admin Links Here -->
                <?php endif; ?>
            <?php else: ?>
                <a href="Login.php" class="nav-link">Login</a>
                <a href="Signup.php" class="nav-button">Register</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <h2>Content in '<?php echo htmlspecialchars($categoryName); ?>'</h2>
        
        <?php if (empty($posts) && empty($threads)): ?>
            <p>No Content Yet! Post Some Stuff :)</p>
        <?php else: ?>
            <section class="posts">
                <h3>Posts</h3>
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <article class="post">
                            <!-- Display each post -->
                            <h4><?php echo htmlspecialchars($post['title']); ?></h4>
                            <p><?php echo htmlspecialchars($post['content']); ?></p>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No posts yet!</p>
                <?php endif; ?>
            </section>

            <section class="threads">
                <h3>Threads</h3>
                <?php if (!empty($threads)): ?>
                    <?php foreach ($threads as $thread): ?>
                        <article class="thread">
                            <!-- Display each thread -->
                            <h4><?php echo htmlspecialchars($thread['title']); ?></h4>
                            <p><?php echo htmlspecialchars($thread['body']); ?></p>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No threads yet!</p>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </main>


    <footer>
        <p>&copy; 2024 MessiIsTheGoat</p>
    </footer>
</body>
</html>

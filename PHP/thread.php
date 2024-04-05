<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include 'config.php'; // Database connection setup
include 'login_status.php'; // Check login status

$thread_id = isset($_GET['thread_id']) ? intval($_GET['thread_id']) : 0;

// Fetch thread details
$threadQuery = $db->prepare("SELECT * FROM threads WHERE thread_id = ?");
$threadQuery->bind_param("i", $thread_id);
$threadQuery->execute();
$threadResult = $threadQuery->get_result();
$thread = $threadResult->fetch_assoc();

$postsQuery = $db->prepare("
    SELECT 
        posts.*, 
        COUNT(votes.vote_id) AS vote_count,
        users.username
    FROM 
        posts 
    LEFT JOIN 
        votes ON posts.post_id = votes.post_id
    LEFT JOIN 
        users ON posts.user_id = users.user_id
    WHERE 
        posts.thread_id = ?
    GROUP BY 
        posts.post_id
    ORDER BY 
        vote_count DESC, posts.timestamp DESC
");

$postsQuery->bind_param("i", $thread_id);
$postsQuery->execute();
$postsResult = $postsQuery->get_result();
$posts = [];

while ($post = $postsResult->fetch_assoc()) {
    $post['comments'] = [];

    // Fetch comments for each post
    $commentsQuery = $db->prepare("
        SELECT 
            comments.*, 
            users.username 
        FROM 
            comments 
        JOIN 
            users ON comments.user_id = users.user_id
        WHERE 
            comments.post_id = ?
        ORDER BY 
            comments.timestamp ASC
    ");

    $commentsQuery->bind_param("i", $post['post_id']);
    $commentsQuery->execute();
    $commentsResult = $commentsQuery->get_result();

    while ($comment = $commentsResult->fetch_assoc()) {
        $post['comments'][] = $comment;
    }

    // Append this post (with its comments if any) to the posts array
    $posts[] = $post;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($thread['title']); ?> - MessiIsTheGoat</title>
    <link rel="stylesheet" href="../CSS/styles-phone.css" media="screen and (max-width: 480px)">
    <link rel="stylesheet" href="../CSS/styles.css" media ="screen and (min-width: 769px)">
    <script src="https://kit.fontawesome.com/69d2da3707.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo-container">
                <img src="https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcQHnhWFBFpqpDEQE_DyEaYEXHwa8QY4mAsBTeZaif0XvmL1sXI2" alt="MessiIsTheGoat Logo" class="logo-image">
                <div class="logo-title">MessiIsTheGoat</div>
            </div>
            <a href="index.php" class="nav-link">Home</a>
            <a href="categories.php" class="nav-link">Categories</a>
            <form action="search.php" method="post" class="search-container">
                <input type="text" name="search_query" placeholder="Search..." class="search-input">
                <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
            </form>
            <?php if ($userLoggedIn): ?>
                <a href="User_Profile.php" class="nav-link">Welcome, <?php echo htmlspecialchars($username); ?></a>
                <form action="logout.php" method="POST" style="display: inline;">
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            <?php else: ?>
                <a href="Login.php" class="nav-link">Login</a>
                <a href="Signup.php" class="nav-button">Register</a>
            <?php endif; ?>
        </nav>
    </header>

    <main class="thread-page">
        <h1><?php echo htmlspecialchars($thread['title']); ?></h1>
        <div class="posts" id="posts-container">
    <?php foreach ($posts as $post): ?>
        <article class="post" id="post-<?php echo htmlspecialchars($post['post_id']); ?>">
            <div class="vote">
                <!-- Check if user is logged in to display voting buttons -->
                <?php if ($userLoggedIn): ?>
                    <button class="vote-btn upvote-btn" data-post="<?php echo htmlspecialchars($post['post_id']); ?>" onclick="vote(<?php echo htmlspecialchars($post['post_id']); ?>, 1)"><i class="fas fa-arrow-up"></i></button>
                <?php endif; ?>
                
                <!-- Display current vote count -->
                <div class="vote-count" id="vote-count-<?php echo htmlspecialchars($post['post_id']); ?>">
                    <?php echo htmlspecialchars($post['vote_count']); ?>
                </div>       
                <?php if ($userLoggedIn): ?>
                    <button class="vote-btn downvote-btn" data-post="<?php echo htmlspecialchars($post['post_id']); ?>" onclick="vote(<?php echo htmlspecialchars($post['post_id']); ?>, -1)"><i class="fas fa-arrow-down"></i></button>
                <?php endif; ?>
            </div>
            <div class="post-info">
                <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                <p><?php echo htmlspecialchars($post['content']); ?></p>
            </div>
            <!-- Comment Section -->
            <div class="comments-section">
                <?php if ($userLoggedIn): ?>
                    <form class="comment-form" method="post">
                    <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post['post_id']); ?>">
                    <input type="text" name="comment" placeholder="Add a comment..." class="comment-input">
                    <button type="button" class="comment-btn">Post Comment</button>
                    </form>
                <?php endif; ?>
                <?php foreach ($post['comments'] as $comment): ?>
                    <div class="comment">
                        <strong><?php echo htmlspecialchars($comment['username']); ?>:</strong>
                        <p><?php echo htmlspecialchars($comment['content']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </article>
    <?php endforeach; ?>
</div> 
    </main>

    <footer>
        <p>&copy; 2024 MessiIsTheGoat</p>
    </footer>
    <script>
    $(document).ready(function() {
        $('.comment-btn').click(function() {
            var form = $(this).closest('form');
            var postData = form.serialize();
            var commentsSection = form.closest('.comments-section');

            $.ajax({
                type: 'POST',
                url: 'add_comment.php',
                data: postData,
                success: function(response) {
                    if (response === 'Comment added.') {
                        var newComment = `<div class="comment">
                            <strong><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>:</strong>
                            <p>${form.find('[name="comment"]').val()}</p>
                        </div>`;
                        commentsSection.append(newComment);
                        form.find('[name="comment"]').val(''); // Clear the input after posting
                    }
                }
            });
        });
    });
    function reorderPosts() {
        let posts = $('.post').get();
        posts.sort((a, b) => {
            const votesA = parseInt($(a).find('.vote-count').text());
            const votesB = parseInt($(b).find('.vote-count').text());
            return votesB - votesA;
        });
        $('#posts-container').empty().append(posts);
    }

    $(document).ready(function() {
        $('.vote-btn').click(function() {
            var postId = $(this).data('post');
            var voteType = $(this).hasClass('upvote-btn') ? 1 : -1;
            vote(postId, voteType);
        });
    });
</script>
</body>
</html>
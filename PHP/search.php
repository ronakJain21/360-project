<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

include 'login_status.php'; // Include to check login status
include 'config.php'; // Database connection setup

$searchResults = ['posts' => [], 'comments' => [], 'threads' => []];

if (isset($_POST['search_query'])) {
    $searchQuery = $db->real_escape_string(trim($_POST['search_query']));

    // Search in posts
    $postsStmt = $db->prepare("SELECT * FROM posts WHERE title LIKE CONCAT('%', ?, '%') OR content LIKE CONCAT('%', ?, '%')");
    $postsStmt->bind_param("ss", $searchQuery, $searchQuery);
    $postsStmt->execute();
    $postsResult = $postsStmt->get_result();
    while ($row = $postsResult->fetch_assoc()) {
        $row['comments'] = [];
        $searchResults['posts'][] = $row;
    }
    $postsStmt->close();

    // Expand each post with comments
    foreach ($searchResults['posts'] as &$post) {
        $postId = $post['post_id'];

        $commentsQuery = "SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.user_id WHERE post_id = ? ORDER BY timestamp ASC";
        $stmt = $db->prepare($commentsQuery);
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $commentsResult = $stmt->get_result();

        while ($comment = $commentsResult->fetch_assoc()) {
            $post['comments'][] = $comment;
        }
        $stmt->close();
    }
    unset($post); // Break reference in the loop.

    // Search in comments
    $commentsStmt = $db->prepare("SELECT * FROM comments WHERE content LIKE CONCAT('%', ?, '%')");
    $commentsStmt->bind_param("s", $searchQuery);
    $commentsStmt->execute();
    $commentsResult = $commentsStmt->get_result();
    while ($row = $commentsResult->fetch_assoc()) {
        $searchResults['comments'][] = $row;
    }
    $commentsStmt->close();

    // Search in threads
    $threadsStmt = $db->prepare("SELECT * FROM threads WHERE title LIKE CONCAT('%', ?, '%') OR body LIKE CONCAT('%', ?, '%')");
    $threadsStmt->bind_param("ss", $searchQuery, $searchQuery);
    $threadsStmt->execute();
    $threadsResult = $threadsStmt->get_result();
    while ($row = $threadsResult->fetch_assoc()) {
        $searchResults['threads'][] = $row;
    }
    $threadsStmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="../CSS/searchstyle.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo-container">
                <img src="https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcQHnhWFBFpqpDEQE_DyEaYEXHwa8QY4mAsBTeZaif0XvmL1sXI2" alt="MessiIsTheGoat Logo" class="logo-image">
                <div class="logo-title">MessiIsTheGoat</div>
            </div>
            <a href="Index.php" class="nav-link">Home</a>
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
                <?php if ($isAdmin): ?>
                    <!-- Additional Admin Links Here -->
                <?php endif; ?>
            <?php else: ?>
                <a href="Login.php" class="nav-link">Login</a>
                <a href="Signup.php" class="nav-button">Register</a>
            <?php endif; ?>
        </nav>
    </header>

    <div id="search-results">
        <h2>Search Results for '<?php echo htmlspecialchars($_POST['search_query'] ?? '', ENT_QUOTES); ?>'</h2>
        <div id="posts-results">
            <h3>Posts</h3>
            <?php foreach ($searchResults['posts'] as $post): ?>
                <div class="search-item">
                    <h4><?php echo htmlspecialchars($post['title']); ?></h4>
                    <p><?php echo htmlspecialchars($post['content']); ?></p>
                    <div class="comments-container">
                        <?php foreach ($post['comments'] as $comment): ?>
                            <div class="comment">
                                <strong><?php echo htmlspecialchars($comment['username']); ?>:</strong>
                                <p><?php echo htmlspecialchars($comment['content']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div id="comments-results">
            <h3>Comments</h3>
            <?php foreach ($searchResults['comments'] as $comment): ?>
                <div class="search-item">
                    <p><?php echo htmlspecialchars($comment['content']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <div id="threads-results">
            <h3>Threads</h3>
            <?php foreach ($searchResults['threads'] as $thread): ?>
                <div class="search-item">
                    <h4><?php echo htmlspecialchars($thread['title']); ?></h4>
                    <p><?php echo htmlspecialchars($thread['body']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

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

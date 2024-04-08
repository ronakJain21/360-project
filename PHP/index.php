<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'login_status.php'; // Handles user login status and checks for admin
include 'fetch_posts.php'; // Retrieves posts from the database
include 'fetch_top_threads.php'; // Retrieves top threads based on post count
include 'fetch_recent_posts.php'; // Retrieves the most recent posts
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MessiIsTheGoat - Home</title>
    <link rel="stylesheet" href="../CSS/styles.css">
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
                <a href="SignUp.php" class="nav-button">Register</a>
            <?php endif; ?>
        </nav>
    </header>
    <div class="container">
    <main>
            <?php if ($userLoggedIn): ?>
                <section class="create-post-box" onClick="location.href='createPost.php'">
                    <div class="create-post-input">
                        <i class="fas fa-edit"></i>
                        <span>Create Post</span>
                    </div>
                </section>
                <section class="create-thread-box" onClick="location.href='createThread.php'">
                    <div class="create-thread-input">
                        <i class="fas fa-comments"></i>
                        <span>Create Thread</span>
                    </div>
                </section>
            <?php endif; ?>
            <div class="posts" id="posts-container">
            <?php foreach ($posts as $post): ?>
                <?php if ($post['is_hidden'] == 0): ?> <!-- Check if post is not hidden -->
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
                            <p class="posted-on" style="font-size: 0.8em; color: #666; margin-top: 5px;">Posted on: <?php echo (new DateTime($post['timestamp']))->format('Y-m-d H:i'); ?></p>
                            <?php if ($isAdmin): ?>
                                <button class="delete-post-btn" onclick="deletePost(<?php echo htmlspecialchars($post['post_id']); ?>)">Delete Post</button>
                            <?php endif; ?>
                        </div>
                        <!-- Comment Section -->
                        <button class="comments-toggle">Show Comments</button>
                        <div class="comments-section" style="display: none;">
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
                                    <?php if (isset($comment['timestamp']) && !empty($comment['timestamp'])): ?>
                                        <p class="commented-on" style="font-size: 0.8em; color: #666; margin-top: 5px;">Commented on: <?php echo (new DateTime($comment['timestamp']))->format('Y-m-d H:i'); ?></p>
                                        <?php else: ?>
                                        <p>Timestamp unavailable.</p>
                                        <?php endif; ?>
                                    <?php if ($isAdmin): ?>
                                        <button class="delete-comment-btn" onclick="deleteComment(<?php echo htmlspecialchars($comment['comment_id']); ?>)">Delete Comment</button>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </article>
                <?php endif; ?>
            <?php endforeach; ?>
            </div> 
        </main>
        <aside class="sidebar">
            <!-- Top Threads Section -->
            <section class="top-threads">
                <h3>Top Threads</h3>
                <ul>
                    <?php foreach ($topThreads as $thread): ?>
                        <li><a href="thread.php?thread_id=<?php echo htmlspecialchars($thread['thread_id']); ?>"><?php echo htmlspecialchars($thread['title']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </section>
            <!-- Recent Posts Section -->
            <section class="recent-posts">
                <h3>Recent Posts By You</h3>
                <ul>
                    <?php foreach ($recentPosts as $post): ?>
                        <li><a href="User_Profile.php?post_id=<?php echo htmlspecialchars($post['post_id']); ?>"><?php echo htmlspecialchars($post['title']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </section>

            <section>
                <h3>Categories</h3>
                <ul>
                    <li><a href="category.php?category=WeLoveMessi">WeLoveMessi</a></li>
                    <li><a href="category.php?category=MESSIGOATArgument">MESSIGOATArgument</a></li>
                    <li><a href="category.php?category=WhyMessitheGOAT">WhyMessitheGOAT</a></li>
                </ul>
            </section>
        </aside>
    </div>
        <footer>
            <p>&copy; 2024 MessiIsTheGoat</p>
        </footer>
    
        <script>
$(document).ready(function() {
    $('.comments-toggle').click(function() {
        var $this = $(this);
        var $commentsSection = $this.next('.comments-section');
        
        $commentsSection.slideToggle('fast', function() {
            var isVisible = $commentsSection.is(':visible');
            $this.text(isVisible ? 'Hide Comments' : 'Show Comments');
        });
    });
    // Event handler for adding comments
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
                    console.log("Comment added successfully!"); // Debugging: Log success message
                    var newComment = `<div class="comment">
                        <strong><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>:</strong>
                        <p>${form.find('[name="comment"]').val()}</p>
                    </div>`;
                    commentsSection.append(newComment);
                    form.find('[name="comment"]').val('');
                    window.location.reload();
                } else {
                    console.log("Error adding comment:", response); // Debugging: Log error message
                    alert(response);
                }
            },
            error: function(xhr, status, error) {
                console.log("AJAX Error:", error); // Debugging: Log AJAX error
            }
        });
    });

    // Event handler for voting buttons
    $('body').on('click', '.vote-btn', function() {
        var postId = $(this).data('post');
        var voteType = $(this).hasClass('upvote-btn') ? 1 : -1;
        vote(postId, voteType);
    });

    // Event handler for deleting posts
    $('body').on('click', '.delete-post-btn', function() {
        var postId = $(this).data('post');
        deletePost(postId);
    });

    // Event handler for deleting comments
    $('body').on('click', '.delete-comment-btn', function() {
        var commentId = $(this).data('comment');
        deleteComment(commentId);
    });
});


    function vote(postId, voteType) {
        $.ajax({
            type: 'POST',
            url: 'vote_handler.php',
            data: { post_id: postId, vote_type: voteType },
            dataType: 'json',
            success: function(response) {
                if (response.logged_in && response.message === "Vote successfully recorded.") {
                    $('#vote-count-' + postId).text(response.new_count);
                    reorderPosts();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred while processing your vote.');
            }
        });
    }

   function deletePost(postId) {
    $.ajax({
        type: 'POST',
        url: 'delete_post.php',
        data: { post_id: postId },
        success: function(response) {
            if (response === 'Post deleted.') {
                $('#post-' + postId).fadeOut(500, function() { $(this).remove(); });
                window.location.reload();
            } else {
                console.log('Error: ' + response); // Change from alert to console log
            }
        }
    });
}

function deleteComment(commentId) {
    $.ajax({
        type: 'POST',
        url: 'delete_comment.php',
        data: { comment_id: commentId },
        success: function(response) {
            if (response === 'Comment deleted.') {
                $('#comment-' + commentId).fadeOut(500, function() { $(this).remove(); });
                window.location.reload();
            } else {
                console.log('Error: ' + response); // Change from alert to console log
            }
        }
    });
}

    function reorderPosts() {
        let posts = $('.post').get();
        posts.sort((a, b) => {
            const votesA = parseInt($(a).find('.vote-count').text());
            const votesB = parseInt($(b).find('.vote-count').text());
            return votesB - votesA;
        });
        $('#posts-container').empty().append(posts);
    }
</script>
</body>
</html>

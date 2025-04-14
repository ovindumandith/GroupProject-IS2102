

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="../../assets/css/comment_post.css">
</head>
<body>
<main class="create-whole-container">
        <div class="header">
            <div class="header-left">
                <img src="../../assets/images/editpost.png" alt="Header Image" class="header-image">
            </div>
            <div class="header-right">
            <button class="c-post-btn" onclick="window.location.href='../controller/CommunityController.php?action=list';">Back to Community</button>
                <br><br><h1>Comment Section</h1>
                <hr>
            </div>
        </div>

<div class="create-post-container">
  <div class="comment-card">
      <img src="../../assets/images/Account.png" class="profile-img" />
      <input type="text" class="username-btn " value="1" readonly />
    </div>
    <div class="comment-body">
      <p class="comment-text">Greate Explanation 🎉🎉</p>
      <form>
             <textarea placeholder="Reply to this comment..." required></textarea>
            <button type="submit" class="add-post-btn">Reply</button>
        </form>
    </div>
<br>
  <form class="description-input">
  <div class="comment-card">
      <img src="../../assets/images/Account.png" class="profile-img" />
      <input type="text" class="username-btn " value="1" readonly />
    </div>
    <textarea placeholder="Add your comment..." required></textarea>
    <button type="submit" class="add-post-btn">Add Comment</button>
  </form>
</div>


    </main>
</body>
</html>




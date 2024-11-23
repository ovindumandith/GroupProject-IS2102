<?php
// Get the review details from the database using the review_id from GET
$reviewId = $_GET['review_id'];
$review = getReviewById($reviewId); // Function to fetch the review details
?>

<form action="ReviewController.php?action=updateReview" method="POST">
    <input type="hidden" name="review_id" value="<?= htmlspecialchars($review['id']) ?>">
    <label for="rating">Rating:</label>
    <select name="rating" id="rating" required>
        <option value="5" <?= $review['rating'] == 5 ? 'selected' : '' ?>>⭐️⭐️⭐️⭐️⭐️</option>
        <option value="4" <?= $review['rating'] == 4 ? 'selected' : '' ?>>⭐️⭐️⭐️⭐️</option>
        <option value="3" <?= $review['rating'] == 3 ? 'selected' : '' ?>>⭐️⭐️⭐️</option>
        <option value="2" <?= $review['rating'] == 2 ? 'selected' : '' ?>>⭐️⭐️</option>
        <option value="1" <?= $review['rating'] == 1 ? 'selected' : '' ?>>⭐️</option>
    </select>
    
    <label for="review_text">Review:</label>
    <textarea name="review_text" id="review_text" rows="4" required><?= htmlspecialchars($review['review_text']) ?></textarea>
    
    <button type="submit" class="submit-review-button">Update Review</button>
</form>

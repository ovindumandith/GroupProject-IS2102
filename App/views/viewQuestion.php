<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Question</title>
    <link rel="stylesheet" href="../public/css/styles.css"> <!-- Adjust the path based on your project structure -->
</head>
<body>
    <div class="container">
        <h1>Academic Question Details</h1>

        <?php if (isset($data) && !empty($data)): ?>
            <!-- Display Question Details -->
            <div class="question-details">
                <h2>Question Information</h2>
                <p><strong>Question ID:</strong> <?php echo htmlspecialchars($data[0]['question_id']); ?></p>
                <p><strong>Question:</strong> <?php echo htmlspecialchars($data[0]['question']); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($data[0]['question_status']); ?></p>
                <p><strong>Created At:</strong> <?php echo htmlspecialchars($data[0]['question_created_at']); ?></p>
                <p><strong>Last Updated:</strong> <?php echo htmlspecialchars($data[0]['question_updated_at']); ?></p>
            </div>

            <!-- Display Responses -->
            <div class="responses-section">
                <h2>Responses</h2>
                <?php 
                $responses = array_filter($data, function($entry) {
                    return !empty($entry['response_id']);
                });

                if (!empty($responses)): ?>
                    <ul class="responses-list">
                        <?php foreach ($responses as $response): ?>
                            <li>
                                <p><strong>Response ID:</strong> <?php echo htmlspecialchars($response['response_id']); ?></p>
                                <p><strong>Response:</strong> <?php echo htmlspecialchars($response['response']); ?></p>
                                <p><strong>Admin ID:</strong> <?php echo htmlspecialchars($response['admin_id']); ?></p>
                                <p><strong>Created At:</strong> <?php echo htmlspecialchars($response['response_created_at']); ?></p>
                                <p><strong>Last Updated:</strong> <?php echo htmlspecialchars($response['response_updated_at']); ?></p>
                            </li>
                            <hr>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No responses available for this question.</p>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <p class="error-message">No question details found or invalid request.</p>
        <?php endif; ?>

        <!-- Back Button -->
        <div class="back-btn">
            <a href="../controller/AcademicQuestionController.php?action=viewAllQuestions" class="btn">Back to All Questions</a>
        </div>
    </div>
</body>
</html>

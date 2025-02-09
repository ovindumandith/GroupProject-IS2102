

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Help Questions</title>
    <link rel="stylesheet" href="../../assets/css/style.css"> <!-- Update path if needed -->
</head>
<body>

<h2>Academic Help Questions</h2>

<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Student Name</th>
            <th>Question</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Updated At</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($questions)): ?>
            <?php foreach ($questions as $question): ?>
                <tr>
                    <td><?php echo htmlspecialchars($question['id']); ?></td>
                    <td><?php echo htmlspecialchars($question['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($question['question']); ?></td>
                    <td><?php echo htmlspecialchars($question['status']); ?></td>
                    <td><?php echo htmlspecialchars($question['created_at']); ?></td>
                    <td><?php echo htmlspecialchars($question['updated_at']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No questions found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>

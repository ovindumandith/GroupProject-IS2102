// Wait for the DOM to fully load
document.addEventListener('DOMContentLoaded', () => {
    // Select DOM elements
    const uploadInput = document.getElementById('upload-input');
    const addPostBtn = document.querySelector('.add-post-btn');
    const titleInput = document.querySelector('.title-input');
    const descriptionInput = document.querySelector('.description-input');
    const usernameInput = document.querySelector('.username-btn');
    const profileSection = document.querySelector('.profile-section');

    // Function to display selected image name
    uploadInput.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            alert(`Selected image: ${file.name}`);
        }
    });

    // Add post button click event
    addPostBtn.addEventListener('click', (event) => {
        event.preventDefault(); // Prevent default form submission behavior

        const username = usernameInput.value.trim();
        const title = titleInput.value.trim();
        const description = descriptionInput.value.trim();

        if (!username) {
            alert('Please enter your User ID.');
            return;
        }

        if (!title) {
            alert('Please add a title for your post.');
            return;
        }

        if (!description) {
            alert('Please add a description for your post.');
            return;
        }

        if (!uploadInput.files.length) {
            alert('Please upload an image.');
            return;
        }

        // If all fields are valid, handle the post submission
        alert('Your post has been created successfully!');
        console.log({
            userId: username,
            title: title,
            description: description,
            image: uploadInput.files[0],
        });

        // Clear inputs after successful submission
        usernameInput.value = '';
        titleInput.value = '';
        descriptionInput.value = '';
        uploadInput.value = '';
    });

    // Handle profile section button clicks
    profileSection.addEventListener('click', (event) => {
        const target = event.target.closest('button');

        if (target) {
            const actionImage = target.querySelector('img');
            if (actionImage) {
                const action = actionImage.alt;
                if (action === 'Edit') {
                    alert('Edit your details.');
                } else if (action === 'Delete') {
                    if (confirm('Are you sure you want to delete?')) {
                        alert('Delete successfully!');
                    }
                }
            }
        }
    });
});

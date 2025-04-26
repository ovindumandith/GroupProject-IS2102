document.getElementById('image_url').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('newImagePreview');
    const label = document.querySelector('.file-input-label');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            label.textContent = file.name;
            label.classList.add('file-selected');
        };
        reader.readAsDataURL(file);
    } else {
        preview.src = '#';
        preview.style.display = 'none';
        label.textContent = 'Choose New Image';
        label.classList.remove('file-selected');
    }
});
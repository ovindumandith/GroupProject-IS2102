document.getElementById('image_url').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('newImagePreview');
    
    if (file) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            
            // Hide current image preview if exists
            const currentPreview = document.querySelector('.image-preview');
            if (currentPreview) {
                currentPreview.style.display = 'none';
                document.querySelector('.current-image-label').style.display = 'none';
            }
        }
        
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
        preview.src = '#';
        
        // Show current image preview again
        const currentPreview = document.querySelector('.image-preview');
        if (currentPreview) {
            currentPreview.style.display = 'block';
            document.querySelector('.current-image-label').style.display = 'block';
        }
    }
});
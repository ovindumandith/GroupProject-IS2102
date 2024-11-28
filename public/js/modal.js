class Modal {
    constructor() {
        this.modal = null;
        this.overlay = null;
        this.closeBtn = null;
        this.init();
    }

    init() {
        // Create modal elements
        this.modal = document.createElement('div');
        this.modal.className = 'modal';
        this.overlay = document.createElement('div');
        this.overlay.className = 'modal-overlay';
        
        // Add close button
        this.closeBtn = document.createElement('button');
        this.closeBtn.className = 'modal-close';
        this.closeBtn.innerHTML = '×';
        
        // Event listeners
        this.closeBtn.addEventListener('click', () => this.close());
        this.overlay.addEventListener('click', () => this.close());
        
        // Append elements
        this.modal.appendChild(this.closeBtn);
        document.body.appendChild(this.overlay);
        document.body.appendChild(this.modal);
        
        // Hide initially
        this.close();
    }

    open(content, type) {
        this.modal.classList.add(`modal-${type}`);
        const contentDiv = document.createElement('div');
        contentDiv.className = 'modal-content';
        contentDiv.innerHTML = content;
        this.modal.appendChild(contentDiv);
        
        this.modal.style.display = 'block';
        this.overlay.style.display = 'block';
        setTimeout(() => {
            this.modal.classList.add('active');
            this.overlay.classList.add('active');
        }, 10);
    }

    close() {
        this.modal.classList.remove('active');
        this.overlay.classList.remove('active');
        setTimeout(() => {
            this.modal.style.display = 'none';
            this.overlay.style.display = 'none';
            this.modal.innerHTML = '';
            this.modal.appendChild(this.closeBtn);
        }, 300);
    }
}
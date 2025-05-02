document.addEventListener('DOMContentLoaded', function () {
    // DOM Elements
    const editBtn = document.getElementById('edit-btn');
    const editModal = document.getElementById('edit-modal');
    const cancelBtn = document.querySelector('.cancel-btn');
    const profileForm = document.getElementById('profile-form');
    const photoUpload = document.getElementById('profile_pic');
    const photoPreview = document.querySelector('.profile-pic');
    const changePicBtn = document.querySelector('.change-pic-btn');

    // Toast Notification System
    const showToast = (message, type = 'info') => {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;

        const icons = {
            success: '✓',
            error: '✕',
            info: 'ℹ'
        };

        toast.innerHTML = `
            <span class="toast-icon">${icons[type] || icons.info}</span>
            <span class="toast-message">${message}</span>
            <button class="toast-close">&times;</button>
        `;

        document.body.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 10);

        const dismissTimer = setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 4000);

        toast.querySelector('.toast-close').addEventListener('click', () => {
            clearTimeout(dismissTimer);
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        });
    };

    // Check URL for success/error messages
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success')) {
        showToast('Profil mis à jour avec succès', 'success');
        window.history.replaceState({}, document.title, window.location.pathname);
    } else if (urlParams.has('error')) {
        showToast(urlParams.get('error') || 'Erreur lors de la mise à jour', 'error');
        window.history.replaceState({}, document.title, window.location.pathname);
    }

    // Photo Upload Preview with Validation
    if (photoUpload && photoPreview) {
        photoUpload.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const validTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                showToast('Format d\'image non supporté (JPEG, PNG, WebP seulement)', 'error');
                e.target.value = '';
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                showToast('L\'image ne doit pas dépasser 2MB', 'error');
                e.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = (event) => {
                photoPreview.src = event.target.result;
                showToast('Photo de profil mise à jour', 'success');
            };
            reader.onerror = () => showToast('Erreur de lecture du fichier', 'error');
            reader.readAsDataURL(file);
        });

        if (changePicBtn) {
            changePicBtn.addEventListener('click', (e) => {
                e.preventDefault();
                photoUpload.click();
            });
        }
    }

    // Modal Handling
if (editBtn && editModal) {
    editBtn.addEventListener('click', () => {
        editModal.style.display = 'flex';
        document.body.style.position = 'fixed'; // Prevent body scroll
        document.body.style.width = '100%'; // Prevent layout shift
    });

    if (cancelBtn) {
        cancelBtn.addEventListener('click', () => {
            editModal.style.display = 'none';
            document.body.style.position = ''; // Restore body scroll
            document.body.style.width = ''; // Restore width
        });
    }

    editModal.addEventListener('click', (e) => {
        if (e.target === editModal) {
            editModal.style.display = 'none';
            document.body.style.position = ''; // Restore body scroll
            document.body.style.width = ''; // Restore width
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && editModal.style.display === 'flex') {
            editModal.style.display = 'none';
            document.body.style.position = ''; // Restore body scroll
            document.body.style.width = ''; // Restore width
        }
    });
}

    // Form Handling with Loading State
    if (profileForm) {
        profileForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const saveBtn = profileForm.querySelector('.save-btn');
            const originalBtnText = saveBtn.innerHTML;

            saveBtn.innerHTML = `
                <span class="spinner"></span>
                Enregistrement...
            `;
            saveBtn.disabled = true;

            try {
                const requiredFields = ['date_of_birth', 'blood_type'];
                let isValid = true;

                requiredFields.forEach(field => {
                    const input = profileForm.querySelector(`[name="${field}"]`);
                    if (!input || !input.value.trim()) {
                        input?.classList.add('error');
                        isValid = false;
                    } else {
                        input.classList.remove('error');
                    }
                });

                if (!isValid) {
                    showToast('Veuillez remplir tous les champs obligatoires', 'error');
                    return;
                }

                profileForm.submit();
            } catch (error) {
                console.error('Form submission error:', error);
                showToast('Une erreur est survenue', 'error');
            } finally {
                saveBtn.innerHTML = originalBtnText;
                saveBtn.disabled = false;
            }
        });
    }

    // Dynamic Textarea Handling
    const setupDynamicTextareas = () => {
        document.querySelectorAll('textarea[name^="allergies"], textarea[name^="chronic_conditions"]').forEach(textarea => {
            textarea.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    const cursorPos = this.selectionStart;
                    const textBefore = this.value.substring(0, cursorPos);
                    const textAfter = this.value.substring(cursorPos);

                    this.value = textBefore + '\n' + textAfter;
                    this.selectionStart = this.selectionEnd = cursorPos + 1;
                }
            });

            textarea.addEventListener('input', function () {
                this.style.height = 'auto';
                this.style.height = `${this.scrollHeight}px`;
            });

            textarea.dispatchEvent(new Event('input'));
        });
    };

    setupDynamicTextareas();
});

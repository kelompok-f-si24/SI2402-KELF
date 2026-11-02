/**
 * Small UI behaviours shared by every page: confirmation modals,
 * mobile navigation, submit loading states, and dismissible alerts.
 */

const openModal = (modal) => {
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('overflow-hidden');
    modal.querySelector('[data-modal-focus]')?.focus();
};

const closeModal = (modal) => {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.classList.remove('overflow-hidden');
};

document.addEventListener('click', (event) => {
    const opener = event.target.closest('[data-modal-open]');

    if (opener) {
        event.preventDefault();
        const modal = document.getElementById(opener.dataset.modalOpen);

        if (modal) {
            openModal(modal);
        }

        return;
    }

    const closer = event.target.closest('[data-modal-close]');

    if (closer) {
        event.preventDefault();
        closeModal(closer.closest('[data-modal]'));

        return;
    }

    if (event.target.matches('[data-modal]')) {
        closeModal(event.target);
    }

    const toggle = event.target.closest('[data-toggle]');

    if (toggle) {
        toggle.dataset.toggle.split(' ').forEach((id) => {
            const target = document.getElementById(id);
            target?.classList.toggle(target.dataset.toggleClass ?? 'hidden');
        });
    }

    const dismiss = event.target.closest('[data-dismiss]');

    if (dismiss) {
        dismiss.closest('[data-alert]')?.remove();
    }
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        document.querySelectorAll('[data-modal].flex').forEach(closeModal);
    }
});

document.addEventListener('submit', (event) => {
    const form = event.target;

    if (event.defaultPrevented || !form.checkValidity()) {
        return;
    }

    form.querySelectorAll('button[type="submit"]').forEach((button) => {
        button.disabled = true;
        button.dataset.originalText = button.innerHTML;
        button.innerHTML = `<svg class="size-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"></circle><path fill="currentColor" class="opacity-75" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg> Memproses...`;
    });
});

window.addEventListener('pageshow', () => {
    document.querySelectorAll('button[data-original-text]').forEach((button) => {
        button.disabled = false;
        button.innerHTML = button.dataset.originalText;
    });
});

document.querySelectorAll('[data-alert][data-autohide]').forEach((alert) => {
    setTimeout(() => alert.remove(), 5000);
});

document.querySelectorAll('[data-file-input]').forEach((input) => {
    input.addEventListener('change', () => {
        const target = document.getElementById(input.dataset.fileInput);

        if (target) {
            target.textContent = input.files[0]?.name ?? 'Belum ada file dipilih';
        }
    });
});

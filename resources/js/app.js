import Swal from 'sweetalert2/dist/sweetalert2.esm.js';
import 'sweetalert2/dist/sweetalert2.min.css';

// Um único listener também atende formulários carregados por wire:navigate.
const pendingForms = new WeakSet();

document.addEventListener('submit', async (event) => {
    const form = event.target;

    if (!(form instanceof HTMLFormElement) || !form.matches('[data-confirm-delete]')) {
        return;
    }

    event.preventDefault();

    if (pendingForms.has(form)) {
        return;
    }

    pendingForms.add(form);

    try {
        const result = await Swal.fire({
            title: 'Confirmar exclusão',
            text: form.dataset.confirmDelete,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, excluir',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc2626',
            focusCancel: true,
            theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
        });

        if (result.isConfirmed && form.isConnected) {
            form.querySelectorAll('button[type="submit"]').forEach((button) => {
                button.disabled = true;
            });

            // Envia o POST com CSRF e _method=DELETE sem abrir a confirmação novamente.
            HTMLFormElement.prototype.submit.call(form);
        }
    } finally {
        pendingForms.delete(form);
    }
});

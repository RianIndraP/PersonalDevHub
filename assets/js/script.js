/* ── Modal ── */
function openModal() {
    document.getElementById('modal-backdrop').classList.remove('hidden');
    document.getElementById('modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('modal-backdrop').classList.add('hidden');
    document.getElementById('modal').classList.add('hidden');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeModal();
});
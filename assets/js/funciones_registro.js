const form_registro = document.getElementById('form_registro');
const modal_registro = document.getElementById('modal_registro');
const input_nombre = document.getElementById('nombre');


modal_registro.addEventListener('hidden.bs.modal', function () {
    form_registro.reset()
});
modal_registro.addEventListener('shown.bs.modal', function () {
    input_nombre.focus()
});
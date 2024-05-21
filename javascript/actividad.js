

document.addEventListener('DOMContentLoaded', function() {

    var btnComentar = document.getElementById('cmt');
    var panelComentarios = document.getElementById('panelComentarios');
    var btnCerrarPanel = document.getElementById('cerrarPanel');

    btnComentar.addEventListener('click', function(event) {
        event.preventDefault();
        panelComentarios.style.display = 'block';
        panelComentarios.style.transition = 'right 0.5s ease';
        panelComentarios.style.right = '-1500px'; // Ajusta la posición desde la derecha
        setTimeout(function() {
            panelComentarios.style.right = '0'; // Desliza el panel desde la derecha
        }, 100); // Espera 100 milisegundos antes de deslizar el panel
    });

    btnCerrarPanel.addEventListener('click', function() {
        panelComentarios.style.transition = 'right 0.5s ease';
        panelComentarios.style.right = '-1500px'; // Oculta el panel deslizándolo hacia la derecha
        setTimeout(function() {
            panelComentarios.style.display = 'none'; // Oculta el panel después de la animación
        }, 500); // Espera 500 milisegundos para asegurar que la animación termine
    }); 

    var formularioComentario = document.getElementById('formularioComentario');
    var textoComentario = document.getElementById('textoComentario');


    // Agregar evento input al campo de texto
    textoComentario.addEventListener('input', function() {
        var texto = this.value;
        palabrasProhibidas.forEach(function(palabra) {
            var regex = new RegExp('\\b' + palabra + '\\b', 'gi'); // Utilizar límites de palabra (\b) para coincidir con palabras completas
            texto = texto.replace(regex, function(match) {
                return '*'.repeat(match.length);
            });
        });
        this.value = texto; // Actualiza el valor del campo de texto con el texto modificado
    });


});
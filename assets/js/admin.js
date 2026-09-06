jQuery(document).ready(function($) {
    // Agregar dinámicamente campos de marcación
    $('#vidchlog-add-marker').on('click', function(e) {
        e.preventDefault();
        
        // Se define el HTML completo incluyendo la clase del contenedor y el required
        var newMarkerHtml = `
            <div class="vidchlog-marker" style="margin-top: 10px;">
                <input 
                    type="text" 
                    name="marker_time[]" 
                    placeholder="00:00" 
                    style="margin-right: 10px;" 
                    required
                >
                <input 
                    type="text" 
                    name="marker_title[]" 
                    placeholder="Título de la marcación" 
                    class="regular-text" 
                    required
                >
            </div>
        `;

        $('#vidchlog-markers-container').append(newMarkerHtml);
    });

    // Quitar la última marcación agregada
    $('#vidchlog-remove-marker').on('click', function(e) {
        e.preventDefault();
        
        var markers = $('#vidchlog-markers-container .vidchlog-marker');
        if (markers.length > 1) {
            markers.last().remove();
        }
    });
// Validación para el formulario
    $('form').on('submit', function(e) {
        var camposVacios = false;

        // Revisamos cada input de tiempo y título dinámico
        $('#vidchlog-markers-container input').each(function() {
            if ($.trim($(this).val()) === '') {
                camposVacios = true;
                $(this).focus(); 
                return false; 
            }
        });

        if (camposVacios) {
            e.preventDefault(); // Detiene el envío del formulario
            alert('Por favor, completa todos los campos de las marcaciones (tiempo y título).');
        }
    });
});

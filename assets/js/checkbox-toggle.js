document.addEventListener('DOMContentLoaded', function () {

    const checkbox = document.getElementById('check');
    const inputLogo = document.getElementById('logo');
    const logoContainer = document.getElementById('logo-archivo');
    const markers = document.getElementById('markers');

    const noticeLogo = document.getElementById('notice-logo');
    const noticeExit = document.getElementById('consulta-exit');
    const noticeError = document.getElementById('consulta-error');

    if (!checkbox || !inputLogo || !logoContainer || !markers) {
        return;
    }

    function actualizarFormulario() {

        if (checkbox.checked) {

            // Escenario: video SIN logo ni marcadores.
            logoContainer.classList.add('ocultar');
            markers.classList.add('ocultar');

            inputLogo.removeAttribute('required');

            markers.querySelectorAll('input').forEach(function (input) {
                input.removeAttribute('required');
            });

            // Ocultar avisos que pertenecen al otro escenario.
            if (noticeExit) {
                noticeExit.classList.add('ocultar');
            }

            if (noticeError) {
                noticeError.classList.add('ocultar');
            }

            console.log('SIN LOGO / SIN MARCADORES');

        } else {

            // Escenario: video CON logo y marcadores.
            logoContainer.classList.remove('ocultar');
            markers.classList.remove('ocultar');

            inputLogo.setAttribute('required', '');

            markers.querySelectorAll('input').forEach(function (input) {
                input.setAttribute('required', '');
            });

            // Ocultar aviso del escenario sin logo.
            if (noticeLogo) {
                noticeLogo.classList.add('ocultar');
            }

            console.log('CON LOGO / CON MARCADORES');
        }
    }

    actualizarFormulario();

    checkbox.addEventListener('change', actualizarFormulario);

});
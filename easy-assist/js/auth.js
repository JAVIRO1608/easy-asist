// =============================================
//  EasyAssist · auth.js (Versión Ultra-Segura)
// =============================================

const PHP_AUTH_URL = '../easy-assist/php/auth.php'; 

document.addEventListener('DOMContentLoaded', () => {
    
    // Función centralizada para mostrar alertas en el cuadro del HTML
    function mostrarMensaje(texto, tipo) {
        const msgDiv = document.getElementById('msg');
        if (msgDiv) {
            msgDiv.innerText = texto;
            msgDiv.className = `msg ${tipo}`;
            msgDiv.style.display = 'block'; 
        } else {
            alert(texto);
        }
    }

    // Inicializar y escuchar los clics en todo el documento de forma segura
    document.addEventListener('click', async (e) => {
        
        // -----------------------------------------------------------------
        // EVENTO: BOTÓN ENTRAR (LOGIN)
        // -----------------------------------------------------------------
        if (e.target && e.target.id === 'btn-login') {
            e.preventDefault();

            const emailInput = document.getElementById('login-email');
            const passwordInput = document.getElementById('login-password');

            if (!emailInput || !passwordInput) return;

            const email = emailInput.value.trim();
            const password = passwordInput.value;

            if (!email || !password) {
                mostrarMensaje('Por favor, rellena todos los campos', 'error');
                return;
            }

            try {
                mostrarMensaje('Verificando credenciales...', 'success');
                
                const res = await fetch(PHP_AUTH_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ accion: 'login', email, password })
                });
                
                const data = await res.json();

                if (data.success) {
                    sessionStorage.setItem('usuario_nombre', data.nombre);
                    sessionStorage.setItem('usuario_email', data.email);
                    mostrarMensaje('¡Acceso correcto! Redirigiendo...', 'success');
                    setTimeout(() => { window.location.href = 'presentacion.html'; }, 1000);
                } else {
                    mostrarMensaje(data.error, 'error');
                }
            } catch (error) {
                console.error(error);
                mostrarMensaje('Error de comunicación con el servidor. Revisa las credenciales en auth.php.', 'error');
            }
        }

        // -----------------------------------------------------------------
        // EVENTO: BOTÓN CREAR CUENTA (REGISTRO)
        // -----------------------------------------------------------------
        if (e.target && e.target.id === 'btn-register') {
            e.preventDefault();

            const nombreInput = document.getElementById('reg-name');
            const emailInput = document.getElementById('reg-email');
            const passInput = document.getElementById('reg-password');
            const pass2Input = document.getElementById('reg-password2');

            if (!nombreInput || !emailInput || !passInput || !pass2Input) return;

            const nombre = nombreInput.value.trim();
            const email = emailInput.value.trim();
            const password = passInput.value;
            const password2 = pass2Input.value;

            if (!nombre || !email || !password || !password2) {
                mostrarMensaje('Todos los campos son obligatorios', 'error');
                return;
            }

            if (password !== password2) {
                mostrarMensaje('Las contraseñas no coinciden', 'error');
                return;
            }

            try {
                mostrarMensaje('Procesando registro...', 'success');

                const res = await fetch(PHP_AUTH_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ accion: 'registrar', nombre, email, password })
                });
                
                const data = await res.json();

                if (data.success) {
                    sessionStorage.setItem('usuario_nombre', data.nombre);
                    sessionStorage.setItem('usuario_email', data.email);
                    mostrarMensaje('¡Cuenta creada con éxito! Entrando...', 'success');
                    setTimeout(() => { window.location.href = 'presentacion.html'; }, 1200);
                } else {
                    mostrarMensaje(data.error, 'error');
                }
            } catch (error) {
                console.error(error);
                mostrarMensaje('Error en la respuesta del servidor.', 'error');
            }
        }
    });
});

// js/navegacion.js
const PHP_NAV_URL = '../php/navegacion.php';

async function navegar(paginaActualId, boton) {
  try {
    const res = await fetch(PHP_NAV_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ pagina_actual_id: paginaActualId, boton: boton })
    });

    if (!res.ok) {
      const err = await res.json();
      alert('Error: ' + (err.message || 'No se pudo mapear la acción.'));
      return;
    }

    const destino = await res.json();
    if (destino && destino.ruta) {
      document.body.classList.add('fade-out');
      setTimeout(() => {
        window.location.href = '../' + destino.ruta;
      }, 300);
    }
  } catch (e) {
    console.error(e);
    alert('Error de comunicación de red con el motor de transiciones.');
  }
}

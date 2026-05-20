// =============================================
//  EasyAssist · navegacion.js
//  Coloca este archivo en: js/navegacion.js
// =============================================

const PHP_URL = '../php/navegacion.php';

/**
 * Navega a la siguiente página consultando la BD.
 * @param {number} paginaActualId - ID de la página actual
 * @param {string} boton - Botón pulsado
 */
async function navegar(paginaActualId, boton) {
  try {
    const res = await fetch(PHP_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ pagina_actual_id: paginaActualId, boton: boton })
    });

    if (!res.ok) {
      const err = await res.json();
      console.error('EasyAssist - Error navegación:', err);
      return;
    }

    const destino = await res.json();
    document.body.classList.add('fade-out');
    setTimeout(() => {
      window.location.href = '/' + destino.ruta;
    }, 300);

  } catch (e) {
    console.error('EasyAssist - Error de red:', e);
  }
}

/**
 * Construye el nombre del botón para roaming según modo y marca guardados.
 * Uso: navegar(ID, botonRoaming('activar', 'samsung')) → 'samsung_activar'
 */
function botonRoaming(accion, marca) {
  return marca + '_' + accion;
}

/**
 * Construye el nombre del botón para datos según tipo guardado.
 * Uso: navegar(ID, botonDatos('iphone')) → 'iphone_sin' o 'iphone_lento'
 */
function botonDatos(marca) {
  const tipo = sessionStorage.getItem('datos_tipo') || 'sin';
  return marca + '_' + tipo;
}

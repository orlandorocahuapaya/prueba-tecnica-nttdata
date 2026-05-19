const clientsContainer = document.getElementById('clients');
const statusEl = document.getElementById('status');
const errorEl = document.getElementById('error');
const reloadBtn = document.getElementById('reloadBtn');
const apiBaseUrl = 'http://localhost:8000';

function renderClients(clients) {
  if (!Array.isArray(clients) || clients.length === 0) {
    clientsContainer.innerHTML = '<p class="status">No hay clientes para mostrar.</p>';
    return;
  }

  clientsContainer.innerHTML = clients.map((client) => {
    const location = client.location || {};
    const city = [location.city, location.state, location.country].filter(Boolean).join(', ');

    return `
      <article class="card">
        <div class="header">
          <img class="avatar" src="${client.photo || ''}" alt="Foto de ${client.name || 'cliente'}" />
          <div>
            <h2 class="name">${client.name || 'Sin nombre'}</h2>
            <p class="gender">${client.gender || 'No especificado'}</p>
          </div>
        </div>
        <p class="field"><span class="label">Correo:</span>${client.email || '-'}</p>
        <p class="field"><span class="label">Nacimiento:</span>${client.birth_date || '-'}</p>
        <p class="field"><span class="label">Ubicación:</span>${city || '-'}</p>
      </article>
    `;
  }).join('');
}

async function loadClients() {
  statusEl.textContent = 'Cargando...';
  errorEl.style.display = 'none';
  clientsContainer.innerHTML = '';

  try {
    const response = await fetch(`${apiBaseUrl}/api/client/random_clients?results=10`);
    if (!response.ok) {
      throw new Error(`Error HTTP ${response.status}`);
    }

    const clients = await response.json();
    renderClients(clients);
    statusEl.textContent = '';
  } catch (error) {
    statusEl.textContent = 'No se pudo cargar el listado';
    errorEl.textContent = `Detalle: ${error.message}`;
    errorEl.style.display = 'block';
  }
}

reloadBtn.addEventListener('click', loadClients);
loadClients();

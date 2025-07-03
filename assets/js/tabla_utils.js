window.ordenarPorColumna = function(tbodySelector, colIndex, iconId, buscadorId, filasPorPagina, paginacionId) {
  const icon = document.getElementById(iconId);
  const tbody = document.querySelector(tbodySelector);
  let asc = icon.getAttribute('data-asc') !== 'false';
  const filas = Array.from(tbody.querySelectorAll('tr'));
  filas.sort((a, b) => {
    const valA = a.children[colIndex].textContent.trim();
    const valB = b.children[colIndex].textContent.trim();
    if (!isNaN(valA) && !isNaN(valB)) {
      return asc ? valA - valB : valB - valA;
    }
    return asc ? valA.localeCompare(valB) : valB.localeCompare(valA);
  });
  filas.forEach(tr => tbody.appendChild(tr));
  asc = !asc;
  icon.setAttribute('data-asc', asc);
  icon.textContent = asc ? '↑' : '↓';
  if (typeof window.mostrarPaginaTabla === 'function') {
    window.mostrarPaginaTabla(tbodySelector, buscadorId, filasPorPagina, 1, paginacionId);
  }
}

window.mostrarPaginaTabla = function(tbodySelector, buscadorId, filasPorPagina, pagina, paginacionId) {
  const filas = Array.from(document.querySelectorAll(`${tbodySelector} tr`));
  const filtro = document.getElementById(buscadorId).value.toLowerCase();
  const filasFiltradas = filas.filter(tr => tr.textContent.toLowerCase().includes(filtro));
  filas.forEach(tr => tr.style.display = 'none');
  const inicio = (pagina - 1) * filasPorPagina;
  const fin = inicio + filasPorPagina;
  filasFiltradas.slice(inicio, fin).forEach(tr => tr.style.display = '');
  window.actualizarPaginacionTabla(filasFiltradas.length, pagina, filasPorPagina, paginacionId, (nuevaPag) => {
    window.mostrarPaginaTabla(tbodySelector, buscadorId, filasPorPagina, nuevaPag, paginacionId);
  });
};

window.actualizarPaginacionTabla = function(totalFilas, pagina, filasPorPagina, paginacionId, onPageChange) {
  const totalPaginas = Math.ceil(totalFilas / filasPorPagina) || 1;
  const paginacion = document.getElementById(paginacionId);
  paginacion.innerHTML = '';
  if (totalPaginas <= 1) return;
  const btnPrev = document.createElement('button');
  btnPrev.textContent = 'Anterior';
  btnPrev.disabled = pagina === 1;
  btnPrev.className = 'px-3 py-1 rounded bg-gray-200 mx-1 disabled:opacity-50' + (btnPrev.disabled ? '' : ' cursor-pointer');
  btnPrev.onclick = () => onPageChange(pagina - 1);
  paginacion.appendChild(btnPrev);
  for (let i = 1; i <= totalPaginas; i++) {
    const btn = document.createElement('button');
    btn.textContent = i;
    btn.className = 'px-3 py-1 rounded mx-1 ' + (i === pagina ? 'bg-blue-500 text-white' : 'bg-gray-200 cursor-pointer');
    btn.onclick = () => onPageChange(i);
    paginacion.appendChild(btn);
  }
  const btnNext = document.createElement('button');
  btnNext.textContent = 'Siguiente';
  btnNext.disabled = pagina === totalPaginas;
  btnNext.className = 'px-3 py-1 rounded bg-gray-200 mx-1 disabled:opacity-50' + (btnNext.disabled ? '' : ' cursor-pointer');
  btnNext.onclick = () => onPageChange(pagina + 1);
  paginacion.appendChild(btnNext);
};
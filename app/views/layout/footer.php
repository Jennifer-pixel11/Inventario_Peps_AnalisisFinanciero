</div> <!-- /container -->

<footer class="site-footer text-center py-3 mt-auto">
  <div class="container small text-muted">
    <div class="fw-semibold">Inventario PEPS · Control de Existencias de Productos</div>
    <div>© <?php echo date('Y'); ?> — Actualizado: <span id="live-time"></span></div>
    <div class="opacity-75">CICLO II -2025 ANALISIS FINANCIERO TEORICO 1</div>
  </div>
</footer>

<script>
function updateClock(){
  const now = new Date();
  const options = { hour: '2-digit', minute: '2-digit', second:'2-digit', hour12: true };
  const date = now.toLocaleDateString('es-SV');
  const time = now.toLocaleTimeString('es-SV', options);
  document.getElementById('live-time').textContent = date + " · " + time;
}
setInterval(updateClock, 1000);
updateClock();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

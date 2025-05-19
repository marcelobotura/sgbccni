<!-- Alternar tema claro/escuro -->
<script>
function alternarTema() {
  const isLight = document.body.classList.contains('light-mode');
  document.cookie = "modo_tema=" + (isLight ? "dark" : "light") + "; path=/";
  location.reload();
}
</script>

<!-- Scripts do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

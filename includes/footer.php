<script>
function alternarTema() {
  const isLight = document.body.classList.contains('light-mode');
  document.cookie = "modo_tema=" + (isLight ? "dark" : "light") + "; path=/";
  location.reload();
}
</script>
</body>
</html>


  </div> <!-- fecha container -->

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?= URL_BASE ?>assets/js/scripts.js"></script>

  <!-- Alternância de tema -->
  <script>
    function alternarTema() {
      const base = document.getElementById("tema-base");
      const medio = document.getElementById("tema-medio");
      const dark = document.getElementById("tema-dark");

      if (!medio.disabled) {
        medio.disabled = true;
        dark.disabled = false;
        localStorage.setItem("tema", "dark");
      } else if (!dark.disabled) {
        dark.disabled = true;
        base.disabled = false;
        localStorage.setItem("tema", "base");
      } else {
        base.disabled = true;
        medio.disabled = false;
        localStorage.setItem("tema", "medio");
      }
    }

    window.addEventListener('DOMContentLoaded', () => {
      const tema = localStorage.getItem("tema");
      if (tema === "medio") {
        document.getElementById("tema-base").disabled = true;
        document.getElementById("tema-medio").disabled = false;
      } else if (tema === "dark") {
        document.getElementById("tema-base").disabled = true;
        document.getElementById("tema-dark").disabled = false;
      }
    });
  </script>
</body>
</html>

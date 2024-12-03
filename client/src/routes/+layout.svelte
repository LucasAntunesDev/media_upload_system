<script lang="ts">
  import '../css/app.css'
  let {children} = $props()
</script>

<header
  class="flex items-center justify-between px-10 w-screen border-b-gray-100 border-b-[2px] h-16">
  <nav class="inline-flex gap-3">
    <a href="/">Início</a>
    <a href="images">Imagens</a>
    <a href="videos">Vídeos</a>
  </nav>
</header>

{@render children()}

<footer
  class="flex items-center justify-between px-10 w-screen border-t-gray-100 border-t-[2px] h-16 mt-8">
  <h6>Lucas Antunes, 2024</h6>
</footer>

<script lang="ts">
  import "../css/app.css";
  console.log("hello world");
  let { children } = $props();
</script>

<header
  class="flex items-center justify-between px-10 w-screen border-b-gray-50 border-b-[2px] h-16"
>
  <nav class="inline-flex gap-3">
    <a href="/">Início</a>
    <a href="images">Imagens</a>
    <a href="videos">Vídeos</a>
  </nav>
</header>

{@render children()}

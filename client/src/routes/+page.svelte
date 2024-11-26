<script>
  import "../css/app.css";
  let count = $state(0);
</script>

<button
  onclick={() => count++}
  class="bg-emerald-600 rounded-md px-2 py-1.5 text-white"
>
  clicks: {count}
</button>

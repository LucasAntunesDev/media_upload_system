<script lang="ts">
  import { onMount } from "svelte";
  import api from "../../services/api";

  interface Image {
    id: number;
    url: string;
  }

  let images: Image[] = $state([]);
  const fetchImages = async () => {
    try {
      const response = await api().get<Image[]>("/images/list");
      images = response.data;
    } catch (error) {
      console.error("Erro ao buscar imagens:", error);
    }
  };
  onMount(() => {
    fetchImages();
  });

  let openModal: boolean = $state(false);
</script>

<h1 class="text-4xl flex w-fit font-bold items-center gap-2 mx-auto my-5">
  Página de imagens<svg
    xmlns="http://www.w3.org/2000/svg"
    width="24"
    height="24"
    viewBox="0 0 24 24"
    fill="none"
    stroke="currentColor"
    stroke-width="2"
    stroke-linecap="round"
    stroke-linejoin="round"
    class="lucide lucide-images size-8"
    ><path d="M18 22H4a2 2 0 0 1-2-2V6" /><path
      d="m22 13-1.296-1.296a2.41 2.41 0 0 0-3.408 0L11 18"
    /><circle cx="12" cy="8" r="2" /><rect
      width="16"
      height="16"
      x="6"
      y="2"
      rx="2"
    /></svg
  >
</h1>

<main>
  {#if images.length > 0}
    <div
      class="grid justify-items-center grid-cols-1 md:grid-cols-2 lg:md:grid-cols-3px-10 w-screen gap-2"
    >
      {#each images as image}
        <img src={image.url} alt="" class="rounded max-w-[90%]" />
      {/each}
    </div>
  {:else}
    <p>Carregando imagens...</p>
  {/if}

  {#if openModal}
    <div>Olá</div>
  {/if}
</main>

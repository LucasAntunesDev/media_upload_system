<script lang="ts">
  import {onMount} from 'svelte'
  import api from '../../services/api'
  import axios from 'axios'

  interface Image {
    id: number
    url: string
    isFavourite: boolean
  }

  let images: Image[] = $state([])

  //Carrega as imagens
  const fetchImages = async () => {
    try {
      const response = await api().get<Image[]>('/images/list')
      images = response.data
    } catch (error) {
      console.error('Erro ao buscar imagens:', error)
    }
  }

  onMount(() => {
    fetchImages()
  })

  //Envio da imagem ao backend
  const handleFormSubmit = async (event: Event) => {
    event.preventDefault()

    const formData = new FormData()
    const fileInput = (event.target as HTMLFormElement).url as HTMLInputElement

    if (fileInput.files && fileInput.files.length > 0) {
      formData.append('url', fileInput.files[0])

      try {
        await axios.post('http://localhost:8000/images/create', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        })

        openModal = false
        fetchImages()
      } catch (error) {
        alert('Não foi possível concluir o envio: ' + error)
      }
    } else {
      alert('Por favor, selecione um arquivo para enviar.')
    }
  }

  //Define o estado do modal (aberto ou fechado)
  let openModal: boolean = $state(false)
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
      d="m22 13-1.296-1.296a2.41 2.41 0 0 0-3.408 0L11 18" /><circle
      cx="12"
      cy="8"
      r="2" /><rect width="16" height="16" x="6" y="2" rx="2" /></svg>
</h1>

<button
  onclick={() => (openModal = !openModal)}
  class="bg-emerald-600 px-2 py-1.5 rounded-lg mx-auto w-fit flex text-white"
  >Adicionar nova imagem</button>

{#if openModal}
  <div
    class="w-[50vw] h-fit py-12 px-10 bg-white border-gray-200 border-2 rounded-md z-10 mx-auto absolute inset-0">
    <button onclick={() => (openModal = !openModal)}
      ><svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke-width="1.5"
        stroke="currentColor"
        class="size-6">
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          d="M6 18 18 6M6 6l12 12" />
      </svg>
    </button>

    <h1 class="text-xl">Adicionar imagem</h1>

    <form onsubmit={handleFormSubmit} class="flex flex-col gap-2">
      <input type="file" name="url" id="url" accept="image/*" />

      <button
        type="submit"
        class="bg-emerald-600 px-2 py-1.5 rounded-lg text-white w-fit">
        Manda bala
      </button>
    </form>
  </div>
{/if}

<main>
  {#if images.length > 0}
    <div
      class="grid justify-items-center grid-cols-1 md:grid-cols-2 lg:md:grid-cols-3px-10 w-screen gap-2">
      {#each images as image}
        <div>
          <button
            ><svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
              class="size-6">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
            </svg>
          </button>
          <img
            src={`http://localhost:8000/storage/${image.url}`}
            alt=""
            class="rounded max-w-80" />
          <button>
            {#if !image.isFavourite}
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="size-6">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z" />
              </svg>
            {:else}
              <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="currentColor"
                class="size-6">
                <path
                  fill-rule="evenodd"
                  d="M6.32 2.577a49.255 49.255 0 0 1 11.36 0c1.497.174 2.57 1.46 2.57 2.93V21a.75.75 0 0 1-1.085.67L12 18.089l-7.165 3.583A.75.75 0 0 1 3.75 21V5.507c0-1.47 1.073-2.756 2.57-2.93Z"
                  clip-rule="evenodd" />
              </svg>
            {/if}
          </button>
        </div>
      {/each}
    </div>
  {:else}
    <p>Carregando imagens...</p>
  {/if}
</main>

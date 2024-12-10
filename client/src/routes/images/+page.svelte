<script lang="ts">
  import {onMount} from 'svelte'
  import {get} from 'svelte/store'
  import api from '../../services/api'
  import {auth} from '../../services/auth'
  import Modal from '../../components/Modal.svelte'
  import axios from 'axios'
  interface Image {
    id: number
    url: string
    isFavourite: boolean
  }
  let images: Image[] = []
  let isAuthenticated = get(auth).token !== ''
  let openModal: boolean = false
  let file = null
  let message = ''
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
  const handleFormSubmit = async (event: Event) => {
    event.preventDefault()
    const formData = new FormData()
    if (file) {
      formData.append('url', file)
      try {
        await axios.post('http://localhost:8000/images/create', formData, {
          headers: {'Content-Type': 'multipart/form-data'},
        })
        message = 'Imagem enviada com sucesso!'
        openModal = !openModal
        fetchImages()
        message = ''
        file = ''
      } catch (error) {
        message = 'Erro ao enviar imagem: ' + error
      }
    } else {
      message = 'Por favor, selecione um arquivo para enviar.'
    }
  }
  const deleteImage = async (imageId: number) => {
    try {
      await api().delete(`/images/${imageId}`)
      images = images.filter(image => image.id !== imageId)
    } catch (error) {
      console.error('Erro ao excluir imagem:', error)
    }
  }
</script>

<h1
  class="text-4xl flex w-fit font-bold items-center gap-2 mx-auto my-5 text-primary bg">
  Página de Imagens <svg
    xmlns="http://www.w3.org/2000/svg"
    width="24"
    height="24"
    viewBox="0 0 24 24"
    fill="none"
    stroke="currentColor"
    stroke-width="2"
    stroke-linecap="round"
    stroke-linejoin="round"
    class="lucide lucide-images size-8">
    <path d="M18 22H4a2 2 0 0 1-2-2V6" />
    <path d="m22 13-1.296-1.296a2.41 2.41 0 0 0-3.408 0L11 18" />
    <circle cx="12" cy="8" r="2" />
    <rect width="16" height="16" x="6" y="2" rx="2" />
  </svg>
</h1>
{#if isAuthenticated}
  <button
    onclick={() => (openModal = !openModal)}
    class="flex mx-auto btn-outline mt-4">
    Adicionar Imagem <svg
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      viewBox="0 0 24 24"
      stroke-width="1.5"
      stroke="currentColor"
      class="size-5">
      <path
        stroke-linecap="round"
        stroke-linejoin="round"
        d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
    </svg>
  </button>
  <Modal
    title="Adicionar Imagem"
    {openModal}
    oncloseModal={() => (openModal = !openModal)}
    onsubmit={handleFormSubmit}>
    <div slot="body">
      <form onsubmit={handleFormSubmit} class="flex flex-col gap-2">
        <input
          type="file"
          name="url"
          id="url"
          accept="image/*"
          onchange={e => (file = e.target.files[0])} />
        <button type="submit" class="btn-success w-fit mx-auto">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="size-5">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
          </svg>
          Enviar
        </button>
        {#if message}
          <p>{message}</p>
        {/if}
      </form>
    </div>
  </Modal>
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
          <button
            onclick={() => deleteImage(image.id)}
            class=" text-red-600"
            title="Excluir imagem">
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
                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
            </svg>
          </button>
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

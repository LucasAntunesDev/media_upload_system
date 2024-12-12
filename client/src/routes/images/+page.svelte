<script lang="ts">
  import axios from 'axios'
  import {onMount} from 'svelte'
  import {get} from 'svelte/store'

  import api from '../../services/api'
  import {auth} from '../../services/auth'
  import Modal from '../../components/Modal.svelte'
  interface Image {
    id: number
    url: string
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
      alert(`Erro ao excluir imagem: ${error}`)
    }
  }

  const toggleFavourite = async (imageId: number) => {
    try {
      const response = await api().patch(`/images/${imageId}/favourite`)
      images = images.map(image =>
        image.id === imageId
          ? {...image, isFavourite: response.data.image.isFavourite}
          : image,
      )
    } catch (error) {
      console.error('Erro ao atualizar favorito:', error)
    }
  }
</script>

<div
  class="w-screen flex justify-start items-center gap-16 px-20 py-8 flex-wrap">
  <div class="max-w-[34rem] w-auto">
    <h1
      class="text-4xl flex w-fit font-bold items-center gap-2 my-5 image-gradient-text">
      Imagens
      <svg
        xmlns="http://www.w3.org/2000/svg"
        width="24"
        height="24"
        viewBox="0 0 24 24"
        fill="none"
        stroke="url(#instagram-gradient)"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
        class="lucide lucide-images size-8">
        <defs>
          <linearGradient
            id="instagram-gradient"
            x1="0%"
            y1="100%"
            x2="100%"
            y2="0%">
            <stop offset="0%" style="stop-color:#f58529;stop-opacity:1" />
            <stop offset="25%" style="stop-color:#feda77;stop-opacity:1" />
            <stop offset="50%" style="stop-color:#dd2a7b;stop-opacity:1" />
            <stop offset="75%" style="stop-color:#8134af;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#515bd4;stop-opacity:1" />
          </linearGradient>
        </defs>
        <path d="M18 22H4a2 2 0 0 1-2-2V6" />
        <path d="m22 13-1.296-1.296a2.41 2.41 0 0 0-3.408 0L11 18" />
        <circle cx="12" cy="8" r="2" />
        <rect width="16" height="16" x="6" y="2" rx="2" />
      </svg>
    </h1>
    <p>
      {#if isAuthenticated}
        Essas são as imagens do sistema. Você pode adicionar mais, salvar suas
        favoritas ou exclui-las.
      {:else}
        Essas são as imagens disponíveis no sistema. Faça login para adicionar
        mais
      {/if}
    </p>
  </div>

  {#if isAuthenticated}
    <button onclick={() => (openModal = !openModal)} class="btn-outline mt-4">
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
</div>

<main>
  {#if images.length > 0}
    <div
      class="grid justify-items-center grid-cols-1 md:grid-cols-2 lg:md:grid-cols-3 px-10 w-screen gap-2">
      {#each images as image}
        <div class="relative">
          <img
            src={`http://localhost:8000/storage/${image.url}`}
            alt=""
            class="rounded max-w-80" />

          <!-- {#if isAuthenticated}
            <button
              onclick={() => toggleFavourite(image.id)}
              class="absolute top-2 right-2"
              title="Marcar como favorito">
              {#if !image.isFavourite}
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="size-6 text-gray-400 hover:text-red-500">
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
                  class="size-6 text-red-500">
                  <path
                    fill-rule="evenodd"
                    d="M6.32 2.577a49.255 49.255 0 0 1 11.36 0c1.497.174 2.57 1.46 2.57 2.93V21a.75.75 0 0 1-1.085.67L12 18.089l-7.165 3.583A.75.75 0 0 1 3.75 21V5.507c0-1.47 1.073-2.756 2.57-2.93Z"
                    clip-rule="evenodd" />
                </svg>
              {/if}
            </button>
          {/if} -->

          <button
            onclick={() => deleteImage(image.id)}
            class="text-red-600 mt-2">
            Excluir
          </button>
        </div>
      {/each}
    </div>
  {:else}
    <p>Carregando imagens...</p>
  {/if}
</main>

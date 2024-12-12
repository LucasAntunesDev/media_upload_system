<script lang="ts">
  import {onMount} from 'svelte'
  import {get} from 'svelte/store'
  import api from '../../services/api'
  import {auth} from '../../services/auth'
  import Modal from '../../components/Modal.svelte'
  import axios from 'axios'
  import 'plyr/dist/plyr.css'

  interface Video {
    id: number
    name: string
    url: string
    thumbnail: string
    tags: string
    isFavourite: boolean
    isPrivate: boolean
  }

  let videos: Video[] = []
  let isAuthenticated = get(auth).token !== ''
  let openModal: boolean = false
  let file = null
  let thumbnail = null
  let name = ''
  let message = ''

  const fetchVideos = async () => {
    try {
      const response = await api().get<Video[]>('/videos/list')
      videos = response.data
    } catch (error) {
      console.error('Erro ao buscar vídeos:', error)
    }
  }

  onMount(() => {
    fetchVideos()
  })

  const handleFormSubmit = async (event: Event) => {
    event.preventDefault()

    const formData = new FormData()
    if (file && thumbnail && name) {
      formData.append('url', file)
      formData.append('thumbnail', thumbnail)
      formData.append('name', name)

      try {
        await axios.post('http://localhost:8000/videos/create', formData, {
          headers: {'Content-Type': 'multipart/form-data'},
        })
        message = 'Vídeo enviado com sucesso!'
        openModal = false
        fetchVideos()
        message = ''
        file = null
        thumbnail = null
        name = ''
      } catch (error) {
        message = 'Erro ao enviar vídeo: ' + error
      }
    } else {
      message = 'Por favor, preencha todos os campos.'
    }
  }

  const deleteVideo = async (videoId: number) => {
    try {
      await api().delete(`/videos/${videoId}`)
      videos = videos.filter(video => video.id !== videoId)
    } catch (error) {
      console.error('Erro ao excluir vídeo:', error)
    }
  }
</script>

<h1
  class="text-4xl flex w-fit font-bold items-center gap-2 mx-auto my-5 text-primary">
  Página de vídeos
  <svg
    xmlns="http://www.w3.org/2000/svg"
    width="24"
    height="24"
    viewBox="0 0 24 24"
    fill="none"
    stroke="currentColor"
    stroke-width="2"
    stroke-linecap="round"
    stroke-linejoin="round"
    class="lucide lucide-film size-8">
    <rect width="18" height="18" x="3" y="3" rx="2" />
    <path d="M7 3v18" />
    <path d="M3 7.5h4" />
    <path d="M3 12h18" />
    <path d="M3 16.5h4" />
    <path d="M17 3v18" />
    <path d="M17 7.5h4" />
    <path d="M17 16.5h4" />
  </svg>
</h1>

{#if isAuthenticated}
  <button
    onclick={() => (openModal = !openModal)}
    class="flex mx-auto btn-outline mt-4">
    Adicionar vídeo
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
  </button>
{/if}

{#if message}
  <p class="text-red-600">{message}</p>
{/if}

<Modal
  title="Adicionar vídeo"
  {openModal}
  oncloseModal={() => (openModal = !openModal)}
  onsubmit={handleFormSubmit}>
  <div slot="body">
    <form onsubmit={handleFormSubmit} class="flex flex-col gap-2">
      <label class="flex flex-col">
        Vídeo
        <input
          type="file"
          accept="video/*"
          onchange={e => (file = e.target.files[0])} />
      </label>
      <label class="flex flex-col">
        Thumbnail
        <input
          type="file"
          accept="image/*"
          onchange={e => (thumbnail = e.target.files[0])} />
      </label>
      <input
        type="text"
        placeholder="Nome do vídeo"
        bind:value={name}
        class="text-input" />
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
    </form>
  </div>
</Modal>

<main class="mt-6">
  {#if videos.length > 0}
    <div
      class="grid justify-items-center grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      {#each videos as video}
        <div class="relative aspect-video">
          <a href={`/videos/show/${video.id}`}>
            <img
              src={`http://localhost:8000/storage/${video.thumbnail}`}
              alt={`Thumbnail de ${video.name}`}
              class="w-full h-auto rounded shadow" />
          </a>
          <h1 class="capitalize font-bold text-center mt-2">{video.name}</h1>
          <button
            onclick={() => deleteVideo(video.id)}
            class="text-red-600 mt-2 block mx-auto"
            aria-label="Excluir vídeo"
            title="Excluir vídeo">
            Excluir
          </button>
        </div>
      {/each}
    </div>
  {:else}
    <p>Carregando vídeos...</p>
  {/if}
</main>

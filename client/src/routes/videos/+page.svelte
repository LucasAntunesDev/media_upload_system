<script lang="ts">
  import {onMount} from 'svelte'
  import {get} from 'svelte/store'
  import axios from 'axios'
  import 'plyr/dist/plyr.css'

  import api from '../../services/api'
  import {auth} from '../../services/auth'
  import Modal from '../../components/Modal.svelte'

  interface Video {
    id: number
    name: string
    url: string
    thumbnail: string
    tags: string
    isFavourite: boolean
    favorited: boolean
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

  const toggleFavorite = async (video: Video) => {
    try {
      if (video.favorited) {
        await api().delete(`/videos/${video.id}/favorite`)
        video.favorited = false
      } else {
        await api().post(`/videos/${video.id}/favorite`)
        video.favorited = true
      }

      videos = videos.map(vd =>
        vd.id === video.id ? {...vd, favorited: !vd.favorited} : vd,
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
      class="text-4xl flex w-fit font-bold items-center gap-2 my-5 text-primary">
      Vídeos
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
    <p>
      {#if isAuthenticated}
        Esses são os vídeos do sistema. Você pode adicionar mais, salvar seus
        favoritos ou exclui-los.
      {:else}
        Esses são as vídeos disponíveis no sistema. Faça login para adicionar
        mais
      {/if}
    </p>
  </div>

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
</div>

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
      class="grid justify-items-center grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 w-screen px-20">
      {#each videos as video}
        <div class="relative aspect-video">
          <a href={`/videos/show/${video.id}`}>
            <img
              src={`http://localhost:8000/storage/${video.thumbnail}`}
              alt={`Thumbnail de ${video.name}`}
              class="w-full h-auto rounded shadow" />
          </a>
          <h1 class="capitalize font-bold mt-2">{video.name}</h1>
          <div>
            <button
              onclick={() => toggleFavorite(video)}
              class="mt-2"
              title={video.favorited ? 'Desfavoritar' : 'Favoritar'}>
              {#if video.favorited}
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 24 24"
                  fill="currentColor"
                  class="size-6 text-gray-200 hover:text-gray-300 custom-transition">
                  <path
                    d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z" />
                </svg>
              {:else}
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="size-6 text-gray-200 hover:text-gray-300 custom-transition">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                </svg>
              {/if}
            </button>
            <button
              onclick={() => deleteVideo(video.id)}
              class=" mt-2"
              aria-label="Excluir vídeo"
              title="Excluir vídeo">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="size-6 hover:text-red-600 custom-transition">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
              </svg>
            </button>
          </div>
        </div>
      {/each}
    </div>
  {:else}
    <p>Carregando vídeos...</p>
  {/if}
</main>

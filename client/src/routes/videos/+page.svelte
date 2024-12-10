<script lang="ts">
  import {onMount} from 'svelte'
  import api from '../../services/api'
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

  let videos: Video[] = $state([])

  const fetchVideos = async () => {
    try {
      const response = await api().get<Video[]>('/videos/list')
      videos = response.data
      initializePlyr()
    } catch (error) {
      console.error('Erro ao buscar vídeos:', error)
    }
  }

  onMount(() => {
    fetchVideos()
  })

  // Envio do vídeo ao backend
  const handleFormSubmit = async (event: Event) => {
    event.preventDefault()

    const formData = new FormData()
    const target = event.target as HTMLFormElement
    const fileInput = target.querySelector('#url') as HTMLInputElement
    const thumbnailInput = target.querySelector(
      '#thumbnail',
    ) as HTMLInputElement
    const nameInput = target.querySelector('#name') as HTMLInputElement

    if (fileInput && fileInput.files && fileInput.files.length > 0) {
      formData.append('url', fileInput.files[0])
      formData.append('thumbnail', thumbnailInput.files[0])
      formData.append('name', nameInput.value)

      try {
        await axios.post('http://localhost:8000/videos/create', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        })

        openModal = false
        fetchVideos()
      } catch (error) {
        alert('Não foi possível concluir o envio: ' + error)
      }
    } else {
      alert('Por favor, selecione um arquivo para enviar.')
    }
  }

  const deleteVideo = async (videoId: number) => {
    try {
      await api().delete(`/videos/${videoId}`)
      // Atualiza a lista de vídeos após a exclusão
      videos = videos.filter(video => video.id !== videoId)
      initializePlyr()
    } catch (error) {
      console.error('Erro ao excluir vídeo:', error)
    }
  }

  // Define o estado do modal (aberto ou fechado)
  let openModal: boolean = $state(false)

  // Inicializa o Plyr para todos os vídeos
  const initializePlyr = async () => {
    if (typeof window !== 'undefined') {
      const {default: Plyr} = await import('plyr')
      const players = Array.from(document.querySelectorAll('.player')).map(
        p => new Plyr(p as HTMLElement),
      )
    }
  }

  onMount(() => {
    initializePlyr()
  })
</script>

<h1 class="text-4xl inline-flex w-fit font-bold items-center gap-1.5">
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
    class="lucide lucide-film size-8"
    ><rect width="18" height="18" x="3" y="3" rx="2" /><path d="M7 3v18" /><path
      d="M3 7.5h4" /><path d="M3 12h18" /><path d="M3 16.5h4" /><path
      d="M17 3v18" /><path d="M17 7.5h4" /><path d="M17 16.5h4" /></svg>
</h1>

<button
  onclick={() => (openModal = !openModal)}
  class="bg-emerald-600 px-2 py-1.5 rounded-lg mx-auto w-fit flex text-white"
  >Adicionar novo vídeo</button>

{#if openModal}
  <div
    class="w-[50vw] h-fit py-12 px-10 border-gray-200 border-2 rounded-md z-10 mx-auto absolute inset-0">
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

    <h1 class="text-xl">Adicionar vídeo</h1>

    <hr class="py-4" />

    <form onsubmit={handleFormSubmit} class="flex flex-col gap-2">
      <label class="flex flex-col"
        >Vídeo<input type="file" name="url" id="url" accept="video/*" /></label>
      <label class="flex flex-col"
        >Thumbnail<input
          type="file"
          name="thumbnail"
          id="thumbnail"
          accept="image/*" /></label>
      <input type="text" name="name" id="name" placeholder="Nome do vídeo" />
      <button
        type="submit"
        class="bg-emerald-600 px-2 py-1.5 rounded-lg text-white w-fit">
        Manda bala
      </button>
    </form>
  </div>
{/if}

<main>
  {#if videos.length > 0}
    <div
      class="grid justify-items-center grid-cols-1 md:grid-cols-2 lg:md:grid-cols-3px-10 w-screen gap-2">
      {#each videos as video}
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
          <video
            class="player"
            playsinline
            controls
            data-poster={`http://localhost:8000/storage/${video.thumbnail}`}>
            <source
              src={`http://localhost:8000/storage/${video.url}`}
              type="video/mp4" />
          </video>
          <h1 class="capitalize font-bold">{video.name}</h1>
          <span class="font-sm text-gray-600">{video.tags}</span>
          <button
            onclick={() => deleteVideo(video.id)}
            class=" text-red-600"
            aria-label="Excluir vídeo"
            title="Excluir vídeo">
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
            {#if !video.isFavourite}
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
                  d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 0 1-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 9.953 4.167 9.5 5 9.5h1.053c.472 0 .745.556.5.96a8.958 8.958 0 0 0-1.302 4.665c0 1.194.232 2.333.654 3.375Z" />
              </svg>
            {:else}
              <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="currentColor"
                class="size-6">
                <path
                  d="M7.493 18.5c-.425 0-.82-.236-.975-.632A7.48 7.48 0 0 1 6 15.125c0-1.75.599-3.358 1.602-4.634.151-.192.373-.309.6-.397.473-.183.89-.514 1.212-.924a9.042 9.042 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75A.75.75 0 0 1 15 2a2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H14.23c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23h-.777ZM2.331 10.727a11.969 11.969 0 0 0-.831 4.398 12 12 0 0 0 .52 3.507C2.28 19.482 3.105 20 3.994 20H4.9c.445 0 .72-.498.523-.898a8.963 8.963 0 0 1-.924-3.977c0-1.708.476-3.305 1.302-4.666.245-.403-.028-.959-.5-.959H4.25c-.832 0-1.612.453-1.918 1.227Z" />
              </svg>
            {/if}
          </button>
        </div>
      {/each}
    </div>
  {:else}
    <p>Carregando vídeos...</p>
  {/if}
</main>

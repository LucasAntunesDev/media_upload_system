<script lang="ts">
  import {onMount} from 'svelte'
  import apiService from '../services/api'
  import axios from 'axios'
  import 'plyr/dist/plyr.css'

  const api = apiService
  // Interfaces para Imagens e Vídeos
  interface Image {
    id: number
    url: string
    name: string
  }

  interface Video {
    id: number
    name: string
    url: string
    thumbnail: string
    tags: string
    isFavourite: boolean
  }

  // Estados para armazenar imagens e vídeos
  let images: Image[] = $state([])
  let videos: Video[] = $state([])

  // Buscar as últimas 3 imagens
  const fetchImages = async () => {
    try {
      const response = await api().get<Image[]>('/images/list')
      images = response.data.slice(-3) // Pega as últimas 3 imagens
    } catch (error) {
      console.error('Erro ao buscar imagens:', error)
    }
  }

  // Buscar os vídeos
  const fetchVideos = async () => {
    try {
      const response = await api().get<Video[]>('/videos/list')
      videos = response.data
      initializePlyr()
    } catch (error) {
      console.error('Erro ao buscar vídeos:', error)
    }
  }

  // Inicializa o Plyr para os vídeos
  const initializePlyr = async () => {
    if (typeof window !== 'undefined') {
      const {default: Plyr} = await import('plyr')
      Array.from(document.querySelectorAll('.player')).map(
        p => new Plyr(p as HTMLElement),
      )
    }
  }

  onMount(() => {
    fetchImages()
    fetchVideos()
  })
</script>

<h1
  class="text-4xl flex w-fit font-bold items-center gap-1.5 text-primary mx-auto">
  Página inicial
</h1>

<div class="flex flex-col w-screen px-20 gap-8 flex-wrap">
  <div>
    <h2 class="text-2xl font-bold mt-6">Imagens mais recentes</h2>
    <a href="/images" class="text-sm text-gray-200 hover:underline">Ver mais</a>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
    {#if images.length > 0}
      {#each images as image}
        <div class="p-2 rounded-lg">
          <img
            src={`http://localhost:8000/storage/${image.url}`}
            alt={image.name}
            class="w-56 rounded" />
          <h3 class="text-lg font-semibold mt-2">{image.name}</h3>
        </div>
      {/each}
    {:else}
      <p>Carregando imagens...</p>
    {/if}
  </div>

  <div>
    <h2 class="text-2xl font-bold mt-6">Vídeos mais recentes</h2>
    <a href="/videos" class="text-sm text-gray-200 hover:underline">Ver mais</a>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
    {#if videos.length > 0}
      {#each videos as video}
        <div class="p-2 rounded-lg">
          <video
            class="player w-full w-56"
            playsinline
            controls
            data-poster={`http://localhost:8000/storage/${video.thumbnail}`}>
            <source
              src={`http://localhost:8000/storage/${video.url}`}
              type="video/mp4" />
          </video>
          <h3 class="text-lg font-semibold mt-2">{video.name}</h3>
        </div>
      {/each}
    {:else}
      <p>Carregando vídeos...</p>
    {/if}
  </div>
</div>

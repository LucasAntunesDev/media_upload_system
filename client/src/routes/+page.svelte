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
  class="text-4xl inline-flex w-fit font-bold items-center gap-1.5 text-primary">
  Página de início
</h1>

<h2 class="text-2xl font-bold mt-6">Últimas Imagens</h2>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
  {#if images.length > 0}
    {#each images as image}
      <div class="border p-2 rounded-lg">
        <img
          src={`http://localhost:8000/storage/${image.url}`}
          alt={image.name}
          class="w-full h-48 object-cover rounded" />
        <h3 class="text-lg font-semibold mt-2">{image.name}</h3>
      </div>
    {/each}
  {:else}
    <p>Carregando imagens...</p>
  {/if}
</div>

<h2 class="text-2xl font-bold mt-6">Vídeos</h2>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
  {#if videos.length > 0}
    {#each videos as video}
      <div class="border p-2 rounded-lg">
        <video
          class="player w-full h-48"
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

<script lang="ts">
  import {onMount} from 'svelte'
  import {page} from '$app/stores'
  import api from '../../../services/api'
  import 'plyr/dist/plyr.css'

  let video = null
  let videoId: string

  // Obter o ID do vídeo da URL
  $: videoId = $page.params.id

  const fetchVideo = async () => {
    try {
      const response = await api().get(`/videos/${videoId}`)
      video = response.data
      initializePlyr()
    } catch (error) {
      console.error('Erro ao buscar o vídeo:', error)
    }
  }

  const initializePlyr = async () => {
    if (typeof window !== 'undefined') {
      const {default: Plyr} = await import('plyr')
      new Plyr('#player')
    }
  }

  onMount(() => {
    fetchVideo()
  })
</script>

{#if video}
  <div class="max-w-4xl mx-auto p-4">
    <h1 class="text-4xl font-bold mb-4">{video.name}</h1>
    <div class="relative">
      <video
        id="player"
        playsinline
        controls
        data-poster={`http://localhost:8000/storage/${video.thumbnail}`}>
        <source
          src={`http://localhost:8000/storage/${video.url}`}
          type="video/mp4" />
      </video>
    </div>
    <p class="mt-4 text-gray-600">{video.tags}</p>
  </div>
{:else}
  <p class="text-center mt-10">Carregando vídeo...</p>
{/if}

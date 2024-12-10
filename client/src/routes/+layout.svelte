<script lang="ts">
  import {onMount} from 'svelte'
  import {user} from '../stores/user'
  import {auth, logout, getProfile} from '../services/auth'
  import {get} from 'svelte/store'

  import '../css/app.css'

  let {children} = $props()

  onMount(async () => {
    if (get(auth).token) {
      await getProfile()
    }
  })

  const loadUser = async () => {
    const profile = await getProfile()
    if (profile) {
      user.set({...get(user), ...profile})
    }
  }

  onMount(loadUser)

  auth.subscribe(state => {
    user.set({...get(user), ...state})
  })

  const handleLogout = async () => {
    await logout()
    user.set({
      id: null,
      name: null,
      email: null,
      completeUser: null,
      token: null,
    })
  }
</script>

<header
  class="flex items-center justify-between px-10 w-screen border-b-gray-100 border-b-[2px] h-16">
  <nav class="inline-flex gap-3">
    <a href="/">Início</a>
    <a href="/images">Imagens</a>
    <a href="/videos">Vídeos</a>
  </nav>

  <div>
    {#if $user.token}
      {console.log($user)}
      <span>Olá, {$user.name}!</span>
      <button class="ml-4 text-red-500" onclick={handleLogout}>Logout</button>
    {:else}
      <a href="/login" class="text-blue-500">Login</a>
    {/if}
  </div>
</header>

{@render children()}

<footer
  class="flex items-center justify-between px-10 w-screen border-t-gray-100 border-t-[2px] h-16 mt-8">
  <h6>Lucas Antunes, 2024</h6>
</footer>

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

<div class="min-h-screen bg-neutral-900 text-zinc-50">
  <header
    class="screen h-16 px-10 md:px-20 py-2 justify-between items-center flex text-zinc-100 text-sm">
    <div class="flex w-fit gap-x-10 items-center justify-between h-fit">
      <a href="/" class="w-fit">
        <!-- <span>Logo</span> -->
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
            d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h1.5C5.496 19.5 6 18.996 6 18.375m-3.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-1.5A1.125 1.125 0 0 1 18 18.375M20.625 4.5H3.375m17.25 0c.621 0 1.125.504 1.125 1.125M20.625 4.5h-1.5C18.504 4.5 18 5.004 18 5.625m3.75 0v1.5c0 .621-.504 1.125-1.125 1.125M3.375 4.5c-.621 0-1.125.504-1.125 1.125M3.375 4.5h1.5C5.496 4.5 6 5.004 6 5.625m-3.75 0v1.5c0 .621.504 1.125 1.125 1.125m0 0h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m1.5-3.75C5.496 8.25 6 7.746 6 7.125v-1.5M4.875 8.25C5.496 8.25 6 8.754 6 9.375v1.5m0-5.25v5.25m0-5.25C6 5.004 6.504 4.5 7.125 4.5h9.75c.621 0 1.125.504 1.125 1.125m1.125 2.625h1.5m-1.5 0A1.125 1.125 0 0 1 18 7.125v-1.5m1.125 2.625c-.621 0-1.125.504-1.125 1.125v1.5m2.625-2.625c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125M18 5.625v5.25M7.125 12h9.75m-9.75 0A1.125 1.125 0 0 1 6 10.875M7.125 12C6.504 12 6 12.504 6 13.125m0-2.25C6 11.496 5.496 12 4.875 12M18 10.875c0 .621-.504 1.125-1.125 1.125M18 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m-12 5.25v-5.25m0 5.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125m-12 0v-1.5c0-.621-.504-1.125-1.125-1.125M18 18.375v-5.25m0 5.25v-1.5c0-.621.504-1.125 1.125-1.125M18 13.125v1.5c0 .621.504 1.125 1.125 1.125M18 13.125c0-.621.504-1.125 1.125-1.125M6 13.125v1.5c0 .621-.504 1.125-1.125 1.125M6 13.125C6 12.504 5.496 12 4.875 12m-1.5 0h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M19.125 12h1.5m0 0c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h1.5m14.25 0h1.5" />
        </svg>
      </a>

      <ul class="justify-center items-center gap-x-4 flex">
        <li class="hover:text-zinc-300 transicao">
          <a href="/">Início</a>
        </li>
        <li class="hover:text-zinc-300 transicao">
          <a href="/images">Imagens</a>
        </li>
        <li class="hover:text-zinc-300 transicao">
          <a href="/videos">Vídeos</a>
        </li>
      </ul>
    </div>

    <div>
      {#if $user.token}
        <span>Olá, {$user.name}!</span>
        <button
          class="items-center gap-2 rounded-lg py-1.5 px-3 hover:text-rose-600"
          onclick={handleLogout}>Logout</button>
      {:else}
        <div class="justify-start items-center gap-2.5 hidden md:flex">
          <a class="btn-outline" href="/register"> Criar Conta </a>

          <a
            class="h-fit px-6 py-3 bg-teal-600 hover:bg-teal-700 rounded-lg justify-center items-center gap-2 inline-flex text-white disabled:bg-primary/50 leading-none text-sm capitalize transition ease-in-out duration-300"
            href="/login">
            Entrar
          </a>
        </div>
      {/if}
    </div>
  </header>

  {@render children()}

  <!-- <footer
    class="screen flex-wrap h-fit px-10 py-4 md:px-20  border-b-2 justify-between items-center flex flex-col text-slate-600 text-sm">
    <h6>Lucas Antunes, 2024</h6>
  </footer> -->
</div>

<script lang="ts">
  import axios from 'axios'
  import {auth, login} from '../../services/auth'

  let email = ''
  let password = ''

  const handleLogin = async (event: Event) => {
    event.preventDefault()

    await login({email: email, password: password})
  }
</script>

<main
  class="w-screen h-fit min-h-screen flex items-center justify-center gap-4 flex-col flex-wrap md:bg-gray-50">
  <fieldset
    class=" hidden md:flex justify-center items-center gap-y-2 gap-x-24 p-10 rounded-lg w-fit h-fit py-10 shadow-xl mx-auto">
    <div class="w-fit">
      <div>
        <p class="text-red-600 text-4xl font-bold">Entrar</p>
        <p class="inline-flex items-center text-sm gap-x-1">
          Ainda não tem uma conta?
          <a
            href="/register"
            class="text-red-600 hover:text-pink-700 custom-transition">
            Cadastre-se
          </a>
        </p>
      </div>
      <form on:submit|preventDefault={handleLogin}>
        <label class="block text-sm font-medium leading-6 text-gray-900">
          Email
          <input
            type="email"
            bind:value={email}
            class="block w-full rounded-md border-0 py-1.5 pl-7 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-secondary sm:text-sm sm:leading-6 outline-none" />
        </label>
        <label class="block text-sm font-medium leading-6 text-gray-900">
          Senha
          <input
            type="password"
            bind:value={password}
            class="block w-full rounded-md border-0 py-1.5 pl-7 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-secondary sm:text-sm sm:leading-6 outline-none" />
        </label>
        <button
          type="submit"
          class="h-fit px-6 py-3 bg-teal-600 hover:bg-teal-700 rounded-lg justify-center items-center gap-2 inline-flex text-white disabled:bg-primary/50 leading-none text-sm capitalize transition ease-in-out duration-300 mx-auto my-4 w-fit"
          >Entrar</button>
      </form>
    </div>
  </fieldset>
</main>

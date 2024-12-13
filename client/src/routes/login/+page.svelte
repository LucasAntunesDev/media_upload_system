<script lang="ts">
  import {login} from '../../services/auth'

  let email = ''
  let password = ''

  const handleLogin = async (event: Event) => {
    event.preventDefault()

    await login({email: email, password: password})
  }
</script>

<main
  class="w-screen h-fit min-h-screen flex items-center justify-center gap-4 flex-col flex-wrap bg-neutral-800">
  <fieldset
    class=" hidden md:flex justify-center items-center gap-y-2 gap-x-24 p-10 rounded-lg w-fit h-fit py-10 shadow-xl mx-auto bg-neutral-900/60">
    <div class="w-fit">
      <div>
        <p class="text-red-600 text-4xl font-bold">Entrar</p>
        <p class="inline-flex items-center text-sm gap-x-1">
          Ainda não tem uma conta?
          <a
            href="/register"
            class="text-red-600 hover:underline custom-transition">
            Cadastre-se
          </a>
        </p>
      </div>
      <form on:submit|preventDefault={handleLogin}>
        <label class="block text-sm font-medium leading-6 text-gray-50">
          Email
          <input type="email" bind:value={email} class="text-input" />
        </label>
        <label class="block text-sm font-medium leading-6 text-gray-50">
          Senha
          <input type="password" bind:value={password} class="text-input" />
        </label>
        <button
          type="submit"
          class="h-fit px-6 py-3 bg-teal-600 hover:bg-teal-700 rounded-lg justify-center items-center gap-2 inline-flex text-white disabled:bg-primary/50 leading-none text-sm capitalize transition ease-in-out duration-300 mx-auto my-4 w-fit"
          >Entrar</button>
      </form>
    </div>
  </fieldset>
</main>

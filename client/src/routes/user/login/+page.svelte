<script lang="ts">
  // import api from '../../services/api'
  import axios from 'axios'
  import {auth, login} from '../../../services/auth'

  let email = ''
  let password = ''

  const handleLogin = async (event: Event) => {
    event.preventDefault()

    await login({email: email, password: password})
    // try {
    //   const response = await axios.post('https://localhost:8000/user/login', {
    //     name,
    //     password,
    //   })

    //   console.log('Login bem-sucedido:', response.data)
    // } catch (error) {
    //   console.error('Erro no login:', error)
    // }

    // console.log('Usuário:', name, 'Senha:', password)
  }
</script>

<main>
  <h1 class="text-red-600 text-4xl font-bold">Login</h1>
  <form on:submit|preventDefault={handleLogin}>
    <label class="block text-sm font-medium leading-6 text-gray-900">
      Email:
      <input
        type="email"
        bind:value={email}
        class="block w-full rounded-md border-0 py-1.5 pl-7 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-secondary sm:text-sm sm:leading-6 outline-none" />
    </label>
    <label class="block text-sm font-medium leading-6 text-gray-900">
      Senha:
      <input
        type="password"
        bind:value={password}
        class="block w-full rounded-md border-0 py-1.5 pl-7 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-secondary sm:text-sm sm:leading-6 outline-none" />
    </label>
    <button
      type="submit"
      class="h-fit px-6 py-3 bg-teal-600 hover:bg-teal-700 rounded-lg justify-center items-center gap-2 inline-flex text-white disabled:bg-primary/50 leading-none text-sm capitalize transition ease-in-out duration-300"
      >Entrar</button>
  </form>
</main>

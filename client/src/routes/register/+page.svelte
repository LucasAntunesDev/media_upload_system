<script lang="ts">
  import axios from 'axios'

  let name = ''
  let email = ''
  let password: string = ''
  let confirmPassword: string = ''

  const handleRegister = async (event: Event) => {
    event.preventDefault()

    if (password !== confirmPassword) {
      console.error('Erro no cadastro: As senhas não coincidem.')
      return
    }

    try {
      const response = await axios.post('http://localhost:8000/user/register', {
        name,
        email,
        password,
        password_confirmation: confirmPassword,
      })

      alert('Cadastro bem-sucedido!')
      window.location.replace('/login')
    } catch (error) {
      console.error('Erro no cadastro:', error)
    }
  }
</script>

<main
  class="w-screen h-fit min-h-screen flex items-center justify-center gap-4 flex-col flex-wrap md:bg-gray-50">
  <fieldset
    class=" hidden md:flex justify-center items-center gap-y-2 gap-x-24 p-10 rounded-lg w-fit h-fit py-10 shadow-xl mx-auto">
    <div class="w-fit">
      <div>
        <p class="text-red-600 text-4xl font-bold">Cadastrar-se</p>
        <p class="inline-flex items-center text-sm gap-x-1">
          Já tem uma conta?
          <a
            href="./login"
            class="text-red-600 hover:text-pink-700 custom-transition">
            Entrar
          </a>
        </p>
      </div>
      <form onsubmit={handleRegister} class="flex flex-col justify-center">
        <label class="block text-sm font-medium leading-6 text-gray-900">
          Nome
          <input
            type="text"
            bind:value={name}
            class="block w-full rounded-md border-0 py-1.5 pl-7 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6 outline-none" />
        </label>
        <label class="block text-sm font-medium leading-6 text-gray-900">
          E-mail
          <input
            type="email"
            bind:value={email}
            class="block w-full rounded-md border-0 py-1.5 pl-7 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6 outline-none" />
        </label>
        <label class="block text-sm font-medium leading-6 text-gray-900">
          Senha
          <input
            type="password"
            bind:value={password}
            class="block w-full rounded-md border-0 py-1.5 pl-7 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6 outline-none" />
        </label>
        <label class="block text-sm font-medium leading-6 text-gray-900">
          Confirme a Senha
          <input
            type="password"
            bind:value={confirmPassword}
            name="password_confirmation"
            class="block w-full rounded-md border-0 py-1.5 pl-7 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6 outline-none" />
        </label>
        <button
          type="submit"
          class="h-fit px-6 py-3 bg-teal-600 hover:bg-teal-700 rounded-lg justify-center items-center gap-2 inline-flex text-white disabled:bg-primary/50 leading-none text-sm capitalize transition ease-in-out duration-300 mx-auto my-4 w-fit"
          >Cadastrar</button>
      </form>
    </div>
  </fieldset>
</main>

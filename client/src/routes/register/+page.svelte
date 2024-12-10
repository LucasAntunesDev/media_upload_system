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

      console.log('Cadastro bem-sucedido:', response.data)
      // Lógica adicional após o cadastro bem-sucedido, como redirecionar o usuário
    } catch (error) {
      console.error('Erro no cadastro:', error)
      // Mostrar mensagem de erro ao usuário
    }
  }
</script>

<main>
  <h1 class="text-red-600 text-4xl font-bold">Cadastro</h1>
  <form onsubmit={handleRegister}>
    <label class="block text-sm font-medium leading-6 text-gray-900">
      Nome:
      <input
        type="text"
        bind:value={name}
        class="block w-full rounded-md border-0 py-1.5 pl-7 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-secondary sm:text-sm sm:leading-6 outline-none" />
    </label>
    <label class="block text-sm font-medium leading-6 text-gray-900">
      E-mail:
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
    <label class="block text-sm font-medium leading-6 text-gray-900">
      Confirme a Senha:
      <input
        type="password"
        bind:value={confirmPassword}
        name="password_confirmation"
        class="block w-full rounded-md border-0 py-1.5 pl-7 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-secondary sm:text-sm sm:leading-6 outline-none" />
    </label>
    <button
      type="submit"
      class="h-fit px-6 py-3 bg-teal-600 hover:bg-teal-700 rounded-lg justify-center items-center gap-2 inline-flex text-white disabled:bg-primary/50 leading-none text-sm capitalize transition ease-in-out duration-300"
      >Cadastrar</button>
  </form>
</main>

<style>
  main {
    max-width: 400px;
    margin: auto;
    padding: 1rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }
  label {
    display: flex;
    flex-direction: column;
    margin-bottom: 1rem;
  }
  button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 0.5rem;
    border-radius: 4px;
    cursor: pointer;
  }
  button:hover {
    background-color: #0056b3;
  }
</style>

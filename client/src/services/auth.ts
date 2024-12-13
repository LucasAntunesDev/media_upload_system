import {writable, get} from 'svelte/store'
import {redirect} from '@sveltejs/kit'
import api from './api'

interface AuthState {
  token: string
  id: number | null
  name: string | null
  email: string | null
  completeUser: any | null
}

const initialAuthState: AuthState = {
  token: '',
  id: null,
  name: null,
  email: null,
  completeUser: null,
}

const auth = writable<AuthState>(initialAuthState)

if (typeof window !== 'undefined') {
  let parsedData
  try {
    parsedData = JSON.parse(localStorage.getItem('trabalho_final_prog3'))
  } catch (e) {
    parsedData = {}
  }

  if (parsedData?.token) {
    auth.set({
      ...initialAuthState,
      token: parsedData.token,
      id: parsedData.id || null,
      name: parsedData.name || null,
      email: parsedData.email || null,
    })
  }
}

const login = async (data: {
  email: string
  password: string
}): Promise<void> => {
  try {
    const response = await api().post('user/login', {
      email: data.email,
      password: data.password,
    })

    if (response.status === 200) {
      const res = response.data

      if (res.token) {
        auth.update(state => {
          const newState = {...state, token: res.token}
          localStorage.setItem(
            'trabalho_final_prog3',
            JSON.stringify({token: res.token})
          )
          return newState
        })

        setTimeout(async () => {
          await getProfile()
        }, 0)

        window.location.replace('/')
      } else {
        throw new Error('Token não encontrado na resposta')
      }
    } else {
      console.error('Ocorreu algum erro no login. Tente novamente.')
    }
  } catch (err) {
    console.error('Erro na requisição:', err)
  }
}

const logout = async (): Promise<void> => {
  try {
    await api().get('user/logout')
    auth.update(state => {
      localStorage.removeItem('trabalho_final_prog3')
      return {...initialAuthState}
    })

    window.location.replace('/')
  } catch (error) {
    console.error('Erro ao fazer logout:', error)
  }
}

const getProfile = async (): Promise<{
  id: number
  name: string
  email: string
} | null> => {
  try {
    const response = await api().get('user/tokenProfile')
    if (response.status === 200) {
      const {id, name, email} = response.data
      auth.update(state => ({
        ...state,
        id,
        name,
        email,
      }))
      return {id, name, email}
    }
  } catch (error) {
    console.error('Erro ao buscar perfil:', error)
    return null
  }
}

const getCompleteUser = async (): Promise<any | null> => {
  const currentId = get(auth).id
  if (!currentId) return null

  try {
    const response = await api().get(`/user/showUser/${currentId}`)
    if (response.status === 200) {
      auth.update(state => ({
        ...state,
        completeUser: response.data,
      }))
      return response.data
    }
  } catch (error) {
    console.error('Erro ao buscar os dados completos do usuário:', error)
    return null
  }
}

export {auth, login, logout, getProfile, getCompleteUser}

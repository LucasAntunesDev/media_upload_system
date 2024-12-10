import axios, {type AxiosInstance, type InternalAxiosRequestConfig} from 'axios'

interface TokenData {
  token: string
}

const apiService = (): AxiosInstance => {
  const api: AxiosInstance = axios.create({
    baseURL: 'http://localhost:8000/',
  })

  const token: string | null = localStorage.getItem('trabalho_final_prog3')
  let parsedToken: TokenData | null = null

  if (token) {
    try {
      parsedToken = JSON.parse(token) as TokenData
      if (parsedToken?.token) {
        api.defaults.headers['Authorization'] = `Bearer ${parsedToken.token}`
      } else {
        console.error('Token inválido ou não encontrado.')
      }
    } catch (error) {
      console.error('Erro ao parsear o token:', error)
    }
  }

  api.interceptors.request.use(
    (config: InternalAxiosRequestConfig) => {
      const storedToken = localStorage.getItem('trabalho_final_prog3')
      if (storedToken) {
        try {
          const parsedStoredToken = JSON.parse(storedToken) as TokenData
          if (parsedStoredToken?.token) {
            config.headers[
              'Authorization'
            ] = `Bearer ${parsedStoredToken.token}`
          }
        } catch (error) {
          console.error('Erro ao parsear o token armazenado:', error)
        }
      }
      return config
    },
    error => {
      console.error('Erro no interceptor:', error)
      return Promise.reject(error)
    }
  )

  return api
}
export default apiService

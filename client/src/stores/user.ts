import {writable} from 'svelte/store'

interface User {
  id: number | null
  name: string | null
  email: string | null
  completeUser: any | null
  token?: string | null
}

export const user = writable<User>({
  id: null,
  name: null,
  email: null,
  completeUser: null,
  token: null,
})

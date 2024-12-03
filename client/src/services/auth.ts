import { writable } from 'svelte/store'
import api from './api';
import { onMount } from 'svelte';
// import navigate

interface AuthState {
  token: string;
  id: number | null;
  name: string | null;
  type: string | null;
  completeUser: any | null;
}

const initialAuthState: AuthState = {
  token: '',
  id: null,
  name: null,
  type: null,
  completeUser: null,
};

const auth = writable<AuthState>(initialAuthState);

onMount(() => { 
  const storedToken = JSON.parse(localStorage.getItem('trabalho_final_prog3') as string)?.token || ''; auth.update(state => ({ ...state, token: storedToken })); 
}); 

const login = async (data: { email: string, password: string }): Promise<void> => {
  try {
    const response = await api().post('/login', {
      email: data.email,
      password: data.password,
    });

    if (response.status === 200) {
      const res = response.data;
      if (res.token) {
        auth.update(state => {
          state.token = res.token;
          localStorage.setItem('trabalho_final_prog3', JSON.stringify({ token: res.token }));
          return state;
        });
        // navigate('/');
        location.replace('/')
      } else {
        throw new Error('Token não encontrado na resposta');
      }
    } else {
      console.error('Ocorreu algum erro no login. Tente novamente.');
    }
  } catch (err) {
    console.error('Erro na requisição:', err);
  }
};

const logout = async (): Promise<void> => {
  try {
    await api().post('/logout');
    auth.update(state => {
      state.token = '';
      state.id = null;
      state.name = null;
      state.type = null;
      state.completeUser = null;
      localStorage.removeItem('trabalho_final_prog3');
      return state;
    });
    // navigate('/');
    location.replace('/')
  } catch (error) {
    console.error('Erro ao fazer logout:', error);
  }
};

const getProfile = async (): Promise<{ id: number, name: string, type: string } | null> => {
  try {
    const response = await api().get('/tokenProfile');
    if (response.status === 200) {
      const { id, name, type } = response.data;
      auth.update(state => {
        state.id = id;
        state.name = name;
        state.type = type;
        return state;
      });
      return { id, name, type };
    }
  } catch (error) {
    console.error('Erro ao buscar perfil:', error);
    return null;
  }
};

const getCompleteUser = async (): Promise<any | null> => {
  let currentId: number | null;
  auth.subscribe(state => currentId = state.id);
  if (!currentId) return null;

  try {
    const response = await api().get(`/usuarios/${currentId}`);
    if (response.status === 200) {
      auth.update(state => {
        state.completeUser = response.data;
        return state;
      });
      return response.data;
    }
  } catch (error) {
    console.error('Erro ao buscar os data do usuário:', error);
    return null;
  }
};

export { auth, login, logout, getProfile, getCompleteUser };

import axios, { type AxiosInstance } from "axios";

const api = (): AxiosInstance => {
  const API: AxiosInstance = axios.create({
    baseURL: "http://localhost:8000/",
    headers: {
      'Content-Type': 'application/json',
    },
  });

  return API;
};

export default api;

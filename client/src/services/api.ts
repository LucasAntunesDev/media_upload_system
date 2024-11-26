import axios, { type AxiosInstance } from "axios";

const api = () : AxiosInstance => {
  const API = axios.create({
    baseURL: "http://localhost:8000/",
  });

  return API;
};
export default api;

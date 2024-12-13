# Sistema de upload de mídias
Este é o último trabalho da disciplina de **Programação Web III**, do curso técnico em **Informática para Internet** do IFRS - _Campus_ Bento Gonçalves. 
<br>Ele consiste em um sistema de envio de imagens e vídeos.

## Funcionalidades
- Envio e exclusão de imagens e vídeos
- Autenticação do usuário
- Adicionar mídias como favoritas

## Tecnologias
<div>
<img src='https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white'/>

<img src='https://img.shields.io/badge/TypeScript-007ACC?style=for-the-badge&logo=typescript&logoColor=white'/>

<img src='https://img.shields.io/badge/Svelte-4A4A55?style=for-the-badge&logo=svelte&logoColor=FF3E00'/>

<img src='https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white'/>
</div>

## Instalação e uso📥

_Aqui está uma explicação passo a passo de como instalar e fazer funcionar a aplicação._

1. Clone o repositório
    ```sh
    git clone https://github.com/LucasAntunesDev/media_upload_system.git
    ```
2. Instale as dependências

    #### Em './client':
    ```sh
    npm install
    ```
    
    #### Em './service':
    ```sh
    composer install
    ```
3. Configure o backend
    
    #### Gerar .env:
    ```sh
    cp .env.example .env
    ```
    
    #### Gerar chave da aplicação:
    ```sh
    php artisan key:generate
    ```
    
    #### Rodar migrations:
    ```sh
    php artisan migrate --seed
    ```
    
    #### Gerar segredo do JWT:
    ```sh
    php artisan jwt:secret
    ```
    
    #### Gerar link simbólico:
    ```sh
    php artisan storage:link
    ```
4. Rode os servidores do frontend e backend
    
    #### Em './client':
    ```sh
    npm run dev
    ```

    #### Em './service':
    ```sh
    php artisan serve
    ```
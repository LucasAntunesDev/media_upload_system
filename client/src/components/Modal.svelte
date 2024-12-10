<script lang="ts">
  import {createEventDispatcher} from 'svelte'

  export let title: string
  export let openModal = false
  export let onSubmit: (event: Event) => void

  const dispatch = createEventDispatcher()

  const closeModal = () => {
    openModal = false
    dispatch('closeModal')
  }
</script>

<div
  class={openModal
    ? 'fixed inset-0 z-50 flex items-center justify-center bg-neutral-950 bg-opacity-50 backdrop-blur-sm'
    : 'hidden'}
  on:click={closeModal}>
  <div
    class="relative w-1/2 p-4 bg-neutral-800 rounded-md"
    on:click|stopPropagation={e => e.stopPropagation()}>
    <div class="flex items-center justify-between">
      <h2 class="font-bold text-primary text-2xl">{title}</h2>
      <button on:click={closeModal} aria-label="close modal">&times;</button>
    </div>
    <div class="mt-4">
      <slot name="body"></slot>
    </div>
    <div class="flex justify-end mt-4">
      <slot name="footer"></slot>
    </div>
  </div>
</div>

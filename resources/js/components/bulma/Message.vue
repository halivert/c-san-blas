<template>
  <article class="message">
    <div class="message-header">
      <p>{{ title }}</p>
      <button
        v-if="hasCloseButton"
        class="delete"
        aria-label="delete">
      </button>
    </div>
    <div class="message-body">
      <slot></slot>
    </div>
  </article>
</template>

<script>
export default {
  props: [
    "title", "hasCloseButton"
  ]
};
</script>

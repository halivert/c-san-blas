<template>
  <message
    :title="title ? title : 'Alert'"
    :has-close-button="hasCloseButton"
  >
    <p style="margin-bottom: 1rem;">
      <slot>{{ message }}</slot>
    </p>
    <div class="buttons is-right">
      <button @click="$emit('confirm')" class="button is-success">
        {{ confirmLabel }}
      </button>
      <button @click="$emit('cancel')" class="button is-danger">
        {{ cancelLabel }}
      </button>
    </div>
  </message>
</template>

<script>
import Message from "./Message.vue";

export default {
  props: [
    "title",
    "hasCloseButton",
    "confirmLabel",
    "cancelLabel",
    "message"
  ],
  components: {
    Message
  }
};
</script>

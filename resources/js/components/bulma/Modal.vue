<template>
  <div :class="[ 'modal', { 'is-active': isActive } ]">
    <div
      @click="backgroundClick"
      class="modal-background">
    </div>
    <div class="modal-content">
      <slot></slot>
    </div>
    <button
      v-if="hasCloseButton"
      @click="hideModal"
      class="modal-close is-large"
      aria-label="close">
    </button>
  </div>
</template>

<script>
import { addClass, removeClass } from "../../helpers.js";

export default {
  props: [
    "hasCloseButton",
    "closeOnBackgroundClick"
  ],
  data() {
    return {
      isActive: false
    };
  },
  methods: {
    showModal() {
      addClass(document.documentElement, 'is-clipped');
      return this.isActive = true;
    },
    backgroundClick() {
      if (this.closeOnBackgroundClick) {
        this.hideModal();
      }
    },
    hideModal() {
      this.$emit('close');
      removeClass(document.documentElement, 'is-clipped');
      return this.isActive = false;
    },
  },
};
</script>

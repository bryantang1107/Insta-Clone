<template>
  <div class="modal-content">
    <div class="modal-header">
      <h1 class="modal-title fs-5" :id="`${label}Label`" v-html="header"></h1>
      <button
        type="button"
        class="btn-close"
        data-bs-dismiss="modal"
        aria-label="Close"
      ></button>
    </div>
    <div class="modal-body">
      <p class="m-0 text-muted" v-html="message"></p>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        Close
      </button>
      <button
        type="button"
        class="btn btn-primary"
        @click="$emit(label)"
        data-bs-dismiss="modal"
      >
        Confirm
      </button>
    </div>
  </div>
</template>

<script>
export default {
  props: ["message", "header", "label"],
};
</script>

<style scoped>
</style>
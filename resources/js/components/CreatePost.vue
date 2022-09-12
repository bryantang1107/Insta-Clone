<template>
  <div class="container">
    <div class="row">
      <div class="col-8 offset-2">
        <div class="row mb-3">
          <label for="caption" class="col-md-4 col-form-label">
            Image Caption
          </label>

          <input
            id="caption"
            type="text"
            class="form-control"
            name="caption"
            value=""
            required
            autocomplete="caption"
            autofocus
          />

          <!-- <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>

              @error('caption') is-invalid @enderror (input)
            </span> -->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {};
</script>

<style scoped>
</style>
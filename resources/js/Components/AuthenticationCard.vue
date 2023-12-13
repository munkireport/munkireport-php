<template>
  <div class="container-sm d-flex justify-content-center" style="height: 100vh">
    <div class="d-flex flex-column justify-content-center">
        <div class="card">
          <div class="card-header">
            <slot name="header" />
          </div>
          <div class="card-body">
            <slot />
          </div>
        </div>
    </div>
  </div>
</template>

<template>
    <div>
      <h2>
        <slot name="title" />
      </h2>
      <p class="text-muted">
        <slot name="description" />
      </p>
      <slot name="aside" />
    </div>
</template>

<script setup>

</script>

<template>
  <div
      class="shadow-md overflow-hidden border border-gray-300 sm:rounded-lg divide-y divide-gray-300">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <slot name="head"/>
      
      </thead>
      <tbody class="divide-y divide-gray-200 bg-white">
        <slot name="body"/>
      </tbody>
    </table>
  </div>
</template>

<style scoped>

</style>

<script setup >
import AppLayout from '@/Layouts/AppLayout.vue';
import {Inertia} from "@inertiajs/inertia";
import {useForm} from "@inertiajs/inertia-vue3";
import JetInputError from '@/Jetstream/InputError.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetFormSection from '@/Jetstream/FormSection.vue';

const props = defineProps({
  client: Object,
  countries: Array
})

const form = useForm({
  name: props.client.name,
  address: props.client.address,
  country: props.client.country.code,
});

const submit = () => {
  form.put(route('clients.update', props.client.id));
};

</script>

<template>
  <AppLayout :title="client.name">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Client {{client.name}}
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <JetFormSection @submit.prevent="submit">
          <template #title>
            Client Information
          </template>
          <template #description>
            Update client information.
          </template>
          <template #form>
            <div class="col-span-6 sm:col-span-4">
              <div>
                <JetLabel for="name" value="Name" />
                <JetInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autofocus
                />
              </div>

              <JetInputError :message="form.errors.name" class="mt-2" />

              <div class="mt-2">
                <JetLabel for="address" value="Address" />
                <JetInput
                    id="address"
                    v-model="form.address"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autofocus
                />
              </div>

              <JetInputError :message="form.errors.address" class="mt-2" />

              <div class="mt-2">
                <JetLabel for="country_id" value="Country" />
                <select
                    id="country_id"
                    v-model="form.country"
                    type="text"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    required
                    autofocus
                >
                  <option v-for="country in countries" :value="country.code" >{{country.name}}</option>
                </select>
              </div>

              <JetInputError :message="form.errors.country_id" class="mt-2" />


              <div class="flex items-center justify-end mt-4">
                <JetButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                  Update
                </JetButton>
              </div>
            </div>
          </template>
        </JetFormSection>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>

</style>

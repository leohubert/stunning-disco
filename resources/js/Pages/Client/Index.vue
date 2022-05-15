<script setup >
import AppLayout from '@/Layouts/AppLayout.vue';
import Datatable from '../Components/Datatable/Datatable';
import DatatableRow from "../Components/Datatable/DatatableRow";
import DatatableHead from "../Components/Datatable/DatatableHead";
import Button from "../../../../vendor/laravel/jetstream/stubs/inertia/resources/js/Jetstream/Button";
import {Inertia} from "@inertiajs/inertia";

defineProps({
  clients: Array
})

function deleteClient(id) {
  Inertia.delete(route('clients.destroy', id))
}

</script>

<template>
  <AppLayout title="Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Clients
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <Datatable>
          <template v-slot:head>
            <DatatableHead>
              #
            </DatatableHead>
            <DatatableHead>
              Name
            </DatatableHead>
            <DatatableHead>
              Address
            </DatatableHead>
            <DatatableHead>
              Country
            </DatatableHead>
            <DatatableHead>
              Actions
            </DatatableHead>
          </template>
          <template v-slot:body>
            <tr v-for="client of clients.data">
              <DatatableRow>
                {{client.id}}
              </DatatableRow>
              <DatatableRow>
                {{client.name}}
              </DatatableRow>
              <DatatableRow>
                {{client.address}}
              </DatatableRow>
              <DatatableRow>
                {{client.country.name}}
              </DatatableRow>
              <DatatableRow>
                <div class="flex space-x-2">
                  <Button @click="() => Inertia.visit(route('clients.edit', client.id))">
                    Edit
                  </Button>
                  <Button class="bg-red-500" @click="() => deleteClient(client.id)">
                    Delete
                  </Button>
                </div>
              </DatatableRow>
            </tr>
          </template>
        </Datatable>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>

</style>

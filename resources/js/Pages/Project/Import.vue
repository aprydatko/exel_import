<template>
    <div>
        Import

        <div class="flex justify-center">
            <form>
                <input @change="setExel" type="file" ref="file" class="hidden" />
                <a @click.prevent="selectExel" href="#" class="block rounded-full bg-green-600 w-32 text-center text-white p-2" >Exel</a>
            </form>
            <div v-if="file" class="ml-3">
                <a @click.prevent="importExel" href="#" class="block rounded-full bg-sky-600 w-32 text-center text-white p-2" >Import</a>
            </div>
        </div>
    </div>
</template>

<script>
import MainLayout from "@/Layouts/MainLayout.vue";

export  default {
    name: "Import",
    layout: MainLayout,
    data() {
      return {
          file: null
      }
    },
    methods: {
        selectExel() {
            this.$refs.file.click();
        },
        setExel(e) {
            this.file = e.target.files[0];
        },
        importExel() {
            const formData = new FormData;
            formData.append('file', this.file);
            this.$inertia.post('/projects/import', formData);
        }
    }
}
</script>

<style scoped>

</style>

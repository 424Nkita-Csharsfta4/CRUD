<template>
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold mb-8">Crud Vue 3 + Php + MySql</h1>
       
            <img src="https://apisero.com/wp-content/uploads/2020/03/Untitled-design-49.png" alt=""
                class=" w-10 h-10">
    
        <div class="flex mb-4">
            <input class="border rounded py-2 px-3 mr-2 w-full" type="text" placeholder="Название объекта"
                v-model="currentObject.name" />
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="createObject">
                Добавить
            </button>
        </div>
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Название</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(object, index) in objects" :key="index">
                    <td class="border px-4 py-2">{{ object.id ? object.id : '-' }}</td>
                    <td class="border px-4 py-2">
                        <input class="border rounded py-2 px-3 w-full" type="text" v-model="object.name" />
                    </td>
                    <td class="border px-4 py-2">
                        <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-2"
                            @click="updateObject(object)">
                            Сохранить
                        </button>
                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                            @click="deleteObject(object.id)">
                            Удалить
                        </button>
                    </td>
                </tr>
                <tr v-if="objects.length === 0">
                    <td class="border px-4 py-2">-</td>
                    <td class="border px-4 py-2">Не записано в бд</td>
                    <td class="border px-4 py-2"></td>
                </tr>
            </tbody>
        </table>
        <p v-if="!isSaved" class="text-red-500">Не удалось сохранить объект "{{ currentObject.name }}"</p>
    </div>
</template>
  
<script>
import axios from 'axios';

export default {
    data() {
        return {
            currentObject: {
                name: '',
            },
            objects: [],
            isSaved: true,
        };
    },
    mounted() {
        this.getObjects();
    },
    methods: {
        getObjects() {
            axios.get('../api/api.php').then((response) => {
                this.objects = response.data;
            });
        },
        createObject() {
            // Установка флага, что запись была сохранена
            this.isSaved = true;

            // Запрос на добавление записи
            axios
                .post('../api/api.php', this.currentObject)
                .then(() => {
                    // Очистка поля ввода
                    this.currentObject.name = '';

                    // Обновление списка объектов
                    this.getObjects();
                })
                .catch((error) => {
                    // Установка флага, что запись не была сохранена
                    this.isSaved = false;
                    console.log(error);
                });
        },
        updateObject(object) {
            axios.put(`../api/api.php?id=${object.id}`, object).then(() => {
                this.getObjects();
            });
        },
        deleteObject(id) {
            axios.delete(`../api/api.php?id=${id}`).then(() => {
                this.getObjects();
            });
        },
    },
    watch: {
        isSaved(newVal) {
            if (!newVal) {
                // Если запись не была сохранена, выводим сообщение с введенными данными
                console.log('Ошибка сохранения объекта', this.currentObject);
            }
        },
    },
};

</script>
  